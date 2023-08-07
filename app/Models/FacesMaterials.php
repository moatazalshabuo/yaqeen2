<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacesMaterials extends Model
{
    use HasFactory;
    protected $table = "faces_materials";
    protected $fillable = ["id","face_id","material_id","quantity"];

    public function rawmaterial(){
        return $this->belongsTo(rawmaterials::class);
    }
    public static function Material($id){
        return ProductFaces::select("rawmaterials.material_name","faces_materials.*","rawmaterials.pace_price as price")
        ->join("faces_materials","faces_materials.face_id","=","product_faces.id")->
        join("rawmaterials","rawmaterials.id","=","faces_materials.material_id")
        ->where("product_faces.product_id",$id)->get();
    }
}
