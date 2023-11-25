<?php

namespace LTL\Mapping\Traits;

use Closure;
use LTL\Mapping\Exceptions\MappingException;
use LTL\Mapping\Mapping;
use LTL\Mapping\MappingCollection;

trait Enumerable
{
    private function verifyClass(): void
    {
        if($this instanceof Mapping) {
            return;
        }
        
        throw new MappingException(__TRAIT__ .' must be used in '. Mapping::class);
    }
    
    /**
     * @param Closure(string $from, mixed $to): bool $callback
     */
    public function each(Closure $callback): MappingCollection
    {
        $this->verifyClass();
        
        /**
         * @var Mapping $this
         */
        $collection = $this->collection();
        
        return $collection->each($callback);
    }

    /**
     * @param Closure(string $from, mixed $to): mixed $callback
     */
    public function map(Closure $callback): MappingCollection
    {
        $this->verifyClass();
        
        /**
         * @var Mapping $this
         */
        $collection = $this->collection();
        
        return $collection->map($callback);
    }

    /**
     * @param Closure(string $from, mixed $to): array<string, mixed> $callback
     */
    public function mapWithKeys(Closure $callback): MappingCollection
    {
        $this->verifyClass();
        
        /**
         * @var Mapping $this
         */
        $collection = $this->collection();
        
        return $collection->map($callback);
    }

    /**
     * @param Closure(string $from, mixed $to): bool $callback
     */
    public function filter(Closure $callback): MappingCollection
    {
        $this->verifyClass();
        
        /**
         * @var Mapping $this
         */
        $collection = $this->collection();
        
        return $collection->filter($callback);
    }
}