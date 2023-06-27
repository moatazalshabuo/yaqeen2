<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salesbill extends Model
{
    use HasFactory;
    protected $fillable = ["created_by","id","totel","sincere","Residual","type","status"];
}
