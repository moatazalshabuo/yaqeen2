<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Customer;
use App\Models\Purchasesbill;
use App\Models\rawmaterials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchasesbillController extends Controller
{
    public function index($id = "")
    {

        if (Auth::user()->user_type != 1) {

            $wher = (!empty($id) && !empty(Purchasesbill::find($id))) ? ["created_by" => Auth::user()->name, "purchasesbills.id" => $id] : ["created_by" => Auth::id()];
            $pages = Purchasesbill::where("created_by", Auth::id())->get();
        } else {

            $wher = (!empty($id) && !empty(Purchasesbill::find($id))) ? ["purchasesbills.id" => $id] : [];
            $pages = Purchasesbill::all();
        }
        $last_bill = Purchasesbill::select("users.name", "purchasesbills.*")
            ->join("users", "users.id", "=", "purchasesbills.created_by")->where($wher)->orderby("id", "DESC")->first();
        $rawmate = rawmaterials::all();
        if (!empty($last_bill)) {
            // البحث عن الصفحة التالية والسابقة بناء على المستخدم
            $index = array();

            foreach ($pages as $val) {
                array_push($index, $val['id']);
            }
            // احضار انديكس الصفحة الحالة
            $current = array_search($last_bill->id, $index);

            $next = isset($index[$current + 1]) ? $index[$current + 1] : "";
            $prev = isset($index[$current - 1]) ? $index[$current - 1] : "";
        } else {
            $next = "";
            $prev = "";
        }

        return view("purchases/purchases", ['data' => $last_bill, "mate" => $rawmate, 'next' => $next, "prev" => $prev]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $id = Purchasesbill::create([
            'created_by' => Auth::id(),
            "status" => 1,
            "custom" => null
        ])->id;
        return redirect()->route("Purchasesbill", $id);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchasesbill $salesbill)
    {
        //
        if ($salesbill->sincere > 0) {
            return redirect()->back()->with("err", "يوجد ايصال صرف للفاتورة يرجى الغاء كل الايصالات الفاتورة لتتمكن من التعديل");
        } else {
            $salesbill->status = 1;
            $salesbill->update();
            return redirect()->route("Purchasesbill", $salesbill->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function save(Request $request)
    {

        $request->validate([
            "client" => "required"
        ], [
            "client.required" => "يرجى اختيار المورد",
        ]);

        Helper::Collect_purbill($request->id);
        $salesbill = Purchasesbill::find($request->id);
        if ($salesbill->status == 1) {
            if ($salesbill->totel > 0) {
                $salesbill->status = 0;
                $salesbill->Residual = $salesbill->totel;
                $salesbill->custom = $request->client;
                $salesbill->update();
                $data = array("id" => $salesbill->id);
            } else {
                $data = array("mass" => "لايمكنك اغلاق فاتورة فارغة ");
            }
        } else {
            $data = array("mass" => "الفاتورة مغلقة بالفعل");
        }
        echo json_encode($data);
    }


    public function rawmaterials_select()
    {
        $product = rawmaterials::select("*")->get();
        echo "<option >اختر الصنف</option>";
        foreach ($product as $item) :
            echo "<option value=$item->id>$item->material_name	</option>";
        endforeach;
    }
    public function select_prod($id)
    {
        $product = rawmaterials::select("price")->where('id', $id)->get();
        $data = array("price" => $product[0]->price);

        echo json_encode($data);
    }


    public function get_bill_data($id = 0)
    {
        $salesbill = Purchasesbill::find($id);
        if (isset($salesbill->id)) {
            if ($salesbill->status == 0) {
                $client = Customer::find($salesbill->custom);
                $data = array(
                    "totel" => $salesbill->totel,
                    "sincere" => $salesbill->sincere,
                    "Residual" => $salesbill->Residual,
                    "custom_name" => $client->name . "" . $client->phone,
                    // "client_id"=>$client->id,
                    "bill_no" => $salesbill->id
                );
            } else {
                $data = array("error" => "<p class='alert alert-danger'>الفاتورة غير مغلقة</p>");
            }
        } else {
            $data = array("error" => "<p class='alert alert-danger'>الفاتورة غير موجودة</p>");
        }
        echo json_encode($data);
    }

    public function ToReceive($id)
    {
        $bill = Purchasesbill::find($id);
        if ($bill->Residual ==  0) {
            $bill->update([
                "receipt" => 1
            ]);
            Helper::ToReceive($id);
            return response()->json(["stat" => 1], 200);
        } else {
            return response()->json(["stat" => 0], 200);
        }
    }


    public function CancelReceive($id)
    {

        $bill = Purchasesbill::find($id);
        if ($bill->Residual ==  0) {
            $bill->update([
                "receipt" => 0
            ]);
            Helper::CancelReceive($id);
            return response()->json(["stat" => 1], 200);
        } else {
            return response()->json(["stat" => 0], 200);
        }
    }


}
