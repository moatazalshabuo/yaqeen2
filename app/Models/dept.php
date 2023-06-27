<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dept extends Model
{
    use HasFactory;

    protected $fillable = ['id',"price","created_at","user_id"];

    protected $table = "depts";

}
