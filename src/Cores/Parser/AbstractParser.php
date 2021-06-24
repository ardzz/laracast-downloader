<?php


namespace Laracast\Cores\Parser;


abstract class AbstractParser
{
    function __construct(protected array $data)
    {
    }

    protected function getData(){
        return $this->data;
    }

    protected function get($key){
        return $this->getData()[$key];
    }

    protected function quoteDecode(string $string): array|string
    {
        return str_replace('&#039;', "'", $string);
    }
}
