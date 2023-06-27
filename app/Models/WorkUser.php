<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkUser extends Model
{
    use HasFactory;
    protected $fillable = ["id","sales_id","user_id","order","status","message"];
    protected $table = "work_users";
}
