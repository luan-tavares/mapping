<?php
namespace LTL\Mapping;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use LTL\Mapping\Exceptions\MappingException;
use LTL\Mapping\MappingCollection;
use LTL\Mapping\Traits\Enumerable;
use ReflectionClass;
use ReflectionClassConstant;

abstract class Mapping implements IteratorAggregate, ArrayAccess, Countable, JsonSerializable
{
    use Enumerable;

    protected array $data;
    
    protected MappingCollection $items;

    public function __construct()
    {
        if(isset($this->data)) {
            $data = $this->data;
            unset($this->data);
            $this->items = new MappingCollection($data, static::class, true);

            return;
        }

        $constants = (new ReflectionClass($this))->getConstants(ReflectionClassConstant::IS_PUBLIC);

        $this->items = new MappingCollection($constants, static::class, true);
    }

    public function __get($name)
    {
        throw new MappingException('You can not access public property in '. static::class .'.');
    }

    public function __set($name, $value)
    {
        throw new MappingException('You can not change or create public property in '. static::class .'.');
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

    public static function keys(): array
    {
        $self = new static;
        
        return array_keys($self->getAll());
    }

    public static function values(): array
    {
        $self = new static;
        
        return array_values($self->getAll());
    }

    public static function value(string $key): string
    {
        $self = new static;

        return $self[$key];
    }

    /** ArrayAccess */

    public function offsetSet($key, $value): void
    {
        $this->items->offsetSet($key, $value);
    }

    public function offsetUnset($key): void
    {
        $this->items->offsetUnset($key);
    }

    public function offsetExists($key): bool
    {
        return $this->items->offsetExists($key);
    }

    public function offsetGet($key): string
    {
        return $this->items->offsetGet($key);
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
        return $this->getAll();
    }
}
