<?php


namespace Laracast\Pages;


use Laracast\Cores\Http\Request;
use Laracast\Cores\Http\Response;
use Laracast\Cores\Parser\TopicsParser;

class Topics extends AbstractPages
{
    function getAll(): array
    {
        $response = Response::make(function (){
            return Request::make()->getClient()->get('browse/all');
        });
        $data = $this->getDataPage($response);
        $collection = collect($data['props']['topics']);

        return $collection->map(function ($item){
            $item['thumbnail'] = 'https://laracasts.com/images/topics/icons/' . $item['thumbnail'];
            $array = explode('/', $item['path']);
            $item['slug'] = end($array);
            return $item;
        })->toArray();
    }

    function getDetails(string $topicsName): TopicsParser
    {
        $response = Response::make(function () use ($topicsName){
            return Request::make()->getClient()->get('topics/' . $topicsName);
        });
        return new TopicsParser($this->getDataPage($response)['props']['topic']);
    }
}