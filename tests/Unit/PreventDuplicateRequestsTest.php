<?php

namespace Larowka\PreventDuplicateRequests\Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Larowka\PreventDuplicateRequests\Middleware\PreventDuplicateRequests;
use Orchestra\Testbench\TestCase;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class PreventDuplicateRequestsTest extends TestCase
{
    public function testDoesNotBlockUniqueRequests()
    {
        $middleware = new PreventDuplicateRequests();
        $request = Request::create('/example', 'GET');

        $method = new \ReflectionMethod(PreventDuplicateRequests::class, 'getRequestKey');
        $key = $method->invoke($middleware, $request);

        $this->assertFalse(Cache::has($key));

        $response = $middleware->handle($request, fn () => 'OK');

        $this->assertEquals('OK', $response);

        $this->assertTrue(Cache::has($key));
    }

    public function testBlocksDuplicateRequestsFromAuthorizedUser()
    {
        $middleware = new PreventDuplicateRequests();
        $request = Request::create('/example', 'GET');

        $request->setUserResolver(function () {
            return (object) ['id' => 1];
        });

        $middleware->handle($request, fn () => 'OK');

        $this->expectException(TooManyRequestsHttpException::class);
        $middleware->handle($request, fn () => 'OK');
    }

    public function testBlocksDuplicateRequestsFromUnauthenticatedUser()
    {
        $middleware = new PreventDuplicateRequests();
        $request = Request::create('/example', 'GET');

        $request->setUserResolver(fn () => null);

        $middleware->handle($request, fn () => 'OK');

        $this->expectException(TooManyRequestsHttpException::class);
        $middleware->handle($request, fn () => 'OK');
    }
}
