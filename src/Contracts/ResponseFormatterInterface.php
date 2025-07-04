<?php

namespace Cjj\RestfulResponse\Contracts;

use Illuminate\Http\JsonResponse;

interface ResponseFormatterInterface
{
    /**
     * 成功响应
     */
    public function success(mixed $data = null, string $message = null, int $code = 200, array $headers = []): JsonResponse;



    /**
     * 错误响应
     */
    public function error(string $message = null, int $code = 400, mixed $errors = null, array $headers = []): JsonResponse;

    /**
     * 创建成功响应
     */
    public function created(mixed $data = null, string $message = null, array $headers = []): JsonResponse;

    /**
     * 无内容响应
     */
    public function noContent(string $message = null): JsonResponse;

    /**
     * 未找到响应
     */
    public function notFound(string $message = null): JsonResponse;

    /**
     * 验证错误响应
     */
    public function validationError(array $errors, string $message = null): JsonResponse;

    /**
     * 未授权响应
     */
    public function unauthorized(string $message = null): JsonResponse;

    /**
     * 禁止访问响应
     */
    public function forbidden(string $message = null): JsonResponse;

    /**
     * 服务器错误响应
     */
    public function serverError(string $message = null, mixed $debug = null): JsonResponse;
}
