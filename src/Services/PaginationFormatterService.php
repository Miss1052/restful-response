<?php


namespace Cjj\RestfulResponse\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Cjj\RestfulResponse\Contracts\PaginationFormatterInterface;

class PaginationFormatterService implements PaginationFormatterInterface
{
    public function format(LengthAwarePaginator $paginator, array $transformedData = []): array
    {
        return [
            'list' => $transformedData,
            'pagination' => [
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
}
