<?php

namespace Cjj\RestfulResponse\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response;
use Cjj\RestfulResponse\Contracts\ResponseFormatterInterface;

class ResponseMacroServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Response::macro('apiSuccess', function ($data = null, $message = null, $code = 200) {
            $formatter = app(ResponseFormatterInterface::class);
            return $formatter->success($data, $message, $code);
        });

        Response::macro('apiError', function ($message = null, $code = 400, $errors = null) {
            $formatter = app(ResponseFormatterInterface::class);
            return $formatter->error($message, $code, $errors);
        });

        Response::macro('apiValidationError', function ($errors, $message = null) {
            $formatter = app(ResponseFormatterInterface::class);
            return $formatter->validationError($errors, $message);
        });
    }
}
