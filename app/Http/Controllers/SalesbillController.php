<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\FacesMaterials;
use App\Models\Product;
use App\Models\ProductFaces;
use App\Models\Salesbill;
use App\Models\SalesItem;
use App\Models\SalesItemFace;
use App\Models\SalesItemFaceMaterial;
use App\Models\ToolFace;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use PHPUnit\TextUI\Help;

class SalesbillController extends Controller
{
    public function index($id = "")
    {
        if (Auth::user()->user_type != 1 && Auth::user()->user_type != 0) {

            $wher = (!empty($id) && !empty(Salesbill::find($id))) ? ["created_by" => Auth::user()->name, "salesbills.id" => $id] : ["created_by" => Auth::id()];
            $pages = Salesbill::where("created_by", Auth::id())->get();
        } else {

            $wher = (!empty($id) && !empty(Salesbill::find($id))) ? ["salesbills.id" => $id] : [];
            $pages = Salesbill::all();
        }

        $last_bill = Salesbill::select()->where($wher)->orderby("id", "DESC")->first();

        if (!empty($last_bill)) {

            $index = array();

            foreach ($pages as $val) {
                array_push($index, $val['id']);
            }
            // احضار انديكس الصفحة الحالة
            $current = array_search($last_bill->id, $index);
            $first = $index[0];
            $last = $index[count($index) - 1];
            $next = isset($index[$current + 1]) ? $index[$current + 1] : "";
            $prev = isset($index[$current - 1]) ? $index[$current - 1] : "";
        } else {
            $next = "";
            $prev = "";
            $last = "";
            $first = "";
        }
        return view("salesbill/sales", ['data' => $last_bill, 'next' => $next, "prev" => $prev, "last" => $last, "first" => $first, "product" => Product::where("status", 1)->get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $id = Salesbill::create([
            'created_by' => Auth::user()->name,
            "status" => 1
        ])->id;
        return redirect()->route("salesbill", $id);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salesbill $salesbill)
    {
        //
        // $salesbill->status = 1;
        // $salesbill->update();
        // return redirect()->route("salesbill", $salesbill->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function save(Request $request)
    {

        // $request->validate([
        //     "client" => "required",
        //     "sincere" => "required|min:0|max:999999|regex:/^(([0-9]*)(\.([0-9]+))?)$/"
        // ], [
        //     "client.required" => "يرجى اختيار الزبون",
        //     "sincere.required" => "يرجى ادخال القمية المستلمة اذا توفرت او يمكن ادخال 0",
        //     "sincere.min" => "لايمكن ادخال قيمة بالسالب"
        // ]);
        // $salesbill = Salesbill::find($request->id);
        // if ($salesbill->total > 0) {
        //     if ($request->sincere <= $salesbill->total) {
        //         $salesbill->status = 0;
        //         $salesbill->sincere = $request->sincere;
        //         $salesbill->Residual = $salesbill->total - $request->sincere;
        //         $salesbill->client = $request->client;
        //         $salesbill->update();
        //         // return redirect()->route("salesbill",$salesbill->id);
        //         $data = array("id" => $salesbill->id);
        //     } else {
        //         $data = array("mass" => "القيمة الخالصة اكبر من اجمالي الفاتورة");
        //     }
        // } else {
        //     $data = array("mass" => "لايمكن اغلاق فاتورة فارغة");
        // }
        // echo json_encode($data);
    }

    //======================= production function =====================

    public function InformationProduct($id)
    {
        $product = Product::find($id);
        $faces = ProductFaces::where("product_id", $id)->get();
        $material = FacesMaterials::Material($id);
        $tool = ToolFace::Tools($id);
        return response()->json(['product' => $product, "face" => $faces, "material" => $material,'tools'=>$tool]);
    }

    public function StroeSales(Request $request)
    {
        $face_item = ProductFaces::where("product_id", $request->product_id)->get();
        $_product = Product::find($request->product_id);
        $roles = array();
        $massege = array();
        if (count($face_item) > 0) {
            foreach ($face_item as $val) {
                if ($_product->type == 2) {
                    $roles['count' . $val['id']] = "required";
                    $roles['face_material' . $val['id']] = "required";
                    $roles['height' . $val['id']] = "required|min:0|max:9999999";
                    $roles['width' . $val['id']] = "required|min:0|max:9999999";
                    $massege['count' . $val['id'] . ".required"] = "لايمكن ترك الحقل فارغ";
                    $massege['face_material' . $val['id'] . ".required"] = "لايمكن ترك الحقل فارغ";
                    $massege['height' . $val['id'] . ".required"] = "لايمكن ترك الحقل فارغ";
                    $massege['width' . $val['id'] . ".required"] = "لايمكن ترك الحقل فارغ";
                } elseif ($_product->type == 0) {
                    $roles['quantity' . $val['id']] = "required|min:0|max:9999999";
                    $massege['quantity' . $val['id'] . ".required"] = "لايمكن ترك الحقل فارغ";
                }
                $roles['price' . $val['id']] = "required|min:0|max:9999999";
                $massege['price' . $val['id'] . ".required"] = "لايمكن ترك الحقل فارغ";
            }

            $roles['count'] = "required|integer|min:1|max:9999999";
            $massege["count.required"] = "لايمكن ترك هذا الحقل فارغ";
            $request->validate($roles, $massege);
            $errors = array();
            foreach ($face_item as $val) {
                if ($_product->type == 2)
                    $_quantity = $_POST['count' . $val['id']] * ($_POST["height" . $val['id']] * $_POST["width" . $val['id']]) * $request->count;
                elseif ($_product->type == 0)
                    $_quantity = $_POST['quantity' . $val["id"]] * $request->count;
                elseif ($_product->type == 1)
                    $_quantity = $request->count;
                foreach ($_POST['face_material' . $val['id']] as $item) {
                    $_material = FacesMaterials::find($item);
                    $re = Helper::CheckMaterial($_material->material_id, ($_quantity * $_material->quantity));
                    if ($re != null)
                        $errors[] = $re;
                }
            }
            // print($errors[0]["material_name"]);die();
            if (!isset($errors[0]['material_name'])) {
                $salesitem = array(
                    "prodid" => $request->product_id,
                    "sales_id" => $request->sales_id,
                    "quantity" => 0,
                    "totel" => 0,
                    "created_by" => Auth::user()->name,
                    "descripe" => $request->descripes
                );
                $faces = array();
                $material = array();

                foreach ($face_item as $val) {
                    if ($_product->type == 2) {
                        $salesitem['quantity'] += $_POST['count' . $val['id']] * ($_POST["height" . $val['id']] * $_POST["width" . $val['id']]) * $request->count;
                        $salesitem["totel"] += ($_POST['count' . $val['id']] * ($_POST["height" . $val['id']] * $_POST["width" . $val['id']])) * $_POST['price' . $val['id']] * $request->count;
                    } elseif ($_product->type == 0) {
                        $salesitem['quantity'] += $_POST['quantity' . $val['id']] * $request->count;
                        $salesitem["totel"] += $_POST['quantity' . $val['id']] * $request->count * $_POST['price' . $val['id']];
                    } elseif ($_product->type == 1) {
                        $salesitem['quantity'] += $request->count;
                        $salesitem["totel"] += $request->count * $_POST['price' . $val['id']];
                    }
                }

                $sales_item_id = SalesItem::create($salesitem)->id;

                foreach ($face_item as $val) {
                    if ($_product->type == 2) {
                        $q = $_POST['count' . $val['id']] * ($_POST["height" . $val['id']] * $_POST["width" . $val['id']]) * $request->count;
                    } elseif ($_product->type == 0) {
                        $q = $_POST['quantity' . $val['id']] * $request->count;
                    } elseif ($_product->type == 1) {
                        $q = $request->count;
                    }
                    $faces = array(
                        "Item_id" => $sales_item_id,
                        "face_id" => $val['id'],
                        "quantity" => $q,
                        "price" => $_POST['price' . $val['id']] * $q
                    );

                    $id = SalesItemFace::create($faces)->id;

                    foreach ($_POST['face_material' . $val['id']] as $item) {

                        $_pord = FacesMaterials::find($item);

                        $material = array(
                            "quantity" => $faces['quantity'] * $_pord->quantity,
                            "material_id" => $_pord->material_id,
                            "item_material_id" => $id,
                            "id" => $item
                        );

                        Helper::CancelQuantity($material["material_id"], $material["quantity"]);

                        SalesItemFaceMaterial::create([
                            "material_id" => $material['id'],
                            "item_face_id" => $material["item_material_id"]
                        ]);
                    }
                    if(isset($_POST['face_tool'.$val['id']])){
                        foreach($_POST['face_tool'.$val['id']] as $to){
                            Helper::InsertToolItem($to,$q,$sales_item_id);
                        }
                    }
                }
                return response()->json(["success" => 1, "error" => 0]);
            } else {
                return response()->json(["success" => 0, "error" => 1, $errors]);
            }
        } else {
            return response()->json(["error" => "يجب اضافة بعض الاوجه ليصبح المنتج متاح للبيع "], 423);
        }
    }
}
