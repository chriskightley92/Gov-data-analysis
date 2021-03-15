<?php

namespace App\Console\Commands;

use App\Jobs\ProcessCsvImport;
use App\Services\GovApiReader\GovApiReaderService;
use Illuminate\Console\Command;

class AccountsImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'run the main accounts import job';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ProcessCsvImport::dispatch();
    }
}
