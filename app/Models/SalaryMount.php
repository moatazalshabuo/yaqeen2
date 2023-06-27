<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryMount extends Model
{
    use HasFactory;
    protected $fillable = [
        "mount",
        "user_name",
        "salary",
        "plus",
        "salary_users",
        "dept_on",
        "still"
    ];
    protected $table = "salary_mounts";
}
