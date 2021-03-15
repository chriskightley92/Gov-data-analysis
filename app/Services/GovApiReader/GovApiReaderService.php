<?php

namespace App\Services\GovApiReader;

use App\Services\GovApiReader\core\HandlerInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GovApiReaderService
{

    /**
     * @param String $baseUrl
     * @param String $queryString
     * @param HandlerInterface $handler
     * @return array
     */
    public function search(String $baseUrl, array $params, HandlerInterface $handler ): array
    {
        $BuiltQueryString = http_build_query($params);

        return $handler->getUrlList($baseUrl.'?'.$BuiltQueryString);
    }


    /**
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
