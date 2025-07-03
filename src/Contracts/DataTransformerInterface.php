<?php


namespace Cjj\RestfulResponse\Contracts;

interface DataTransformerInterface
{
    /**
     * 转换单个项目
     */
    public function transform(mixed $item): array;

    /**
     * 转换集合
     */
    public function transformCollection(iterable $items): array;
}
