<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryUser extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "type_salary",
        "rate",
        "salary",
        "totel_salary",
        "dept",
        "count_finish_work"
    ];
    protected $table = "salary_users";


}
