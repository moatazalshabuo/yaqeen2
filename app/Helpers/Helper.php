<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use App\Models\ItemTool;
use App\Models\rawmaterials;
use App\Models\Purchasesbill;
use App\Models\Purchasesitem;
use App\Models\SalaryUser;
use App\Models\Salesbill;
use App\Models\SalesItem;
use App\Models\ToolFace;
use App\Models\WorkUser;
use Exception;
use Illuminate\Support\Facades\DB;

class Helper
{
    public static function compareDates($date1, $date2)
    {
        return strtotime($date1) - strtotime($date2);
    }

    public static function Collect_purbill($id)
    {
        $Purchasesbill = Purchasesbill::find($id);
        $mymaterial = DB::table('purchasesitems')->select(DB::raw("SUM(totel) as totalsum"))
            ->where(['purchases_id' => $Purchasesbill->id])->get();

        $Purchasesbill->totel = isset($mymaterial[0]->totalsum) ? $mymaterial[0]->totalsum : 0;
        $Purchasesbill->update();
    }

    public static function Collect_salebill($id)
    {
        $salesbill = Salesbill::find($id);
        if ($salesbill->type == 0) {
            $mymaterial = DB::table('sales_items')->select(DB::raw("SUM(totel) as totalsum"))
                ->where(['sales_id' => $salesbill->id])->get();
        } else {
            $mymaterial = DB::table('sales_cncs')->select(DB::raw("SUM(totel) as totalsum"))
                ->where(['sales_id' => $salesbill->id])->get();
        }

        $salesbill->totel = isset($mymaterial[0]->totalsum) ? $mymaterial[0]->totalsum : 0;
        $salesbill->Residual = $salesbill->totel - $salesbill->sincere;
        $salesbill->update();
    }

    public static function AddQuantity($id, $quantity, $price = null)
    {
        $raw = rawmaterials::find($id);

        $raw->quantity = ($raw->quantity + $quantity);
        if ($price != null) {
            $raw->price = $price;
            $pace_price = ($raw->material_type != 3) ? $price / ($raw->hiegth * $raw->width) : $price;
            $raw->pace_price = $pace_price;
        }
        $raw->update();
    }

    public static function CancelQuantity($id, $quantity)
    {
        $raw = rawmaterials::find($id);
        $raw->quantity = ($raw->quantity - $quantity);
        $raw->update();
    }

    public static function ToReceive($id)
    {

        $Purchasesbill = Purchasesbill::find($id);
        $PurchasesItem = Purchasesitem::where("purchases_id", $Purchasesbill->id)->get();
        foreach ($PurchasesItem as $value) {
            Helper::AddQuantity($value->rawmati, $value->quantity, $value->price);
        }
    }

    public static function CancelReceive($id)
    {

        $Purchasesbill = Purchasesbill::find($id);
        $PurchasesItem = Purchasesitem::where("purchases_id", $Purchasesbill->id)->get();

        foreach ($PurchasesItem as $value) {

            Helper::CancelQuantity($value->rawmati, $value->quantity);
        }
    }
    public static function CheckMaterial($material_id, $quantity)
    {
        $material = rawmaterials::find($material_id);

        if ($material->quantity < $quantity) {
            $pruch = Purchasesitem::select("rawmaterials.material_name", "purchasesbills.note")->join("purchasesbills", "purchasesbills.id", "=", "purchasesitems.purchases_id")->join("rawmaterials", "rawmaterials.id", "=", "purchasesitems.rawmati")->where(['purchasesitems.rawmati' => $material_id, "purchasesbills.receipt" => 0])->get();
            if (isset($pruch[0]->note))
                return $pruch[0];
            else
                return ["material_name" => $material->material_name, "note" => ""];
        }
    }
    public static function InsertToolItem($tool_id, $quantity, $salesitem)
    {
        $row = ToolFace::select("faces_materials.quantity", "tool_materials.id", "tool_materials.price")->join("tool_materials", "tool_materials.id", "=", "tool_face.tool_materials_id")->join("faces_materials", "faces_materials.material_id", "=", "tool_materials.material")->where("tool_face.id", $tool_id)->get()[0];

        $price = $row->quantity * $row->price * $quantity;

        ItemTool::create([
            'tool_materials_id' => $row->id,
            "salesitem_id" => $salesitem,
            "quantity" => $row->quantity * $quantity,
            "price" => $price
        ]);
    }

    public static function check_item($id)
    {
        $salesitem = SalesItem::find($id);
        $work = WorkUser::where("sales_id", $id)->get();
        if (isset($salesitem->id)) {
            $workin = false;
            $workdone = true;
            foreach ($work as $item) {
                if ($item->status == 1 || $item->status == 2) {
                    $workin = true;
                }
                if ($item->status != 3) {
                    $workdone = false;
                }
            }

            if ($workin) {
                $salesitem->status = 1;
            } elseif ($workdone) {
                $salesitem->status = 2;
            } else {
                $salesitem->status = 0;
            }
            $salesitem->update();
        }
    }

    public static function count_salary($id, $price)
    {
        $salary = SalaryUser::where("user_id", $id)->first();

        if (isset($salary->id)) {
            if ($salary->type_salary == 1) {
                $sal = ($salary->rate / 100) *  $price;
                $salary->totel_salary = $salary->totel_salary + $sal;
            }
            $salary->count_finish_work = $salary->count_finish_work + 1;
        }
        $salary->update();
    }
}
