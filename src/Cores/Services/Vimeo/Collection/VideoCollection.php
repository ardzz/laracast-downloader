<?php


namespace Laracast\Cores\Services\Vimeo\Collection;


use Laracast\Cores\Parser\AbstractParser;

class VideoCollection extends AbstractParser
{
    function width(){
        return $this->get('width');
    }

    function height(){
        return $this->get('height');
    }

    function mime(){
        return $this->get('mime');
    }

    function fps(){
        return $this->get('fps');
    }

    function url(){
        return $this->get('url');
    }

    function quality(){
        return $this->get('quality');
    }
}
