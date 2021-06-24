<?php


namespace Laracast;


use Laracast\Pages\Episodes;
use Laracast\Pages\Series;
use Laracast\Pages\Topics;

class Laracast
{
    static function episodes(): Episodes
    {
        return new Episodes();
    }

    static function series(): Series
    {
        return new Series();
    }

    static function topics(): Topics
    {
        return new Topics();
    }
}
