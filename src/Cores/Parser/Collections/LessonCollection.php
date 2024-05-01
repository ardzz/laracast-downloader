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

    function vimeoId(){
        return $this->get('vimeoId');
    }
}
