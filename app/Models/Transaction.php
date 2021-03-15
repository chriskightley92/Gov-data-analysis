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
}
