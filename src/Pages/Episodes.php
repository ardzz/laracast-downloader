<?php


namespace Laracast\Pages;


use Laracast\Cores\Http\Request;
use Laracast\Cores\Http\Response;
use Laracast\Cores\Parser\EpisodesParser;

class Episodes extends AbstractPages
{
    function getDetails(string $path): EpisodesParser
    {
        $response = Response::make(function () use ($path){
            return Request::make()->getClient()->get($path);
        });
        return new EpisodesParser($this->getDataPage($response)['props']);
    }
}
