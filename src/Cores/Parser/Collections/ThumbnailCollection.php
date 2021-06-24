<?php


namespace Laracast\Cores\Parser\Collections;


use Laracast\Cores\Parser\AbstractParser;

class ThumbnailCollection extends AbstractParser
{
    function standardSize(){
        return $this->get('thumbnail');
    }

    function largeSize(){
        return $this->get('large_thumbnail');
    }

    function svgThumbnail(){
        return $this->get('svgThumbnail');
    }
}
