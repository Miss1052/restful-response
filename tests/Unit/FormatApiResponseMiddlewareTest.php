<?php


namespace Cjj\RestfulResponse\Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Cjj\RestfulResponse\Tests\TestCase;
use Cjj\RestfulResponse\Http\Middleware\FormatApiResponse;
use Cjj\RestfulResponse\Contracts\ResponseFormatterInterface;

class FormatApiResponseMiddlewareTest extends TestCase
{
    public function test_middleware_formats_json_response(): void
    {
        $middleware = new FormatApiResponse(app(ResponseFormatterInterface::class));
        $request = new Request();

        $response = $middleware->handle($request, function () {
            return response()->json(['test' => 'data']);
        });

        $this->assertInstanceOf(JsonResponse::class, $response);

        $data = $response->getData(true);
        $this->assertArrayHasKey('success', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('data', $data);
    }

    public function test_middleware_skips_already_formatted_response(): void
    {
        $middleware = new FormatApiResponse(app(ResponseFormatterInterface::class));
        $request = new Request();

        $originalData = [
            'success' => true,
            'message' => 'Already formatted',
            'data' => ['test' => 'data']
        ];

        $response = $middleware->handle($request, function () use ($originalData) {
            return response()->json($originalData);
        });

        $data = $response->getData(true);
        $this->assertEquals($originalData, $data);
    }
}
