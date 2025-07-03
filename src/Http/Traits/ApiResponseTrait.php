<?php


namespace Cjj\RestfulResponse\Http\Traits;

use Cjj\RestfulResponse\Contracts\PaginationFormatterInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Cjj\RestfulResponse\Contracts\ResponseFormatterInterface;

trait ApiResponseTrait
{
    protected function apiResponse(): ResponseFormatterInterface
    {
        return app(ResponseFormatterInterface::class);
    }

    protected function successResponse(mixed $data = null, string $message = null, int $code = 200): JsonResponse
    {
        // 如果有分页器，使用分页格式
        if ($data instanceof LengthAwarePaginator) {
            $paginationFormatter = app(PaginationFormatterInterface::class);
            $paginator = $data;
            // 如果 $data 是数组，直接使用；否则转换为数组
            $list = $paginator->items();

            $formattedData = $paginationFormatter->format($paginator, $list);

            return $this->apiResponse()->success($formattedData, $message, $code);
        }
        return $this->apiResponse()->success($data, $message, $code);
    }

    protected function paginatedResponse(LengthAwarePaginator $paginator,mixed $transformedData = null,string $message = 'Data retrieved successfully'): JsonResponse
    {
        $paginationFormatter = app(PaginationFormatterInterface::class);

        // 如果没有提供转换数据，使用分页器的原始数据
        if ($transformedData === null) {
            $transformedData = $paginator->items();
        }

        $formattedData = $paginationFormatter->format($paginator, $transformedData);
        $formattedData['message'] = $message;

        return response()->json($formattedData, 200);
    }

    protected function errorResponse(string $message = null, int $code = 400, mixed $errors = null): JsonResponse
    {
        return $this->apiResponse()->error($message, $code, $errors);
    }

    protected function createdResponse(mixed $data = null, string $message = null): JsonResponse
    {
        return $this->apiResponse()->created($data, $message);
    }

    protected function notFoundResponse(string $message = null): JsonResponse
    {
        return $this->apiResponse()->notFound($message);
    }

    protected function validationErrorResponse(array $errors, string $message = null): JsonResponse
    {
        return $this->apiResponse()->validationError($errors, $message);
    }

    protected function unauthorizedResponse(string $message = null): JsonResponse
    {
        return $this->apiResponse()->unauthorized($message);
    }

    protected function forbiddenResponse(string $message = null): JsonResponse
    {
        return $this->apiResponse()->forbidden($message);
    }
}
