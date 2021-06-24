<?php

use Laracast\Cores\Http\Request;
use Laracast\Cores\Parser\EpisodesParser;
use Laracast\Cores\Services\Laracast\Authentication;
use Laracast\Cores\Services\Vimeo\Vimeo;
use Laracast\Laracast;
use Laracast\LaracastConfig;

require 'config.php';

LaracastConfig::setCookie(Authentication::make('gassudah@yandex.com', 'memek123')->visitHomeAfterLogged());

$serieses = Laracast::topics()->getDetails('laravel');

foreach ($serieses->series() as $series){
    $episodes = Laracast::series()->getDetails($series['slug']);
    //echo $episodes->metaData()->title() . PHP_EOL;
    foreach ($episodes->episodes() as $episode){
        $lesson = EpisodesParser::direct($episode);
        var_dump(LaracastConfig::getAuth()->isLogged());
        //echo $lesson->metaData()->vimeoURL() . PHP_EOL;
        //echo $lesson->metaData()->duration() . PHP_EOL;
        //echo '-----------------------------------------' . PHP_EOL;
        //echo '|||||||| you passed episode |||||||||||' . PHP_EOL;
    }
    echo '------ you passed series -------' . PHP_EOL . PHP_EOL;
}
