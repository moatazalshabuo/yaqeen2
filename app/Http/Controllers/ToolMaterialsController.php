<?php

namespace App\Http\Controllers;

use App\Models\CncTools;
use App\Models\rawmaterials;
use App\Models\ToolFace;
use App\Models\ToolMaterials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolMaterialsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tools = CncTools::all();
        $material = rawmaterials::all();
        $toolMaterials = ToolMaterials::select("rawmaterials.material_name","cnc_tools.name","tool_materials.*")->
        join("rawmaterials","rawmaterials.id","=","tool_materials.material")->
        join("cnc_tools","cnc_tools.id","=","tool_materials.tool")->get();
        return view("ToolMaterial/tool_material",compact("tools","material","toolMaterials"));
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
        $request->validate(
            [
                "title"=>["required","string","max:30"],
                "material"=>['required'],
                "tool"=>["required"],
                "price"=>["required","numeric","min:0.00001","max:9999999"],
            ],[
                "title.required"=>"يجب ادخال الاسم ",
                "title.max"=>"وصلت للحد الاقصى من عدد الحروف",
                "material.required"=>'يرجى اختيار المادة',
                "tool.required"=>"يرجى اختيار الاداء",
                "price.required"=>"يرجى اضافة السعر",

            ]
            );

            $type = ($request->type)?$request->type:0;
            ToolMaterials::create([
                "title"=>$request->title,
                "material"=>$request->material,
                "tool"=>$request->tool,
                "price"=>$request->price,
                "type"=>$type,
                "created_by"=>Auth::user()->name
            ]);
            return redirect()->back()->with("Add","تم الاضافة بنجاح");
    }

    /**
     * Display the specified resource.
     */
    public function show($toolMaterials)
    {
        return response()->json(ToolMaterials::find($toolMaterials));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $request->validate(
            [
                "title"=>["required","string","max:30"],
                "material"=>['required'],
                "tool"=>["required"],
                "price"=>["required","numeric","min:0.00001","max:9999999"],
            ],[
                "title.required"=>"يجب ادخال الاسم ",
                "title.max"=>"وصلت للحد الاقصى من عدد الحروف",
                "material.required"=>'يرجى اختيار المادة',
                "tool.required"=>"يرجى اختيار الاداء",
                "price.required"=>"يرجى اضافة السعر",

            ]
            );

            ToolMaterials::find($request->id)->update([
                "title"=>$request->title,
                "material"=>$request->material,
                "tool"=>$request->tool,
                "price"=>$request->price,
                "type"=>$request->type,
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($toolMaterials)
    {
        ToolMaterials::find($toolMaterials)->delete();
        return redirect()->back()->with("delete","تم الحذف بنجاح");

    }

    public function active($id){
        ToolMaterials::find($id)->update([
            "status"=>1,
        ]);
        return redirect()->back()->with("edit","تم التفعيل بنجاح");
    }
    public function unactive($id){
        ToolMaterials::find($id)->update([
            "status"=>0,
        ]);
        return redirect()->back()->with("edit","تم الغاء التفعيل بنجاح");
    }
    public function get_data($id){
        return ToolMaterials::find($id);
    }
}
