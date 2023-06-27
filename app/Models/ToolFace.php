<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolFace extends Model
{
    use HasFactory;
    protected $table = "tool_face";
    protected $fillable = ["id","tool_materials_id","product_faces_id"];

    public static function Tools($id){
        return ToolFace::select("tool_materials.title","tool_materials.price","tool_face.*")
        ->join("product_faces","tool_face.product_faces_id","=","product_faces.id")->
        join("tool_materials","tool_materials.id","=","tool_face.tool_materials_id")
        ->where(["product_faces.product_id"=>$id])->get();
    }
}
