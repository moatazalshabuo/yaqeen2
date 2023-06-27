<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Purchasesbill;
use App\Models\Purchasesitem;
use App\Models\rawmaterials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchasesitemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $Purchasebill = Purchasesbill::find($request->id);

        if ($Purchasebill->status == 1) :
            $rules = [
                "material" => "required|numeric",
                "price" => "required|numeric|min:0.00001|max:9999999",
                'quantity' => "required|integer|min:1|max:9999999"
            ];
            $message = [
                "product.required" => "يجب ادخال الصنف",
                "price.required" => "يجب ادخال السعر"
            ];
            $request->validate($rules, $message);

            $quantity = $request->quantity;

            if ($Purchasebill->receipt == 1)
                Helper::AddQuantity($request->material, $quantity, $request->price);

            $id = Purchasesitem::create([
                "purchases_id" => $Purchasebill->id,
                'rawmati' => $request->material,
                "quantity" => $quantity,
                "totel" => ceil($quantity * $request->price),
                "price"=>$request->price,
                "descont" => 0,
                "user_id" => Auth::id()
            ])->id;
            Helper::Collect_purbill($Purchasebill->id);
            echo 1;
        else :
            echo "الفاتورة مغلقة";
        endif;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function edit_item($id)
    {
        $Purchaseitem = Purchasesitem::find($id);
        $Purchasebill = Purchasesbill::find($Purchaseitem->purchases_id);
        if ($Purchasebill->status == 1) {
            $data = array(
                "type" => 1,
                "mate" => $Purchaseitem->rawmati,
                "price" => $Purchaseitem->price,
                "quantity" => $Purchaseitem->quantity,
                "total" => $Purchaseitem->totel + $Purchaseitem->descont,
            );
            if ($Purchasebill->receipt == 1)
                Helper::CancelQuantity($Purchaseitem->rawmati, $Purchaseitem->quantity);

            $Purchaseitem->delete();
            Helper::Collect_purbill($Purchasebill->id);
        } else {
            $data = array("type" => 2, "massege" => "الفتورة مغلقة");
        }
        echo json_encode($data);
    }

    public function getItemTotal($id)
    {
        //
        $bill = Purchasesbill::find($id);
        $data = Purchasesitem::join("rawmaterials", "rawmaterials.id", "=", "purchasesitems.rawmati")->select("rawmaterials.material_name", "purchasesitems.*")->where("purchasesitems.purchases_id", $id)->get();
        $total = array("totel" => $bill->totel, "sincere" => $bill->sincere, "Residual" => $bill->Residual, "tbody" => "");
        foreach ($data as $val) {
            $total['tbody'] .= "<tr >
            <td>" . $val->id . "</td>
            <td>" . $val->material_name . "</td>
            <td>" . floatval($val->quantity) . "</td>
            <td>" . floatval($val->descont) . "</td>
            <td>" . floatval($val->totel) . "</td>
            <td>" . $val->created_at . "</td>
            <td class='d-flex justify-content-end'>
                    <button class='btn btn-info ml-1 btn-icon dele' id='" . $val->id . "'><span class='spinner-border spinner-border-sm sp' style='display: none'></span><span  class='text'><i class='mdi mdi-delete'></i></span></button>
                    <button class='btn btn-danger btn-icon edit-item' id='" . $val->id . "'><span class='spinner-border spinner-border-sm sp' style='display: none'></span><span  class='text'><i class='mdi mdi-transcribe'></i></button>
                </td>
            </tr>";
        }
        echo json_encode($total);
    }

    public function destroy($id)
    {
        $Purchaseitem = Purchasesitem::find($id);
        $Purchasebill = Purchasesbill::find($Purchaseitem->purchases_id);
        if ($Purchasebill->status == 1) {
            if ($Purchasebill->receipt == 1)
                Helper::CancelQuantity($Purchaseitem->rawmati, $Purchaseitem->quantity);
            $Purchaseitem->delete();
            Helper::Collect_purbill($Purchasebill->id);
            echo 1;
        } else {
            echo "الفاتورة مغلقة";
        }
    }
}
