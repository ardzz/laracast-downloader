<?php


namespace Laracast\Cores\Services\Vimeo\Cores\Http;


use GuzzleHttp\Client;

class VimeoRequest
{
    protected string $base_url = "https://player.vimeo.com/";

    function getClient(): Client
    {
        return new Client([
            "base_uri" => $this->base_url
            //'proxy' => '127.0.0.1:8889'
        ]);
    }

    static function make(): VimeoRequest
    {
        return new self();
    }
}
