<?php

namespace Cjj\RestfulResponse\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator as SimplePaginator;
use Illuminate\Pagination\CursorPaginator;
use Cjj\RestfulResponse\Contracts\PaginationFormatterInterface;

class PaginationFormatterService implements PaginationFormatterInterface
{
    protected array $config;

    /**
     * 构造函数，允许通过 $config 覆盖默认配置。
     *
     * @param array $config 可选的配置数组，用于覆盖默认的 list_key 和 pagination_key。
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'list_key' => 'list',
            'pagination_key' => 'pagination',
        ], $config);
    }

    public function format($paginator, array $transformedData = []): array
    {
        $listKey = $this->config['list_key'];
        $paginationKey = $this->config['pagination_key'];

        if ($paginator instanceof LengthAwarePaginator) {
            return [
                $listKey => $transformedData,
                $paginationKey => [
                    'total' => $paginator->total(),
                    'per_page' => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                    'has_more_pages' => $paginator->hasMorePages(),
                    'next_page_url' => $paginator->nextPageUrl(),
                    'prev_page_url' => $paginator->previousPageUrl(),
                    'first_page_url' => $paginator->url(1),
                    'last_page_url' => $paginator->url($paginator->lastPage()),
                ]
            ];
        }

        if ($paginator instanceof SimplePaginator) {
            return [
                $listKey => $transformedData,
                $paginationKey => [
                    'per_page' => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'has_more_pages' => $paginator->hasMorePages(),
                    'next_page_url' => $paginator->nextPageUrl(),
                    'prev_page_url' => $paginator->previousPageUrl(),
                ]
            ];
        }

        if ($paginator instanceof CursorPaginator) {
            return [
                $listKey => $transformedData,
                $paginationKey => [
                    'per_page' => $paginator->perPage(),
                    'has_more_pages' => $paginator->hasMorePages(),
                    'next_cursor' => $paginator->nextCursor()?->encode(),
                    'prev_cursor' => $paginator->previousCursor()?->encode(),
                    'next_page_url' => $paginator->nextPageUrl(),
                    'prev_page_url' => $paginator->previousPageUrl(),
                ]
            ];
        }

        throw new \InvalidArgumentException('Unsupported paginator type: ' . get_class($paginator));
    }
}
