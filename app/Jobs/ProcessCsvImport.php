<?php

namespace App\Jobs;

use App\Models\Company;
use App\Models\CompanyDepartment;
use App\Models\Department;
use App\Models\Expense;
use App\Models\Transaction;
use App\Models\Supplier;
use App\Services\CsvParser\CsvParserService;
use App\Services\GovApiReader\GovApiReaderService as Service;
use Illuminate\Support\Facades\Log;
use App\Services\GovApiReader\GovApiReaderService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\CheckGovServiceIsUp;

class ProcessCsvImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function middleware()
    {
        return [new CheckGovServiceIsUp()];
    }


    /**
     * Execute the job.
     *
     * @param GovApiReaderService $service
     * @return void
     */
    public function handle(GovApiReaderService $service)
    {

        Log::info('Beginning Csv Import');

//        $base_url = env('BASE_GOV_DATA_SEARCH_API_URL');
//        $urlList = $service->search($base_url, [
//            'q' => 'payment-to-suppliers-2011-2012',
//
//        ], new AccountsHandler());

        $urlList = [
            'https://data.yorkopendata.org/dataset/27bc1dc6-d62f-4b93-a326-13989f5bfb56/resource/58bd7c54-e93b-4c74-b4b0-3613229d8be7/download/over500payments2011.csv'
        ];

        foreach ($urlList as $url){

            Log::info('Reading CSV data from: '.$url);

            $service->import($url, ['save_to' => storage_path()]);
            $records = CsvParserService::parseToArray(Storage::path('tmp.csv'));

            DB::beginTransaction();

            try{
                foreach ($records as $key => $record) {

                    Log::info('Beginning sync of record: '.json_encode($record));

                    $company = Company::firstOrCreate([
                        'name' => $record['BodyName']
                    ]);

                    $department = Department::firstOrCreate([
                        'name' => $record['OrganisationUnit']
                    ]);

                    $expense = Expense::firstOrCreate([
                        'expenditure_category' => $record['ExpenseCategory'],
                        'expenditure_code' => $record['ExpenditureCode']
                    ]);

                    $supplier = Supplier::firstOrCreate([
                        'name' => $record['SupplierName'],
                    ]);

                    $companyDepartment = CompanyDepartment::firstOrCreate([
                        'department_id' => $department->id,
                        'company_id' => $company->id
                    ]);

                    Transaction::firstOrCreate([
                        'company_department_id' => $companyDepartment->id,
                        'expense_id' => $expense->id,
                        'supplier_id' => $supplier->id,
                        'transaction_date' => date('Y-m-d H:i:s',strtotime($record['Date'])),
                        'transaction_number' => $record['TransactionNumber'],
                        'transaction_amount' => $record['Amount']
                    ]);

                    DB::commit();
                }

            }catch (\Exception $e){
                DB::rollBack();
                Log::error('Import failed on line: '.$key.' Caused by - '.$e->getMessage());
                die();
            }

            Log::info('Csv Import Complete');
        }
    }
}
