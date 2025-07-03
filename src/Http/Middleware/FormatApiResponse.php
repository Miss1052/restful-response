<?php


namespace Cjj\RestfulResponse\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Cjj\RestfulResponse\Contracts\ResponseFormatterInterface;

class FormatApiResponse
{
    protected ResponseFormatterInterface $formatter;

    public function __construct(ResponseFormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // 只处理JSON响应
        if (!$response instanceof JsonResponse) {
            return $response;
        }

        // 检查是否已经是标准格式
        $data = $response->getData(true);
        if (isset($data['success']) && isset($data['message'])) {
            return $response;
        }

        // 根据状态码格式化响应
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 200 && $statusCode < 300) {
            $formattedResponse = $this->formatter->success($data, null, $statusCode);
        } else {
            $message = $data['message'] ?? null;
            $errors = $data['errors'] ?? $data;
            $formattedResponse = $this->formatter->error($message, $statusCode, $errors);
        }

        return $formattedResponse;
    }
}
