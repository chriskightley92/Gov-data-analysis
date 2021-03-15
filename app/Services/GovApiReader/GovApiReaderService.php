<?php

namespace App\Services\GovApiReader;

use App\Services\GovApiReader\core\HandlerInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GovApiReaderService
{

    /**
     * Was used to grab packages compiled monthly by querying packagesearch within gov API. Gov endpoints search engine
     * made it almost impossible to target correct files. See lines 44-48 on how to use.
     *
     * @param String $baseUrl
     * @param array $params
     * @param HandlerInterface $handler
     * @return array
     */
    public function search(String $baseUrl, array $params, HandlerInterface $handler ): array
    {
        $BuiltQueryString = http_build_query($params);

        return $handler->getUrlList($baseUrl.'?'.$BuiltQueryString);
    }


    /**
     * Additional withOptions save guzzle directive allows local download of file
     *
     * @param String $url
     * @param array $options
     */
    public function import(String $url, array $options = []): void
    {
        try {
            Http::timeout(3)->withOptions($options)->get($url);
        }catch (\Exception $e){
            Log::error('Unable to import file from url: '.$url.' - '.$e->getMessage());
            die();
        }
    }

    /**
     * Used by middleware to check service availability. Url is appended with a search term with a unique id to
     * return as few results as possible to speed up check.
     *
     * @param String $url
     * @return bool
     */
    public static function isPipelineUp(String $url): bool
    {
        try{
            $response = Http::timeout(3)->get($url.'?q='.uniqid());
        }catch (\Exception $e){
            return false;
        }

        return !empty($response);
    }
}
