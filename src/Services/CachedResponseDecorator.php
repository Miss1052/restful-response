<?php

namespace Cjj\RestfulResponse\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Cjj\RestfulResponse\Contracts\ResponseFormatterInterface;

class CachedResponseDecorator implements ResponseFormatterInterface
{
    protected ResponseFormatterInterface $formatter;
    protected int $ttl;

    public function __construct(ResponseFormatterInterface $formatter, int $ttl = 3600)
    {
        $this->formatter = $formatter;
        $this->ttl = $ttl;
    }

    public function success(mixed $data = null, string $message = null, int $code = 200, array $headers = []): JsonResponse
    {
        $cacheKey = $this->generateCacheKey('success', $data, $message, $code);

        return Cache::remember($cacheKey, $this->ttl, function () use ($data, $message, $code, $headers) {
            return $this->formatter->success($data, $message, $code, $headers);
        });
    }

    public function error(string $message = null, int $code = 400, mixed $errors = null, array $headers = []): JsonResponse
    {
        // 错误响应不缓存
        return $this->formatter->error($message, $code, $errors, $headers);
    }

    // 其他方法的实现...
    public function created(mixed $data = null, string $message = null, array $headers = []): JsonResponse
    {
        return $this->formatter->created($data, $message, $headers);
    }

    public function noContent(string $message = null): JsonResponse
    {
        return $this->formatter->noContent($message);
    }

    public function notFound(string $message = null): JsonResponse
    {
        return $this->formatter->notFound($message);
    }

    public function validationError(array $errors, string $message = null): JsonResponse
    {
        return $this->formatter->validationError($errors, $message);
    }

    public function unauthorized(string $message = null): JsonResponse
    {
        return $this->formatter->unauthorized($message);
    }

    public function forbidden(string $message = null): JsonResponse
    {
        return $this->formatter->forbidden($message);
    }

    public function serverError(string $message = null, mixed $debug = null): JsonResponse
    {
        return $this->formatter->serverError($message, $debug);
    }

    protected function generateCacheKey(string $method, ...$params): string
    {
        return 'api_response:' . $method . ':' . md5(serialize($params));
    }
}
