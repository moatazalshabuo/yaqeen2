<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pay_recipt extends Model
{
    use HasFactory;

    protected $table = "pay_receipt";
    protected $fillable = ['id','bill_id',"client_id","price","created_by","created_at"];
}
