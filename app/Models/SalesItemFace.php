<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesItemFace extends Model
{
    use HasFactory;
    protected $fillable = ["id","face_id","Item_id","height","width","count","quantity","price"];
    protected $table = "item_face";
}
