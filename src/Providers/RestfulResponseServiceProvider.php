<?php


namespace Cjj\RestfulResponse\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Cjj\RestfulResponse\Contracts\ResponseFormatterInterface;
use Cjj\RestfulResponse\Contracts\PaginationFormatterInterface;
use Cjj\RestfulResponse\Services\ResponseFormatterService;
use Cjj\RestfulResponse\Services\PaginationFormatterService;
use Cjj\RestfulResponse\Exceptions\ApiExceptionHandler;

class RestfulResponseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 合并配置
        $this->mergeConfigFrom(__DIR__ . '/../../config/restful-response.php', 'restful-response');

        // 绑定服务 ResponseFormatterService
        $this->app->singleton(ResponseFormatterInterface::class, function ($app) {
            return new ResponseFormatterService($app['config']['restful-response']);
        });

        // 绑定服务 PaginationFormatterService
        $this->app->singleton(PaginationFormatterInterface::class, function ($app) {
            return new PaginationFormatterService([
                'list_key' => 'rows',        // 你需要的自定义key
                'pagination_key' => 'pager', // 你需要的自定义key
            ]);
        });


        // 注册异常处理器
        $this->app->singleton('restful-response.exception-handler', function ($app) {
            return new ApiExceptionHandler($app[ResponseFormatterInterface::class]);
        });
    }

    public function boot(): void
    {
        // 发布配置文件
        $this->publishes([
            __DIR__ . '/../../config/restful-response.php' => config_path('restful-response.php'),
        ], 'restful-response-config');

        // 发布语言文件
        // 确保先加载翻译再发布
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'restful-response');

        $this->publishes([
            __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/restful-response'),
        ], 'restful-response-lang');



        // 扩展异常处理器
        if (config('restful-response.handle_exceptions', true)) {
            $this->extendExceptionHandler();
        }
    }

    protected function extendExceptionHandler(): void
    {
        $this->app->extend(ExceptionHandler::class, function ($handler, $app) {
            $apiHandler = $app['restful-response.exception-handler'];

            // 包装原有的render方法
            $originalRender = $handler->render(...);

            $handler->render = function ($request, $exception) use ($originalRender, $apiHandler) {
                $apiResponse = $apiHandler->render($request, $exception);
                return $apiResponse ?? $originalRender($request, $exception);
            };

            return $handler;
        });
    }
}
