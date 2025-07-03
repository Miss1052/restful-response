<?php

namespace Cjj\RestfulResponse\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Cjj\RestfulResponse\Contracts\ResponseFormatterInterface;

class ApiResource extends JsonResource
{
    protected ResponseFormatterInterface $formatter;

    public function __construct($resource, ResponseFormatterInterface $formatter = null)
    {
        parent::__construct($resource);
        $this->formatter = $formatter ?? app(ResponseFormatterInterface::class);
    }

    /**
     * 自定义响应格式
     */
    public function withResponse($request, $response)
    {
        $data = $response->getData(true);

        // 重新格式化响应
        $formattedResponse = $this->formatter->success($data);
        $response->setContent($formattedResponse->getContent());
        $response->setStatusCode($formattedResponse->getStatusCode());
    }
}
