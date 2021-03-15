<?php

namespace App\Services\CsvParser;

use Illuminate\Support\Facades\Log;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\TabularDataReader;
use League\Csv\Exception;

class CsvParserService
{

    /**
     * @param String $file_name
     * @return TabularDataReader
     * @throws \League\Csv\Exception
     */
    public static function parseToArray(String $file_name): TabularDataReader
   {
       try{
           $csv = Reader::createFromPath($file_name, 'r')->setHeaderOffset(0);

       }catch (Exception $e){

           Log::error('Unable to read CSV file from source: '.$file_name);
           die();
       }

       return Statement::create()->process($csv);
   }
}
