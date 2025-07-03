<?php

namespace Cjj\RestfulResponse\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PaginationFormatterInterface
{
    /**
     * 格式化分页数据
     */
    public function format(LengthAwarePaginator $paginator, array $transformedData = []): array;
}
