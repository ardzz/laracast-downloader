<?php


namespace Laracast\Cores\Parser\Collections;


use Laracast\Cores\Parser\AbstractParser;
use Laracast\Cores\Parser\ParserInterface;

class DirectCollection extends AbstractParser implements ParserInterface
{
    function metaData(): LessonCollection
    {
        return new LessonCollection($this->getData());
    }
}
