<?php


namespace App\Services\GovApiReader\core;

interface HandlerInterface
{

    public function getUrlList(String $searchUrl): array;

}
