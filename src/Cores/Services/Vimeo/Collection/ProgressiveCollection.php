<?php


namespace Laracast\Cores\Services\Vimeo\Collection;


use Laracast\Cores\Parser\AbstractParser;

class ProgressiveCollection extends AbstractParser
{
    function videos(){
        return $this->getData();
    }

    function videosWithCollection(): array
    {
        $output = [];
        $videos = $this->videos();

        foreach ($videos as $video){
            $output[] = new VideoCollection($video);
        }

        /** @var $output VideoCollection[] */
        return $output;
    }
}
