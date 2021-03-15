<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'expense_id',
        'company_department_id',
        'transaction_number',
        'transaction_date',
        'transaction_amount',
    ];

    public $timestamps = true;

    public function companyDepartment()
    {
        return $this->hasOne(CompanyDepartment::class);
    }


    public function expense()
    {
        return $this->hasOne(Expense::class);
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class);
    }

}
