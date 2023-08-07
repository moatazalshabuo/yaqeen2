<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rawmaterials extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'material_name',
        'material_type',
        'quantity',
        'price',
        "pace_price",
        "width",
        "hiegth",
        'created_by',
    ];

    public function quantity(){
        return $this->quantity * $this->hiegth * $this->width;
    }

    // public function section()
    // {
    //     return $this->belongsTo('App\Models\sections');
    // }

}
