<?php


namespace Laracast\Cores\Parser\Collections;


use Laracast\Cores\Parser\AbstractParser;
use Spatie\Url\Url;

class LessonCollection extends AbstractParser
{
    function title(){
        return str_replace('/', ' or ', $this->quoteDecode($this->get('title')));
    }

    function description(): string
    {
        return html_entity_decode($this->get('excerpt'));
    }

    function duration(){
        return $this->get('lengthForHumans');
    }

    function vimeoURL(){
        return $this->get('download');
    }

    function vimeoId(){
        $url = Url::fromString($this->vimeoURL());
        return (int) str_replace('.hd.mp4', '', $url->getSegment(2));
    }
}
