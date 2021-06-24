<?php


namespace Laracast\Cores\Services\Laracast;


use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Storage;
use Laracast\Cores\Exception\EmailIsEmpty;
use Laracast\Cores\Exception\EnviromentAreEmpty;
use Laracast\Cores\Exception\PasswordIsEmpty;
use Laracast\Cores\Http\Request;
use Laracast\Cores\Parser\AbstractParser;
use Laracast\Cores\Parser\Collections\LoginCollection;
use Laracast\LaracastConfig;

class Authentication
{
    protected $session_name = 'session.json';

    /**
     * @throws PasswordIsEmpty
     * @throws EmailIsEmpty
     * @throws EnviromentAreEmpty
     */
    function __construct(){
        $email    = empty(env('LARACAST_EMAIL'));
        $password = empty(env('LARACAST_PASSWORD'));
        if ($email && $password){
            throw new EnviromentAreEmpty('environment are empty!');
        }
        elseif($email){
            throw new EmailIsEmpty('environment LARACAST_EMAIL is empty!');
        }
        elseif ($password){
            throw new PasswordIsEmpty('environment LARACAST_PASSWORD is empty!');
        }
    }

    static function make()
    {
        return new self();
    }

    function getRequired(){
        $firstVisit = Request::make()->getClient()->get('/', [
            'version' => 2,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:89.0) Gecko/20100101 Firefox/89.0',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate',
                'Upgrade-Insecure-Requests' => '1',
                'Te' => 'trailers',
                'Connection' => 'close'
            ]
        ]);
        $output = [];
        foreach ($firstVisit->getHeader('Set-Cookie') as $firstVisitCookies){
            $cookie = Request::parseCookieUseCollection($firstVisitCookies);
            $output[] = [$cookie->getName(), $cookie->getValue()];
        }
        return [urldecode(Request::parserCookie($output)), urldecode($output[0][1])];
    }

    function login(){
        if ($this->isSessionReplaceable()){
            try {
                $required = $this->getRequired();
                $login = Request::make()->getClient()->post('/sessions', [
                    'version' => 2,
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
                Storage::put('session.json', json_encode($loginCollectable, JSON_PRETTY_PRINT));
                return new LoginCollection($loginCollectable);
            }
            catch (BadResponseException $badResponseException){
                $cookies = Request::parseCookieFromHeaders($badResponseException->getResponse()->getHeader('Set-Cookie'));
                LaracastConfig::setCookie(Request::parserCookie($cookies));
                $loginCollectable = ['cookies' => $cookies];
                return new LoginCollection($loginCollectable);
            }
        }
        else{
            $cookies = $this->loadCookieFromStorage();
            LaracastConfig::setCookie(Request::parserCookie($cookies['cookies']));
            return new LoginCollection($cookies);
        }
    }

    function isSessionReplaceable(): bool
    {
        if ($cookies = $this->loadCookieFromStorage()){
            LaracastConfig::setCookie(Request::parserCookie($cookies['cookies']));
            return !(new LoginCollection($cookies))->isCookieUsable()->isLogged();
        }else{
            return true;
        }
    }

    function loadCookieFromStorage(): mixed
    {
        if (Storage::exists($this->session_name)){
            return json_decode(Storage::get($this->session_name), 1);
        }else{
            return false;
        }
    }
}
