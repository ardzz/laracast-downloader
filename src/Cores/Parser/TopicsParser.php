<?php


namespace Laracast\Cores\Parser;


use Laracast\Cores\Parser\Collections\TopicsCollection;

class TopicsParser extends AbstractParser implements ParserInterface
{
    /**
     * @return array
     */
    protected function getData(): array
    {
        return $this->data;
    }

    function metaData(){
        return new TopicsCollection($this->getData());
    }

    function series(){
        return $this->getData()['series'];
    }

    function seriesWithCollection(): \Illuminate\Support\Collection|\Tightenco\Collect\Support\Collection
    {
        return collect($this->series());
    }
}
