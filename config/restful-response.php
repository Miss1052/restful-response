<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Response Format Configuration
    |--------------------------------------------------------------------------
    */

    // 是否包含时间戳
    'include_timestamp' => env('API_INCLUDE_TIMESTAMP', true),

    // 是否包含状态文本
    'include_status_text' => env('API_INCLUDE_STATUS_TEXT', true),

    // 默认消息
    'success_message' => 'Success',
    'error_message' => 'Error',

    // 响应字段包装器
    'data_wrapper' => 'data',
    'message_wrapper' => 'message',
    'success_wrapper' => 'success',
    'errors_wrapper' => 'errors',
    'meta_wrapper' => 'meta',

    // 分页配置
    'pagination' => [
        'include_links' => true,
        'include_meta' => true,
    ],

    // 异常处理
    'handle_exceptions' => env('API_HANDLE_EXCEPTIONS', true),

    // 调试模式下是否显示异常详情
    'show_exception_details' => env('APP_DEBUG', false),

    // 自定义状态消息
    'status_messages' => [
        200 => 'Success',
        201 => 'Created successfully',
        204 => 'No content',
        400 => 'Bad request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Resource not found',
        422 => 'Validation failed',
        500 => 'Internal server error',
    ],

    // 中间件配置
    'middleware' => [
        'format_response' => env('API_FORMAT_RESPONSE', true),
    ],
];
