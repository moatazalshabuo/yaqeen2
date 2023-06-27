<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\FacesMaterials;
use App\Models\Salesbill;
use App\Models\SalesCnc;
use App\Models\SalesItem;
use App\Models\SalesItemFace;
use App\Models\ToolMaterials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesItemController extends Controller
{

    /**
     * Remove the specified resource from storage.
     */
    public function create(Request $request)
    {
        $sales = Salesbill::find($request->sales_id);
        if ($sales->status == 1) {
            $request->validate([
                "cnc_tools" => ["required"],
                "quantity" => ["required", 'numeric', "min:0.0001", "max:9999999"],
            ], [
                "cnc-tools.required" => "يجب اختيار العملية",
                "quantity.required" => "يجب ادخال الكمية",
            ]);
            $tool = ToolMaterials::find($request->cnc_tools);
            SalesCnc::create([
                "cnc_id" => $request->cnc_tools,
                "quantity" => $request->quantity,
                "descripe" => $request->descripe,
                "sales_id" => $request->sales_id,
                "totel" => ($tool->price * $request->quantity),
                "created_by" => Auth::user()->name
            ]);
            return response(["error" => 0, "mssg" => "تم اضافة عنصر بنجاح"]);
        } else {
            return response(["error" => 1, "mssg" => "الفاتورة مغلقة"]);
        }
    }

    public function destroy($id)
    {

        $item1 = SalesItem::find($id);
        $item2 = SalesCnc::find($id);
        $sales = isset($item1->id) ? Salesbill::find($item1->sales_id) : Salesbill::find($item2->sales_id);
        if ($sales->status == 1) {
            if (isset($item1->id)) {
                $faces = SalesItemFace::where("item_id", $id)->get();

                foreach ($faces as $item) {

                    $_pord = FacesMaterials::where("face_id", $item->face_id)->get();
                    foreach ($_pord as $val) {
                        $quantity = $item->quantity * $val->quantity;
                        $material_id = $val->material_id;
                        Helper::AddQuantity($material_id, $quantity);
                    }
                }
                $sales = SalesItem::find($id)->delete();
            } else {
                $item2->delete();
            }
            return response(["error" => 0, "mssg" => "تم اضافة عنصر بنجاح"]);
        } else {
            return response(["error" => 1, "mssg" => "الفاتورة مغلقة"]);
        }
    }

    public function get_item($id)
    {
        $bill = Salesbill::find($id);
        if ($bill->type == 0) {
            $salesitem = SalesItem::select("sales_items.*", "products.name")
                ->join("products", "products.id", "=", "sales_items.prodid")
                ->where("sales_items.sales_id", $id)->get();
            Helper::Collect_salebill($id);
            $salesbill = Salesbill::find($id);
            return response()->json(["salesitem" => $salesitem, "salesbill" => $salesbill]);
        } else {
            $salesitem = SalesCnc::select("sales_cncs.*", "tool_materials.title as name")
                ->join("tool_materials", "tool_materials.id", "=", "sales_cncs.cnc_id")
                ->where("sales_cncs.sales_id", $id)->get();
            Helper::Collect_salebill($id);
            return response()->json(["salesitem" => $salesitem, "salesbill" => $bill]);
        }
    }
}
