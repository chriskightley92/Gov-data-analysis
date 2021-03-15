<?php

namespace App\Http\Middleware;

use App\Services\GovApiReader\GovApiReaderService as Service;
use Closure;
use Illuminate\Support\Facades\Log;


class CheckGovServiceIsUp
{
    /**
     * Handle an incoming request.
     *
     * @param $command
     * @param \Closure $next
     * @return mixed
     */
    public function handle($command, Closure $next)
    {
        if(!Service::isPipelineUp(env('BASE_GOV_DATA_SEARCH_API_URL'))){

            Log::error('Service Not Available, please try again later');
            return false;
        }

        return $next($command);
    }
}
