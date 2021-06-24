<?php


namespace Laracast\Cores\Http;


use GuzzleHttp\Client;
use LaravelZero\Framework\Commands\Command;
use ScriptFUSION\Byte\ByteFormatter;

trait Download
{

    static function start(string $url, string $path): void
    {
        (new self)->startDownload($url, $path);
    }

    function startDownload(string $url, string $path){
        $progress = null;
        $startTime = $prevTime = microtime(true);
        $prevSize = 0;

        $client = new Client([
            'sink'     => $path,
            'progress' => function ($total_size, $current_size) use (&$progress, &$startTime, &$prevTime, &$prevSize){
                if ($total_size > 0 && $current_size > 0 && null === $progress){
                    $progress = $this->output->createProgressBar($total_size);
                    $progress->setMessage('0', 'current_size');
                    $progress->setMessage('0' . '/s', 'speed');
                    $progress->setMessage((new ByteFormatter)->format($total_size), 'total_size');
                    $progress->setFormat("%current_size%/%total_size% [%bar%] %percent%% [%speed%]");
                    $progress->start();
                }
                if ($progress != NULL){
                    if ($current_size == $total_size){
                        $progress->setProgress($current_size);
                        $progress->finish();
                    }else{
                        $averageSpeed = $current_size / (microtime(true) - $startTime);
                        $currentSpeed = ($current_size - $prevSize) / (microtime(true) - $prevTime);
                        $prevTime = microtime(true);
                        $prevSize = $current_size;
                        $timeRemaining = ($current_size - $total_size) / $averageSpeed;

                        $progress->setMessage(trim((new ByteFormatter)->format($current_size)), 'current_size');
                        $progress->setMessage((new ByteFormatter)->format($currentSpeed) . '/s', 'speed');
                        $progress->setProgress($current_size);
                    }
                }
            },
        ]);

        $client->get($url);
    }
}
