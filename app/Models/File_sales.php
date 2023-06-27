<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File_sales extends Model
{
    use HasFactory;

    protected $table = 'file_sales';
    protected $fillable = ['id',"file_name","sales_id"];
}
