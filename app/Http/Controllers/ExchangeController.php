<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Purchasesbill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExchangeController extends Controller
{

    function pay(Request $request){
        $data = array();
        $request->validate([
            "bill_id"=>"required",
            "price"=>"required|max:999999|regex:/^(([0-9]*)(\.([0-9]+))?)$/|min:1"
        ],[
            "bill_id.required"=>"لايمكن ترك رقم الفاتورة فارغ",
            "price.required"=>"يرجى ادخال القيمة"
        ]);

        $salesbill = Purchasesbill::find($request->bill_id);
        // print_r($salesbill);die();
        if($request->price <= $salesbill->Residual){
            $Residual = $salesbill->Residual;
            $salesbill->Residual = $Residual - $request->price;
            $salesbill->sincere = $salesbill->sincere + $request->price;
            $salesbill->update();
            $data['id']= Exchange::create([
                "bill_id" => $request->bill_id,
                "price"=>$request->price,
                "created_by"=>Auth::id(),
                "type"=>0
            ])->id;
            $data['done'] = "تم تسجيل العملية بنجاح ";
        }else{
            $data['error'] = "القمية المدخلة اكبر من القيمة المتبقي";
        }
        echo json_encode($data);
    }

    function pay1(Request $request){
        $data = array();
        $request->validate([
            "descripe"=>"required|max:191|string",
            "price"=>"required|numeric|max:9999999|min:1"
        ],[
            "descripe.required"=>"لايمكن ترك رقم الفاتورة فارغ",
            "price.required"=>"يرجى ادخال القيمة"
        ]);
            $data['id']= Exchange::create([
                "desc" => $request->descripe,
                "price"=>$request->price,
                "created_by"=>Auth::id(),
                "type"=>1
            ])->id;
            $data['done'] = "تم تسجيل العملية بنجاح ";

        echo json_encode($data);
    }
}
