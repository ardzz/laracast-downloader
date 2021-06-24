<?php


namespace Laracast\Cores\Http;


use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;

class Response
{
    static function make(Callable $callable): string
    {
        try {
            $response = call_user_func($callable);
            return $response->getBody()->getContents();
        }
        catch (BadResponseException $throwable){
            return $throwable->getResponse()->getBody()->getContents();
        }
        catch (GuzzleException $exception){
            return $exception->getMessage();
        }
    }
}