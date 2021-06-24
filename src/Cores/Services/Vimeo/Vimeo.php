<?php


namespace Laracast\Cores\Services\Vimeo;


use Laracast\Cores\Http\Response;
use Laracast\Cores\Services\Vimeo\Collection\MetadataCollection;
use Laracast\Cores\Services\Vimeo\Collection\ProgressiveCollection;
use Laracast\Cores\Services\Vimeo\Collection\VideoCollection;
use Laracast\Cores\Services\Vimeo\Cores\Http\VimeoRequest;

class Vimeo
{
    function __construct(protected string|int $idVimeo)
    {
    }

    static function make(string|int $idVimeo): Vimeo
    {
        return new self($idVimeo);
    }

     protected function request(): string
    {
        return Response::make(function () {
            return VimeoRequest::make()->getClient()->get("video/{$this->idVimeo}/config", [
                'query' => [
                    'referrer' => 'https://laracasts.com/'
                ],
                'headers' => [
                    'Referer' => 'https://player.vimeo.com'
                ]
            ]);
        });
    }

    protected function response(){
        return json_decode($this->request(), 1);
    }

    function metadata(){
        return new MetadataCollection($this->response()['video']);
    }

    function progressive(){
        return new ProgressiveCollection($this->response()['request']['files']['progressive']);
    }

    function videos(){
        return $this->progressive()->videos();
    }
}
