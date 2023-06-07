<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesItemFaceMaterial extends Model
{
    use HasFactory;
    protected $fillable = ['id',"material_id","item_face_id"];
    protected $table = "item_facea_material";
}
