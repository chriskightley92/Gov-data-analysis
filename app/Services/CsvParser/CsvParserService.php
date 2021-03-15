<?php

namespace App\Services\CsvParser;

use Illuminate\Support\Facades\Log;
use League\Csv\Reader;
use League\Csv\Statement;

class CsvParserService
{

   public static function parseToArray(String $file_name)
   {
       try{
           $csv = Reader::createFromPath($file_name, 'r');
       }catch (\Exception $e){
           Log::error('');
           die();
       }

       $csv->setHeaderOffset(0); //set the CSV header offset

       return Statement::create()->process($csv);
   }
}
