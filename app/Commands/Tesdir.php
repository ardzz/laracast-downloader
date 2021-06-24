<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use Laracast\Cores\Http\Request;
use Laracast\Cores\Parser\AbstractParser;
use Laracast\Cores\Parser\Collections\LoginCollection;
use Laracast\Cores\Services\Laracast\Authentication;
use Laracast\Cores\Services\Vimeo\Vimeo;
use Laracast\Laracast;
use Laracast\LaracastConfig;
use LaravelZero\Framework\Commands\Command;

class Tesdir extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'tesdir';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    protected string $session_name = '.laracast_session.json';
    public function handle()
    {
        /*$this->info('Get requirements');
        $required = Authentication::make()->getRequired();
        $login = Request::make()->getClient()->post('/sessions', [
            //'version' => 2,
            'headers' => [
                'Cookie' => $required[0],
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:89.0) Gecko/20100101 Firefox/89.0',
                'Accept' => 'text/html, application/xhtml+xml',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate',
                'Content-Type' => 'application/json;charset=utf-8',
                'X-Requested-With' => 'XMLHttpRequest',
                'X-Inertia' => 'true',
                'X-Inertia-Version' => '9fe991987a4c2e05a180c004725e4f18',
                'X-Xsrf-Token' => $required[1],
                'Content-Length' => '53',
                'Origin' => 'https://laracasts.com',
                'Referer' => 'https://laracasts.com/',
                'Te' => 'trailers',
                'Connection' => 'close'
            ],
            'json' => [
                'email' => env('LARACAST_EMAIL'),
                'password' => env('LARACAST_PASSWORD')
            ],
            'allow_redirects' => false
        ]);
        $cookies = Request::parseCookieFromHeaders($login->getHeader('Set-Cookie'));
        LaracastConfig::setCookie(Request::parserCookie($cookies));
        $loginCollectable = ['cookies' => $cookies];
        Storage::put($this->session_name, json_encode($loginCollectable, JSON_PRETTY_PRINT));
        $output = new LoginCollection($loginCollectable);
        var_dump($output);*/
        Authentication::make()->login();
        $episode = Laracast::episodes()->getDetails('episodes/1183');
        print_r(
            Vimeo::make($episode->metaData()->vimeoId())->metadata()->title()
        );
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
