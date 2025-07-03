<?php


namespace Cjj\RestfulResponse\Tests\Feature;

use Cjj\RestfulResponse\Tests\TestCase;
use Cjj\RestfulResponse\Facades\ApiResponse;

class ApiResponseTest extends TestCase
{
    public function test_facade_success_response(): void
    {
        $response = ApiResponse::success(['test' => 'data'], 'Success message');

        $data = $response->getData(true);

        $this->assertTrue($data['success']);
        $this->assertEquals('Success message', $data['message']);
        $this->assertEquals(['test' => 'data'], $data['data']);
    }

    public function test_facade_error_response(): void
    {
        $response = ApiResponse::error('Error message', 400);

        $data = $response->getData(true);

        $this->assertFalse($data['success']);
        $this->assertEquals('Error message', $data['message']);
        $this->assertEquals(400, $response->getStatusCode());
    }

}
