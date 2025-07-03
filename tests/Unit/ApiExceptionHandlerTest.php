<?php


namespace Cjj\RestfulResponse\Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Cjj\RestfulResponse\Tests\TestCase;
use Cjj\RestfulResponse\Exceptions\ApiExceptionHandler;
use Cjj\RestfulResponse\Contracts\ResponseFormatterInterface;

class ApiExceptionHandlerTest extends TestCase
{
    protected ApiExceptionHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->handler = new ApiExceptionHandler(app(ResponseFormatterInterface::class));
    }

    public function test_handles_validation_exception(): void
    {
        $request = new Request();
        $request->headers->set('Accept', 'application/json');

        $exception = ValidationException::withMessages(['name' => ['Name is required']]);

        $response = $this->handler->render($request, $exception);

        $this->assertNotNull($response);
        $this->assertEquals(422, $response->getStatusCode());

        $data = $response->getData(true);
        $this->assertFalse($data['success']);
        $this->assertArrayHasKey('errors', $data);
    }

    public function test_handles_authentication_exception(): void
    {
        $request = new Request();
        $request->headers->set('Accept', 'application/json');

        $exception = new AuthenticationException('Unauthenticated');

        $response = $this->handler->render($request, $exception);

        $this->assertNotNull($response);
        $this->assertEquals(401, $response->getStatusCode());

        $data = $response->getData(true);
        $this->assertFalse($data['success']);
    }

    public function test_returns_null_for_non_json_requests(): void
    {
        $request = new Request();
        // 不设置Accept header，模拟非JSON请求

        $exception = new \Exception('Test exception');

        $response = $this->handler->render($request, $exception);

        $this->assertNull($response);
    }
}
