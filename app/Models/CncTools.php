<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CncTools extends Model
{
    use HasFactory;
    protected $fillable = ["name","id","status","created_by"];
}
