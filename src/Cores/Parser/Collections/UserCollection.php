<?php


namespace Laracast\Cores\Parser\Collections;


use Laracast\Cores\Parser\AbstractParser;

class UserCollection extends AbstractParser
{
    function id(){
        return $this->get('id');
    }

    function username(){
        return $this->get('username');
    }

    function email(){
        return $this->get('email');
    }

    function subscribed(){
        return $this->get('subscribed');
    }

    function canceled(){
        return $this->get('canceled');
    }

    function expired(){
        return $this->get('expired');
    }

    function active(){
        return $this->get('active');
    }

    function billable(){
        return $this->get('billable');
    }

    function streaking(){
        return $this->get('streaking');
    }
}
