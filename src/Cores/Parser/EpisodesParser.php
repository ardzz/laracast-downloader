<?php


namespace Laracast\Cores\Parser;


use Laracast\Cores\Http\Request;
use Laracast\Cores\Http\Response;
use Laracast\Cores\Parser\Collections\DirectCollection;
use Laracast\Cores\Parser\Collections\LessonCollection;

class EpisodesParser extends AbstractParser implements ParserInterface
{
    protected function directAccess(){
        return new DirectCollection($this->getData());
    }

    static function direct(array $data): DirectCollection
    {
        return (new self($data))->directAccess();
    }

    function metaData(): LessonCollection
    {
        return new LessonCollection($this->getData()['lesson']);
    }

    function series(): SeriesParser
    {
        return new SeriesParser($this->getData()['series']);
    }

    function getDirectLink(): string
    {
        $laracast_url = $this->get('downloadLink');
        $request = Request::make()->getClient()->get($laracast_url, [
            'allow_redirects' => false,
        ]);
        return $request->getHeader('Location')[0];
    }
}
