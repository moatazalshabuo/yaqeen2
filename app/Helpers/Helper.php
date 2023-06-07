<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use App\Models\ItemTool;
use App\Models\rawmaterials;
use App\Models\Purchasesbill;
use App\Models\Purchasesitem;
use App\Models\ToolFace;
use Exception;
use Illuminate\Support\Facades\DB;

class Helper
{
    public static function compareDates($date1, $date2){
        return strtotime($date1) - strtotime($date2);
    }

    public static function Collect_purbill($id){
        $Purchasesbill = Purchasesbill::find($id);
        $mymaterial = DB::table('purchasesitems')->select(DB::raw("SUM(totel) as totalsum"))
        ->where(['purchases_id'=> $Purchasesbill->id])->get();

            $Purchasesbill->totel = isset($mymaterial[0]->totalsum)?$mymaterial[0]->totalsum:0;
        $Purchasesbill->update();
    }

    public static function AddQuantity($id,$quantity,$price = null){
        $raw = rawmaterials::find($id);
        $raw->quantity = ($raw->quantity + $quantity);
        if($price != null)
        $raw->price = $price;
        $raw->update();
    }

    public static function CancelQuantity($id,$quantity){
        $raw = rawmaterials::find($id);
        $raw->quantity = ($raw->quantity - $quantity);
        $raw->update();
    }

    public static function ToReceive($id){

        $Purchasesbill = Purchasesbill::find($id);
        $PurchasesItem = Purchasesitem::where("purchases_id",$Purchasesbill->id)->get();
        foreach($PurchasesItem as $value){
            Helper::AddQuantity($value->rawmati,$value->quantity,$value->price);
        }

    }

    public static function CancelReceive($id){

        $Purchasesbill = Purchasesbill::find($id);
        $PurchasesItem = Purchasesitem::where("purchases_id",$Purchasesbill->id)->get();

        foreach($PurchasesItem as $value){

            Helper::CancelQuantity($value->rawmati,$value->quantity);
        }

    }
    public static function CheckMaterial($material_id,$quantity){
        $material = rawmaterials::find($material_id);

         if($material->quantity < $quantity){
            $pruch = Purchasesitem::select("rawmaterials.material_name","purchasesbills.note")->
            join("purchasesbills","purchasesbills.id","=","purchasesitems.purchases_id")->
            join("rawmaterials","rawmaterials.id","=","purchasesitems.rawmati")->
            where(['purchasesitems.rawmati'=>$material_id,"purchasesbills.receipt"=>0])->get();
            if(isset($pruch[0]->note))
            return $pruch[0];
            else
            return ["material_name"=>$material->material_name,"note"=>""];
         }
    }
    public static function InsertToolItem($tool_id,$quantity,$salesitem){
        $row = ToolFace::select("faces_materials.quantity","tool_materials.id","tool_materials.price")->
        join("tool_materials","tool_materials.id","=","tool_face.tool_materials_id")->
        join("faces_materials","faces_materials.material_id","=","tool_materials.material")->
        where("tool_face.id",$tool_id)->get()[0];

        $price = $row->quantity * $row->price * $quantity;

        ItemTool::create([
            'tool_materials_id'=>$row->id,
            "salesitem_id"=>$salesitem,
            "quantity"=>$row->quantity * $quantity,
            "price"=>$price
        ]);
    }
}
