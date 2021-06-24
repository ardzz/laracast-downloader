<?php


namespace Laracast\Cores\Services\Vimeo\Collection;


use Laracast\Cores\Parser\AbstractParser;

class MetadataCollection extends AbstractParser
{
    function id(){
        return $this->get('id');
    }

    function embedCode(){
        return $this->get('embed_code');
    }

    function title(){
        return $this->get('title');
    }

    function fps(){
        return $this->get('fps');
    }

    function isHD(): bool
    {
        return (boolean) $this->get('hd');
    }
}
