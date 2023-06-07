<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolMaterials extends Model
{
    use HasFactory;
    protected $fillable = ['id',"title","material","tool","status","type","price","created_by"];
}
