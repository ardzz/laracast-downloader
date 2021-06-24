<?php


namespace Laracast\Cores\Parser\Collections;


use Laracast\Cores\Parser\AbstractParser;

class AuthCollection extends AbstractParser
{
    function isLogged(){
        return $this->get('signedIn');
    }

    function user(): UserCollection
    {
        return new UserCollection($this->get('user'));
    }
}
