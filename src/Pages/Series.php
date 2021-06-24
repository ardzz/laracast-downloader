<?php


namespace Laracast\Pages;


use Laracast\Cores\Http\Request;
use Laracast\Cores\Http\Response;
use Laracast\Cores\Parser\SeriesParser;

class Series extends AbstractPages
{
    function getDetails(string $slug): SeriesParser
    {
        $response = Response::make(function () use ($slug){
            return Request::make()->getClient()->get('series/' . $slug);
        });
        return new SeriesParser($this->getDataPage($response)['props']['series']);
    }
}
