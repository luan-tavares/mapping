<?php

namespace LTL\Mapping;

use ArrayAccess;
use Closure;
use Countable;
use Iterator;
use LTL\Mapping\Exceptions\MappingException;

class MappingCollection implements Countable, Iterator, ArrayAccess
{
    private array $data;

    public function __construct(array $data = [])
    {
        $this->pushAll($data);
    }

    public function all(): array
    {
        return $this->data;
    }

    public function pushAll(array $data): void
    {
        foreach ($data as $from => $item) {
            $this->push($from, $item);
        }
    }

    public function push(string $from, mixed $to): void
    {
        $this->data[$from] = $to;
    }

    /**
     * @param Closure(string $from, mixed $to): bool $callback
     */
    public function each(Closure $callback): self
    {
        foreach ($this->data as $from => $to) {
            $callback($from, $to);
        }
       
        return $this;
    }

    /**
     * @param Closure(string $from, mixed $to): mixed $callback
     */
    public function map(Closure $callback): self
    {
        $result = [];
        
        foreach ($this->data as $from => $to) {
            $result[] = $callback($from, $to);
        }

        return new self($result);
    }

    /**
     * @param Closure(string $from, mixed $to): array<string, mixed> $callback
     */
    public function mapWithKeys(Closure $callback): self
    {
        $result = [];

        $i = 0;
        
        foreach ($this->data as $from => $to) {
            $return = $callback($from, $to);

            if($i === 0) {
                $this->verifyMapWithKeys($return);
            }

            $result[key($return)] = current($return);
            
            $i++;
        }

        return new self($result);
    }

    private function verifyMapWithKeys($return): void
    {
        if(!is_array($return)) {
            throw new MappingException('Incorrect mapWithKeys(...) in '. static::class .'.');
        }

        if(count($return) !== 1) {
            throw new MappingException('Incorrect mapWithKeys(...) in '. static::class .'.');
        }
    }

    /**
     * @param Closure(string $from, mixed $to): bool $callback
     */
    public function filter(Closure $callback): self
    {
        $result = [];
        
        foreach ($this->data as $from => $to) {
            if ($callback($from, $to)) {
                $result[$from] = $to;
            }
        }

        return new self($result);
    }

    /**Iterator */

    public function rewind(): void
    {
        reset($this->data);
    }
    
    public function current(): mixed
    {
        return current($this->data);
    }
    
    public function key(): string
    {
        return key($this->data);
    }
    
    public function next(): void
    {
        next($this->data);
    }
    
    public function valid(): bool
    {
        return !is_null(key($this->data));
    }

    /** Countable */

    public function count(): int
    {
        return count($this->data);
    }

    /** ArrayAccess */

    public function offsetSet($offset, $to): void
    {
        throw new MappingException("You can't add or change property in ". static::class .'.');
    }
    
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->data);
    }
    
    public function offsetUnset($offset): void
    {
        throw new MappingException("You can't remove property in ". static::class .'.');
    }
    
    public function offsetGet($offset): mixed
    {
        if(!$this->offsetExists($offset)) {
            throw new MappingException("Property \"{$offset}\" does not exist in ". static::class .'.');
        }
    
        return $this->data[$offset];
    }
}