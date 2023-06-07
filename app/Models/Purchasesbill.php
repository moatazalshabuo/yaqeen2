<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchasesbill extends Model
{
    use HasFactory;

    protected $fillable = ["id","created_by","totel","sincere","custom","Residual","status","note","receipt","created_at"];
    protected $table = "purchasesbills";

}
