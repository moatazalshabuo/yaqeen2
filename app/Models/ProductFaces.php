<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductFaces extends Model
{
    use HasFactory;
    protected $fillable = ["id","title","product_id","price",'ratio'];
    protected $table = "product_faces";

    public static function Material($id){
        return ProductFaces::select("rawmaterials.material_name","faces_materials.*","product_faces.title")
        ->join("faces_materials","faces_materials.face_id","=","product_faces.id")->
        join("rawmaterials","rawmaterials.id","=","faces_materials.material_id")
        ->where("product_faces.product_id",$id)->get();
    }

    public static function get_with_cost($id){
        return ProductFaces::select("product_faces.id","product_faces.title","product_faces.price",'product_faces.ratio',
        DB::raw("sum((rawmaterials.pace_price * faces_materials.quantity) ) as coust_material"),
        DB::raw("sum(tool_materials.price) as coust_tool"))
        ->leftJoin("faces_materials","faces_materials.face_id","=","product_faces.id")->
        leftJoin("rawmaterials","rawmaterials.id","=","faces_materials.material_id")
        ->leftjoin("tool_face","tool_face.product_faces_id","=","product_faces.id")->
        leftjoin("tool_materials","tool_materials.id","=","tool_face.tool_materials_id")
        ->where("product_faces.product_id",$id)
        ->groupBy("product_faces.id","product_faces.title","product_faces.price");
    }
}
