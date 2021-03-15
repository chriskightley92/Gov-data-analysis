<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDepartment extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'department_id'];

    public $timestamps = true;


    public function company()
    {
        return $this->hasOne(Company::class);
    }


    public function department()
    {
        return $this->hasOne(Department::class);
    }
}
