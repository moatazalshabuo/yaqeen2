<?php

namespace App\Http\Controllers;

use App\Models\pay_recipt;
use App\Models\Salesbill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PayController extends Controller
{

    function pay(Request $request)
    {
        $data = array();
        $request->validate([
            "client" => "required",
            "price" => "required|numeric|min:1|max:999999"
        ], [
            "client.required" => "يجب اختيار زبون",
            "price.required" => "يرجى ادخال القيمة"
        ]);

        $totls = Salesbill::select(DB::raw("SUM(Residual) as Residualsum"))
            ->where("client", $request->client)->get();
        if (isset($totls[0]) && $totls[0]->Residualsum >= $request->price) {
            $price = $request->price;
            $bills = Salesbill::select("id")->where(["client" => $request->client, "status" => '0'])->where("Residual", ">", "0")->orderBy("id", "DESC")->get();
            foreach ($bills as $val) {
                $sal = Salesbill::find($val->id);
                if ($price > 0) {
                    if ($price <= $sal->Residual) {
                        $sal->Residual = $sal->Residual - $price;
                        $sal->sincere = $sal->sincere + $price;
                        $sal->update();
                        $price = 0;
                    } else {
                        $price = $price - $sal->Residual;
                        $sal->sincere = $sal->sincere + $sal->Residual;
                        $sal->Residual = 0;
                        $sal->update();
                    }
                }
            }
            $data['id'] = pay_recipt::create([
                "client_id" => $request->client,
                "price" => $request->price,
                "created_by" => Auth::id()
            ])->id;
            $data['done'] = "تم تسجيل العملية بنجاح ";
        } else {
            $data['error'] = "القمية المدخلة اكبر من القيمة المتبقي";
        }

        echo json_encode($data);
    }


    // ايصالات القبض

    public function client_pay($id = "")
    {
        if ($id != "") {
            $salesbill = Salesbill::select("id")->where(["client" => $id, "status" => '0'])->get();
            $html = "<option value=''>اختر الفاتورة </option>";
            foreach ($salesbill as $val) {
                $html .= "<option value=" . $val['id'] . ">" . $val['id'] . "</option>";
            }
            $totls = Salesbill::select(DB::raw("SUM(totel) as totalsum"), DB::raw("SUM(sincere) as sinceresum"), DB::raw("SUM(Residual) as Residualsum"))->where("client", $id)->get();
            $data = array("salesbill" => $html, "sincere" => $totls[0]->sinceresum, "Residual" => $totls[0]->Residualsum, "total" => $totls[0]->totalsum);
            echo json_encode($data);
        } else {
            echo "";
        }
    }
}
