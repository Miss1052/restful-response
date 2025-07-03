<?php


namespace Cjj\RestfulResponse\Facades;

use Illuminate\Support\Facades\Facade;
use Cjj\RestfulResponse\Contracts\ResponseFormatterInterface;

/**
 * @method static \Illuminate\Http\JsonResponse success(mixed $data = null, string $message = null, int $code = 200, array $headers = [])
 * @method static \Illuminate\Http\JsonResponse error(string $message = null, int $code = 400, mixed $errors = null, array $headers = [])
 * @method static \Illuminate\Http\JsonResponse created(mixed $data = null, string $message = null, array $headers = [])
 * @method static \Illuminate\Http\JsonResponse noContent(string $message = null)
 * @method static \Illuminate\Http\JsonResponse notFound(string $message = null)
 * @method static \Illuminate\Http\JsonResponse validationError(array $errors, string $message = null)
 * @method static \Illuminate\Http\JsonResponse unauthorized(string $message = null)
 * @method static \Illuminate\Http\JsonResponse forbidden(string $message = null)
 * @method static \Illuminate\Http\JsonResponse serverError(string $message = null, mixed $debug = null)
 */
class ApiResponse extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ResponseFormatterInterface::class;
    }
}
