<?php


namespace Laracast\Cores\Parser\Collections;


use Laracast\Cores\Http\Request;
use Laracast\Cores\Parser\AbstractParser;
use Laracast\Laracast;
use Laracast\LaracastConfig;

class LoginCollection extends AbstractParser
{
    function cookies(): string
    {
        return Request::parserCookie($this->get('cookies'));
    }

    function cookieWithCollection(): \Tightenco\Collect\Support\Collection|\Illuminate\Support\Collection
    {
        return collect($this->get('cookies'));
    }

    function isLoginSuccess(): bool
    {
        return count($this->cookieWithCollection()->toArray()) > 2;
    }

    function isCookieUsable(): AuthCollection
    {
        Laracast::episodes()->getDetails('/episodes/1620');
        return LaracastConfig::getAuth();
    }
}
