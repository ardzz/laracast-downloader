<?php


namespace Laracast\Cores\Parser;


use Laracast\Cores\Parser\Collections\DirectCollection;
use Laracast\Cores\Parser\Collections\LessonCollection;

class EpisodesParser extends AbstractParser implements ParserInterface
{
    protected function getData()
    {
        return $this->data;
    }

    protected function directAccess(){
        return new DirectCollection($this->getData());
    }

    static function direct(array $data): DirectCollection
    {
        return (new self($data))->directAccess();
    }

    function metaData(): LessonCollection
    {
        return new LessonCollection($this->getData()['lesson']);
    }

    function series(): SeriesParser
    {
        return new SeriesParser($this->getData()['series']);
    }
}
