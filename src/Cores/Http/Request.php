<?php


namespace Laracast\Cores\Http;


use GuzzleHttp\Client;
use GuzzleHttp\Cookie\SetCookie;
use Laracast\Cores\Exception\CookieIsEmpty;
use Laracast\LaracastConfig;

class Request
{
    protected string $base_url = "https://laracasts.com/";

    /**
     * @throws CookieIsEmpty
     */
    function __construct(){
        /*if (!LaracastConfig::issetCookie()){
            throw new CookieIsEmpty('Cookie is empty!');
        }*/
    }

    static function make(): Request
    {
        return new self;
    }

    function getClient(): Client
    {
        if (LaracastConfig::issetCookie()){
            return new Client([
                'base_uri' => $this->base_url,
                'headers' => [
                    'cookie' => LaracastConfig::getCookie()
                ],
                //'proxy' => '127.0.0.1:8080',
                'verify' => false
            ]);
        }else{
            return new Client([
                'base_uri' => $this->base_url,
                //'proxy' => '127.0.0.1:8080',
                'verify' => false
            ]);
        }
    }

    static function parserCookie(array $cookies): string
    {
        $output = [];
        foreach ($cookies as $cookie){
            $output[] = $cookie[0] . '=' . $cookie[1];
        }
        return implode('; ', $output);
    }

    static function parseCookieUseCollection($cookie): SetCookie
    {
        return SetCookie::fromString($cookie);
    }

    static function parseCookieFromHeaders(array $cookies): array
    {
        $output = [];
        foreach ($cookies as $cookie){
            $parseCookie = Request::parseCookieUseCollection($cookie);
            $output[] = [$parseCookie->getName(), $parseCookie->getValue()];
        }
        return $output;
    }
}
