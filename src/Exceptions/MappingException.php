<?php

namespace LTL\Mapping\Exceptions;

use Exception;

class MappingException extends Exception
{
    public function __construct(private array|string $messages)
    {
        $message = $this->messages;
        
        if(is_array($this->messages)) {
            $message = json_encode($message);
        }
        
        parent::__construct($message);
    }

    public function errors(): array|string
    {
        return $this->messages;
    }
}
