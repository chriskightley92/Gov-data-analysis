<?php

namespace App\Services\GovApiReader\src;

use App\Services\GovApiReader\core\HandlerInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AccountsHandler implements HandlerInterface
{

    /**
     * If route to result list isn't found, die here with error.
     * If Url and format params are missing / incorrect format, continue loop and not die
     *
     * @param String $queryString
     * @return array
     */
    public function getUrlList(String $queryString): array
    {
        $urlList = [];

        try{
            $response = Http::timeout(3)->get($queryString)->body();
            $decodedResponse = json_decode($response, true);

            $resultList = $decodedResponse['result']['results'][1];

            foreach ($resultList['resources'] as $result){

                if(array_key_exists('url', $result) &&
                    array_key_exists('format', $result) &&
                    $result['format'] == 'CSV'){

                    $urlList[] = $result['url'];
                }
            }

        }catch (\Exception $e){
            Log::error('Unable to process URL -'. $e->getMessage());
            die();
        }

        return $urlList;
    }
}
