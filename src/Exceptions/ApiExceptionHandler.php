<?php


namespace Cjj\RestfulResponse\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Cjj\RestfulResponse\Contracts\ResponseFormatterInterface;

class ApiExceptionHandler
{
    protected ResponseFormatterInterface $formatter;

    public function __construct(ResponseFormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function render(Request $request, Throwable $e): ?JsonResponse
    {
        // 只处理API请求
        if (!$request->expectsJson()) {
            return null;
        }

        return match (true) {
            $e instanceof ValidationException => $this->handleValidationException($e),
            $e instanceof AuthenticationException => $this->handleAuthenticationException($e),
            $e instanceof AuthorizationException => $this->handleAuthorizationException($e),
            $e instanceof ModelNotFoundException,
                $e instanceof NotFoundHttpException => $this->handleNotFoundException($e),
            $e instanceof MethodNotAllowedHttpException => $this->handleMethodNotAllowedException($e),
            default => $this->handleGenericException($e),
        };
    }

    protected function handleValidationException(ValidationException $e): JsonResponse
    {
        return $this->formatter->validationError($e->errors(), $e->getMessage());
    }

    protected function handleAuthenticationException(AuthenticationException $e): JsonResponse
    {
        return $this->formatter->unauthorized($e->getMessage());
    }

    protected function handleAuthorizationException(AuthorizationException $e): JsonResponse
    {
        return $this->formatter->forbidden($e->getMessage());
    }

    protected function handleNotFoundException(Throwable $e): JsonResponse
    {
        return $this->formatter->notFound('Resource not found');
    }

    protected function handleMethodNotAllowedException(MethodNotAllowedHttpException $e): JsonResponse
    {
        return $this->formatter->error('Method not allowed', 405);
    }

    protected function handleGenericException(Throwable $e): JsonResponse
    {
        $debug = config('app.debug') ? [
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ] : null;

        return $this->formatter->serverError($e->getMessage(), $debug);
    }
}
