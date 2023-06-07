<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show_select($id = "")
    {
        $client = Customer::all();
        echo "<option value=''>اختر الزبون</option>";
        foreach($client as $val){
            $sele = ($id == $val->id)?"selected":"";
            echo "<option $sele value='".$val->id."'>".$val->name ." - ".$val->phone."</option>";
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'name'=>["required",'max:255'],
            "phone"=>["unique:clients,phone",'max:30'],
            "email"=>['max:80'],
            "address"=>['max:191']
        ],
        ["name.required"=>"يرجى ادخال الاسم",
        "phone.unique"=>"رقم الهاتف موجود مسبقا"]);
        echo Customer::create([
            "name"=>$request->name,
            "phone"=>$request->phone,
            "email"=>$request->email,
            "address"=>$request->address
        ])->id;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function cust_index()
    {
        # code...
        $product = Customer::all();
        
        return view("frontend/costum",['product'=>$product]);
    }
    // public function search_cust(Request $request)
    // {
    //     // $where = array();
    //     $date = array();
    //     $Residual_s=0;
    //     $sincere_s=0;
    //     $data1 = array();
    //     $data = array();
    //     # code...

    //     $request->validate([
    //         'custom'=>"required"
    //     ],['custom.required'=>"يرجى اختيار المورد"]);
    //         $client = $request->custom;

    //     if(isset($request->from)){
    //         $request->validate([
    //             'to'=>"required"
    //         ]);
    //         $date['from'] = $request->from;
    //         $date['to'] = $request->to;
    //     }
    //    //    $i=0;

       
       
    //     // print_r($dateArr);die();
    //    if(isset($date['from']) && isset($date['to'])){

    //     $prus = Exchange::select("exchange_receipt.*",'users.name')->
    //     join("purchasesbills","purchasesbills.id","=","exchange_receipt.bill_id")->
    //     join("users","users.id","=","exchange_receipt.created_by")->
    //     where("purchasesbills.custom",$client)->
    //     whereBetween('exchange_receipt.created_at',[$date])->get();

    //     $prus1 = Purchasesbill::select("purchasesbills.*","users.name")->
    //     join("users","users.id","=","purchasesbills.created_by")->
    //     where('custom',$client)->     
    //     whereBetween('purchasesbills.created_at',[$date])->get();  

    //     }else{

    //         $prus = Exchange::select("exchange_receipt.*",'users.name')->
    //         join("purchasesbills","purchasesbills.id","=","exchange_receipt.bill_id")->
    //         join("users","users.id","=","exchange_receipt.created_by")->
    //         where("purchasesbills.custom",$client)->get();

    //         $prus1 = Purchasesbill::select("purchasesbills.*","users.name")->
    //         join("users","users.id","=","purchasesbills.created_by")->
    //         where('custom',$client)->get();        
    //     }
    //     foreach($prus as $val){
    //         array_push($data1,
    //         ['id_bill'=>$val->id,
    //         'price'=>$val->price,
    //         'created_at'=>$val->created_at,
    //         "username"=>$val->name,
    //         "type"=>"ايصال صرف",
    //         "type_n"=>'1'
    //         ]
    //        );
    //        }   
    //        foreach($prus1 as $val){
    //         array_push($data1,
    //         ['id_bill'=>$val->id,
    //         'price'=>$val->tolal,
    //         'sincere'=>$val->sincere,
    //         'Residual'=>$val->Residual,
    //         'created_at'=>$val->created_at,
    //         "username"=>$val->name,
    //         "type"=>"فاتورة مشتريات",
    //         "type_n"=>'2'
    //         ]
            
    //        );
    //        $sincere_s += $val->sincere;
    //         $Residual_s += $val->Residual;
    //        }
    //     // print_r($prus.$prus1);die();
    //        $data = collect($data1)->sortBy('created_at')->reverse()->toArray();
    //     //    echo $data[0]['type_n'] == '1';
    //     //    var_dump($data);die();
        
    //     return redirect()->route('cust_index')->with(['data'=>$data,
    //         'sincere_s'=>empty($sincere_s)?'0':$sincere_s,
    //         'Residual_s'=>empty($Residual_s)?'0':$Residual_s
    //     ]);
    // }
}
