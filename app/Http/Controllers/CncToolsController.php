<?php

namespace App\Http\Controllers;

use App\Models\CncTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CncToolsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tools = CncTools::all();
        return view("Cnc/cnc_tools",compact("tools"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate(
            [
                "name"=>["required","string","max:30","unique:cnc_tools,name"]
            ],[
                "name.required"=>"يجب ادخال الاسم ",
                "name.unique"=>"لا يجب تكرار اسم الاداء"
            ]
            );
            CncTools::create([
                "name"=>$request->name,
                "created_by"=>Auth::user()->name
            ]);
            return redirect()->back()->with("Add","تم الاضافة بنجاح");
    }

    /**
     * Display the specified resource.
     */
    public function show($cncTools)
    {
        return response()->json(CncTools::find($cncTools));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $request->validate(
            [
                "name"=>["required","string","max:30"]
            ],[
                "name.required"=>"يجب ادخال الاسم "
            ]
            );
            CncTools::find($request->id)->update([
                'name'=>$request->name
            ]);
    }
    public function active($id){
        CncTools::find($id)->update([
            "status"=>1,
        ]);
        return redirect()->back()->with("edit","تم التفعيل بنجاح");
    }
    public function unactive($id){
        CncTools::find($id)->update([
            "status"=>0,
        ]);
        return redirect()->back()->with("edit","تم الغاء التفعيل بنجاح");
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CncTools $cncTools)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cncTools)
    {
       CncTools::find($cncTools)->delete();
       return redirect()->back()->with("delete","تم الحذف بنجاح");
    }
}
