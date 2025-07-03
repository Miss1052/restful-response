<?php


namespace Cjj\RestfulResponse\Tests\Unit;

use Cjj\RestfulResponse\Tests\TestCase;
use Cjj\RestfulResponse\Services\ResponseFormatterService;

class ResponseFormatterServiceTest extends TestCase
{
    protected ResponseFormatterService $formatter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->formatter = new ResponseFormatterService();
    }

    public function test_success_response_structure(): void
    {
        $response = $this->formatter->success(['key' => 'value'], 'Test message');

        $data = $response->getData(true);

        $this->assertTrue($data['success']);
        $this->assertEquals('Test message', $data['message']);
        $this->assertEquals(['key' => 'value'], $data['data']);
        $this->assertArrayHasKey('meta', $data);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_error_response_structure(): void
    {
        $response = $this->formatter->error('Error message', 400, ['error' => 'details']);

        $data = $response->getData(true);

        $this->assertFalse($data['success']);
        $this->assertEquals('Error message', $data['message']);
        $this->assertEquals(['error' => 'details'], $data['errors']);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_validation_error_response(): void
    {
        $errors = ['name' => ['Name is required']];
        $response = $this->formatter->validationError($errors);

        $data = $response->getData(true);

        $this->assertFalse($data['success']);
        $this->assertEquals('Validation failed', $data['message']);
        $this->assertEquals($errors, $data['errors']);
        $this->assertEquals(422, $response->getStatusCode());
    }
}
