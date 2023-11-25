<?php
namespace LTL\Mapping;

use ArrayAccess;
use Countable;
use DomainException;
use IteratorAggregate;
use JsonSerializable;
use LTL\Mapping\MappingCollection;
use LTL\Mapping\Traits\Enumerable;
use ReflectionClass;
use ReflectionClassConstant;

abstract class Mapping implements IteratorAggregate, ArrayAccess, Countable, JsonSerializable
{
    use Enumerable;
    
    protected MappingCollection $items;

    public function __construct()
    {
        $constants = (new ReflectionClass($this))->getConstants(ReflectionClassConstant::IS_PUBLIC);

        $this->items = new MappingCollection($constants);
    }

    public function collection(): MappingCollection
    {
        return $this->items;
    }

    public function getAll(): array
    {
        return $this->items->all();
    }

    public static function all(): array
    {
        $self = new static;
        
        return $self->getAll();
    }

    public static function value(string $key): string
    {
        $self = new static;

        return $self[$key];
    }

    /** ArrayAccess */

    public function offsetSet($key, $value): void
    {
    }

    public function offsetUnset($key): void
    {
    }

    public function offsetExists($key): bool
    {
        return isset($this->items[$key]);
    }

    public function offsetGet($key): string
    {
        if($this->offsetExists($key)) {
            return $this->items[$key];
        }

        throw new DomainException('"'. static::class ."::{$key}\" is not mapped");
        
    }

    /** IteratorAggregate */

    public function getIterator(): MappingCollection
    {
        return $this->items;
    }

    /** Countable */

    public function count(): int
    {
        return count($this->items);
    }

    /** JsonSerializable */

    public function jsonSerialize(): array
    {
        return $this->items->all();
    }
}
