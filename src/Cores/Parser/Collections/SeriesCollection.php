<?php


namespace Laracast\Cores\Parser\Collections;


use Laracast\Cores\Parser\AbstractParser;
use Laracast\Cores\Parser\ParserInterface;

class SeriesCollection extends AbstractParser
{
    function title(){
        return $this->get('title');
    }

    function description(): string
    {
        return html_entity_decode(strip_tags($this->get('body')));
    }

    function path(){
        return $this->get('path');
    }

    function thumbnail(): ThumbnailCollection
    {
        return new ThumbnailCollection($this->getData());
    }

    function slug(){
        return $this->get('slug');
    }

    function episodeCount(){
        return $this->get('episodeCount');
    }

    function difficultyLevel(){
        return $this->get('difficultyLevel');
    }

    function hasChapters(){
        return $this->get('hasChapters');
    }

    function runTime(){
        return $this->get('runTime');
    }

    function chapters(){
        return $this->get('chapters');
    }
}
