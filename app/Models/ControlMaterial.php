<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlMaterial extends Model
{
    use HasFactory;

    protected $fillable = ["raw_id","quantity","type","created_by"];

    public function raw()
    {
        return $this->belongsTo(rawmaterials::class,'raw_id');
    }
}
