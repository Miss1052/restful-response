<?php


namespace Cjj\RestfulResponse\Services;



use Cjj\RestfulResponse\Contracts\DataTransformerInterface;

abstract class BaseDataTransformer implements DataTransformerInterface
{
    public function transformCollection(iterable $items): array
    {
        $result = [];
        foreach ($items as $item) {
            $result[] = $this->transform($item);
        }
        return $result;
    }

    /**
     * 子类必须实现此方法
     */
    abstract public function transform(mixed $item): array;
}
