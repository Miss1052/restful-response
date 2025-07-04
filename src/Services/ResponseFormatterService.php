<?php

namespace Cjj\RestfulResponse\Services;

use Illuminate\Contracts\Translation\Translator;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Cjj\RestfulResponse\Contracts\ResponseFormatterInterface;

class ResponseFormatterService implements ResponseFormatterInterface
{
    protected array $config;
    /**
     * @var \Illuminate\Contracts\Foundation\Application|Application|mixed
     */
//    private Translator $translator;

    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'include_timestamp' => true,
            'include_status_text' => false,
            'data_wrapper' => 'data',
            'message_wrapper' => 'message',
            'success_wrapper' => 'success',
            'errors_wrapper' => 'errors',
            'meta_wrapper' => 'meta',
            'translation_key_prefix' => 'restful-response::messages', // 添加翻译键前缀
        ], $config);
        $translator = app(Translator::class);
        $this->translator = $translator;
    }

    public function success(mixed $data = null, string $message = null, int $code = 200, array $headers = []): JsonResponse
    {
        $response = [
            $this->config['success_wrapper'] => true,
            $this->config['message_wrapper'] => $message ?? $this->getStatusMessage($code),
        ];


        if ($data !== null) {
            $response[$this->config['data_wrapper']] = $data;
        }

        $this->addMeta($response, $code);

        return response()->json($response, $code, $headers);
    }

    public function error(string $message = null, int $code = 400, mixed $errors = null, array $headers = []): JsonResponse
    {
        $response = [
            $this->config['success_wrapper'] => false,
            $this->config['message_wrapper'] => $message ?? $this->getStatusMessage($code),
        ];

        if ($errors !== null) {
            $response[$this->config['errors_wrapper']] = $errors;
        }

        $this->addMeta($response, $code);

        return response()->json($response, $code, $headers);
    }

    public function created(mixed $data = null, string $message = null, array $headers = []): JsonResponse
    {
        return $this->success($data, $message ?? 'Created successfully', 201, $headers);
    }

    public function noContent(string $message = null): JsonResponse
    {
        return $this->success(null, $message ?? 'No content', 204);
    }

    public function notFound(string $message = null): JsonResponse
    {
        return $this->error($message ?? 'Resource not found', 404);
    }

    public function validationError(array $errors, string $message = null): JsonResponse
    {
        return $this->error($message ?? 'Validation failed', 422, $errors);
    }

    public function unauthorized(string $message = null): JsonResponse
    {
        return $this->error($message ?? 'Unauthorized', 401);
    }

    public function forbidden(string $message = null): JsonResponse
    {
        return $this->error($message ?? 'Forbidden', 403);
    }

    public function serverError(string $message = null, mixed $debug = null): JsonResponse
    {
        $errors = null;
        if ($debug !== null && config('app.debug')) {
            $errors = $debug;
        }

        return $this->error($message ?? 'Internal server error', 500, $errors);
    }

    protected function addMeta(array &$response, int $code): void
    {
        \Log::info('addme');
        $meta = [];

        if ($this->config['include_timestamp']) {
            $meta['timestamp'] = now()->toISOString();
        }

        \Log::info('confi',['config'=>$this->config]);
        if ($this->config['include_status_text']) {
            $meta['status_code'] = $code;
            $meta['status_text'] = $this->getStatusText($code);
        }

        if (!empty($meta)) {
            $response[$this->config['meta_wrapper']] = $meta;
        }
    }

//    protected function getStatusMessageV1(int $code): string
//    {
//        $messages = [
//            200 => $this->config['success_message'],
//            201 => 'Created successfully',
//            204 => 'No content',
//            400 => 'Bad request',
//            401 => 'Unauthorized',
//            403 => 'Forbidden',
//            404 => 'Not found',
//            422 => 'Validation failed',
//            500 => 'Internal server error',
//        ];
//
//        return $messages[$code] ?? $this->config['error_message'];
//    }

    protected function getStatusMessage(int $code): string
    {
        $translationKeys = [
            200 => 'success',
            201 => 'created',
            204 => 'no_content',
            400 => 'bad_request',
            401 => 'unauthorized',
            403 => 'forbidden',
            404 => 'not_found',
            422 => 'validation_failed',
            500 => 'server_error',
        ];
        $translationKey = $translationKeys[$code] ?? null;
        if ($translationKey) {
            $message = $this->translator->get($this->config['translation_key_prefix'] . '.' . $translationKey);
            if ($message) {
                return $message;
            }
        }
       return$messages[$code] ?? $this->config['error_message'];
    }

    protected function getStatusText(int $code): string
    {
        $statusTexts = [
            200 => 'OK',
            201 => 'Created',
            204 => 'No Content',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            422 => 'Unprocessable Entity',
            500 => 'Internal Server Error',
        ];

        return $statusTexts[$code] ?? 'Unknown Status';
    }
}
