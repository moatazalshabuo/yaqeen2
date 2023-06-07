<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
    use HasFactory;
    protected $fillable = ["id","prodid","sales_id","descripe","quantity","descont","totel","created_by"];
    protected $table = "sales_items";
}
