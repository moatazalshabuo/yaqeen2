<?php

namespace App\Http\Controllers;

use App\Models\Client;

use Illuminate\Http\Request;

class CLintController extends Controller
{
    public function show_select($id = "")
    {
        $client = Client::all();
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
            "phone"=>["unique:c_lints,phone",'max:30'],
            "email"=>['max:80'],
            "address"=>['max:191']
        ],["name.required"=>"يرجى ادخال الاسم",
        "phone.unique"=>"رقم الهاتف موجود مسبقا"]);
        echo Client::create([
            "name"=>$request->name,
            "phone"=>$request->phone,
            "email"=>$request->email,
            "address"=>$request->address
        ])->id;
    }
}
