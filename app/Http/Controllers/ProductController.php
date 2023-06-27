<?php

namespace App\Http\Controllers;

use App\Models\FacesMaterials;
use App\Models\Product;
use App\Models\ProductFaces;
use App\Models\rawmaterials;
use App\Models\SalesItem;
use App\Models\ToolFace;
use App\Models\ToolMaterials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("products.products");
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
        $request->validate([
            "name" => ["required", "max:30", "unique:products,name"],
            "type" => ["required"],
        ], [
            "name.required" => "يجب ادخال اسم المنتج",
            "name.max" => "اسم المنتج لايجب ان يتعدى 30 حرف",
            "name.unique" => "المنتج مضاف مسبقا",
            "type" => "يرجى اختيار نوع البيانات",
        ]);
        $id = Product::create([
            "name" => $request->name,
            "type" => $request->type,
            "created_by" => Auth::user()->name
        ])->id;
        return response()->json($id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => ["required", "max:30", "unique:products,name," . $id],
            "type" => ["required"],
        ], [
            "name.required" => "يجب ادخال اسم المنتج",
            "name.max" => "اسم المنتج لايجب ان يتعدى 30 حرف",
            "name.unique" => "المنتج مضاف مسبقا",
            "type" => "يرجى اختيار نوع البيانات",
        ]);
        $product = Product::find($id);

        $product->update([
            "name" => $request->name,
            "type" => $request->type
        ]);
        return response()->json(1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (SalesItem::where('prodid', $id)->count() == 0) {
            Product::find($id)->delete();
            session()->flash('Add', 'تم الحذف المنتج بنجاح');
        } else {
            session()->flash('Add', 'لا يمكن حذف ماده موجوده في فاتورة مبيعات');
        }
        return redirect("/products");
    }

    // ajax side

    public function GetProduct($id = null)
    {
        $data = ($id == null) ? Product::all() : Product::find($id);
        return response()->json($data);
    }

    public function StoreFace(Request $request)
    {
        $request->validate([
            "title" => ["required", "string", "max:30"],
        ], [
            "title.required" => "يرجى ادخال عنوان للوجه"
        ]);

        ProductFaces::create([
            "title" => $request->title,
            "product_id" => $request->id_product,
            "price" => 0
        ]);
    }

    public function GetItemFace($id)
    {
        return response()->json(ProductFaces::get_with_cost($id)->get());
    }

    public function MaterialFace($id)
    {
        $material = FacesMaterials::where('face_id', $id)->get();
        $id = array();
        foreach ($material as $value) {
            $id[] = $value->material_id;
        }
        return response()->json(rawmaterials::whereNotIn("id", $id)->get());
    }

    public function ToolFace($id)
    {
        $tool = ToolFace::where('product_faces_id', $id)->get();
        $id = array();
        foreach ($tool as $value) {
            $id[] = $value->tool_materials_id;
        }
        return response()->json(ToolMaterials::whereNotIn("id", $id)->where("type", 1)->get());
    }

    public function MaterialProduct($id)
    {
        $product = Product::find($id);

        $material = ProductFaces::select("rawmaterials.material_name", "faces_materials.*", "product_faces.title")
            ->join("faces_materials", "faces_materials.face_id", "=", "product_faces.id")->join("rawmaterials", "rawmaterials.id", "=", "faces_materials.material_id")
            ->where("product_faces.product_id", $product->id)->get();

        return $material;
    }


    public function ToolProduct($id)
    {
        $product = Product::find($id);

        $material = ProductFaces::select("tool_materials.title as name", "tool_materials.price", "tool_face.*", "product_faces.title")
            ->join("tool_face", "tool_face.product_faces_id", "=", "product_faces.id")->join("tool_materials", "tool_materials.id", "=", "tool_face.tool_materials_id")
            ->where(["product_faces.product_id", $product->id])->get();

        return $material;
    }


    public function StoreMaterialFace(Request $request)
    {
        $request->validate([
            'face' => ['required'],
            "material" => ["required"],
            "quantity" => ["required", "numeric", "max:9999999", "min:0"]
        ], [
            "face.required" => "يجب اختيار الوجه",
            "material.required" => "يجب اختيار مادة واحدة على الاقل",
            "quantity" => "يجب ادخال كمية الاستهلاك"
        ]);
        foreach ($request->material as $val) {
            FacesMaterials::create([
                'face_id' => $request->face,
                "material_id" => $val,
                "quantity" => $request->quantity
            ]);
        }
    }

    public function StoreToolFace(Request $request)
    {
        $request->validate([
            'face' => ['required'],
            "tool" => ["required"],
        ], [
            "face.required" => "يجب اختيار الوجه",
            "tool.required" => "يجب اختيار مادة واحدة على الاقل",
        ]);
        foreach ($request->tool as $val) {
            ToolFace::create([
                'product_faces_id' => $request->face,
                "tool_materials_id" => $val,
            ]);
        }
    }

    public function DeleteFace($id)
    {
        ProductFaces::find($id)->delete();
    }

    public function DeleteMaterialFace($id)
    {
        FacesMaterials::find($id)->delete();
    }

    public function DeleteToolFace($id)
    {
        ToolFace::find($id)->delete();
    }

    public function StorePrice(Request $request)
    {
        ProductFaces::find($request->id)->update([
            "price" => $request->price
        ]);
    }
}
