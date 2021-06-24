<?php


namespace Laracast\Pages;


use Laracast\Cores\Parser\Collections\AuthCollection;
use Laracast\LaracastConfig;
use voku\helper\HtmlDomParser;

abstract class AbstractPages
{
    protected array $rawData;

    protected function getDataPage(string $html)
    {
        $extract = HtmlDomParser::str_get_html($html);
        $json    = html_entity_decode($extract->find('div[id=app]', 0)->getAttribute('data-page'));
        $output  = json_decode($json, 1);

        $this->setRawData($output);
        //LaracastConfig::setAuth($this->getRawData()['props']['auth']);

        return $output;
    }

    /**
     * @return array
     */
    protected function getRawData(): array
    {
        return $this->rawData;
    }

    /**
     * @param array $rawData
     */
    protected function setRawData(array $rawData): void
    {
        $this->rawData = $rawData;
    }

    function auth(){
        return new AuthCollection($this->getRawData()['props']['auth']);
    }
}
