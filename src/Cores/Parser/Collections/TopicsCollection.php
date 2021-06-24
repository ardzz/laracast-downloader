<?php


namespace Laracast\Cores\Parser\Collections;


use Laracast\Cores\Parser\AbstractParser;

class TopicsCollection extends AbstractParser
{
    function getName(){
        return $this->get('name');
    }

    function getEpisodeCount(){
        return $this->get('episode_count');
    }

    function getPath(){
        return $this->get('path');
    }
}
