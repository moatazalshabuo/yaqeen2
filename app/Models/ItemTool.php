<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTool extends Model
{
    use HasFactory;
    protected $fillable = ["id","tool_materials_id","price","quantity","salesitem_id"];
}
