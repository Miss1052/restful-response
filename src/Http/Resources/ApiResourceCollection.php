<?php


namespace Cjj\RestfulResponse\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Cjj\RestfulResponse\Contracts\ResponseFormatterInterface;
use Cjj\RestfulResponse\Contracts\PaginationFormatterInterface;

class ApiResourceCollection extends ResourceCollection
{
    protected ResponseFormatterInterface $formatter;
    protected PaginationFormatterInterface $paginationFormatter;

    public function __construct(
        $resource,
        ResponseFormatterInterface $formatter = null,
        PaginationFormatterInterface $paginationFormatter = null
    )
    {
        parent::__construct($resource);
        $this->formatter = $formatter ?? app(ResponseFormatterInterface::class);
        $this->paginationFormatter = $paginationFormatter ?? app(PaginationFormatterInterface::class);
    }

    public function withResponse($request, $response)
    {
        $data = $response->getData(true);

        // 检查是否为分页数据
        if ($this->resource instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
            $formattedData = $this->paginationFormatter->format($this->resource, $data['data']);
            $formattedResponse = $this->formatter->success($formattedData);
        } else {
            $formattedResponse = $this->formatter->success($data);
        }

        $response->setContent($formattedResponse->getContent());
        $response->setStatusCode($formattedResponse->getStatusCode());
    }
}
