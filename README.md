# Gov-data-analysis

##How it works?
Laravel scheduler calls a job every hour first checking to see if an existing job isn't currently running

Scheduler then runs command account:import which in turn dispatches the ProcessCsvImport Job.

ProcessCsvImport Job first checks via middleware that the service is reachable.

Job then contacts the file host to download a local copy of the CSV file. File is then parsed by 
League\Csv library and compiled into multidimensional array on a record per line basis.

The returned array is looped through and line items are inserted via base table relations if they
don't exist. Model id's are then passed up the chain to parent tables. If any records fail to insert during the
transaction, all data is rolled back. 

##installation

Use following command to clone repository locally into personal dev environment.

`Git clone https://github.com/chriskightley92/Gov-data-analysis.git` 


Setup Cron job to call laravel scheduler every minute by appending the below to crontab

`* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`

Install composer dependencies locally

`composer install`

Create env replica from .env.example or fill in local database config including the following vars

`BASE_GOV_DATA_SEARCH_API_URL="https://data.gov.uk/api/action/package_search"`

Run database migrations

`php artisan migrate`

##Monitoring

Navigate to root directory and follow the log for a live log feed

`cd {base_laravel_install}/storage/logs`

`tail -f laravel.log`


