<?php


namespace Laracast\Cores\Parser;


use Laracast\Cores\Parser\Collections\SeriesCollection;

class SeriesParser extends AbstractParser implements ParserInterface
{
    /**
     * @return array
     */
    protected function getData(): array
    {
        return $this->data;
    }

    function metaData(): SeriesCollection
    {
        return new SeriesCollection($this->getData());
    }

    function episodes(){
        $output = [];
        if ($this->metaData()->hasChapters()){
            foreach ($this->metaData()->chapters() as $chapter){
                $output = array_merge($output, $chapter['episodes']);
            }
            return $output;
        }else{
            return $this->metaData()->chapters()[0]['episodes'];
        }
    }

    function episodesWithCollection(): \Tightenco\Collect\Support\Collection|\Illuminate\Support\Collection
    {
        return collect($this->episodes());
    }
}
