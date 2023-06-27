<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;
    protected $table = "exchange_receipt";
    protected $fillable = ["id","desc","bill_id","type","price","created_by","created_at"];
}
