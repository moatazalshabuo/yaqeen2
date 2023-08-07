<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
    use HasFactory;
    protected $fillable = ["id","prodid","count","sales_id","descripe","user_id","quantity","descont","totel","created_by"];
    protected $table = "sales_items";


}
