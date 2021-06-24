<?php


namespace Laracast;


use Illuminate\Support\Facades\Auth;
use Laracast\Cores\Parser\Collections\AuthCollection;

class LaracastConfig
{
    static string $cookie;
    static AuthCollection $auth;

    static function setCookie(string $cookie): void
    {
        self::$cookie = $cookie;
    }

    static function getCookie(): ?string
    {
        return !empty(self::$cookie) ? self::$cookie : null;
    }

    static function issetCookie(): bool
    {
        return !empty(self::$cookie);
    }

    /**
     * @param array $auth
     */
    static function setAuth(array $auth): void
    {
        self::$auth = new AuthCollection($auth);
    }

    /**
     * @return AuthCollection
     */
    static function getAuth(): AuthCollection
    {
        return self::$auth;
    }
}
