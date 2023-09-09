<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\File_sales;
use App\Models\SalesItem;
use App\Models\SalesItemFace;
use App\Models\SalesItemFaceMaterial;
use App\Models\User;
use App\Models\WorkUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkItemController extends Controller
{
    public function index($id)
    {
        $salesitem = SalesItem::select("sales_items.*", "products.name")
            ->join("products", "products.id", "=", "sales_items.prodid")
            ->where("sales_id", $id)->get();
        foreach ($salesitem as $val) {
            $ids[] = $val->id;
        }
        $files = File_sales::whereIn("sales_id", $ids)->get();

        $faces = SalesItemFace::select("product_faces.title", "item_face.*")
            ->join("product_faces", "product_faces.id", "=", "item_face.face_id")
            ->whereIn("item_face.Item_id", $ids)->get();
        $ids = array();
        foreach ($faces as $val) {
            $ids[] = $val->id;
        }
        $material = SalesItemFaceMaterial::select("rawmaterials.material_name", "item_facea_material.*")
            ->join("faces_materials", "faces_materials.id", "=", "item_facea_material.material_id")
            ->join("rawmaterials", "rawmaterials.id", "=", "faces_materials.material_id")
            ->whereIn("item_facea_material.item_face_id", $ids)->get();


        return view("work/index", compact('salesitem', "faces", "material", 'files'));
    }

    public function getUser($id)
    {
        $user_list = WorkUser::select("users.name", "work_users.*")
            ->join("users", "users.id", "=", "work_users.user_id")
            ->where("work_users.sales_id", $id)->orderBy("order")->get();
        $ids = array();

        foreach ($user_list as $value) {
            $ids[] = $value->user_id;
        }
        $user_select = User::whereNotIn("users.id", $ids)->
        join('salary_users','salary_users.user_id','=','users.id')
        ->get();

        return response()->json(["user_list" => $user_list, "user_select" => $user_select]);
    }

    public function save(Request $request)
    {

        $request->validate([
            "order" => ['required'],
            "user" => ["required"],
        ], [
            "order.required" => "لايمكن ترك حقل الترتيب فارغ",
            "user.required" => "يجب اختيار المستخدم"
        ]);
        $work = WorkUser::where("sales_id", $request->salesitem)->where('status', ">", 0)->get();

        $ids = array();


        WorkUser::create([
            "sales_id" => $request->salesitem,
            "user_id" => $request->user,
            "order" => $request->order,
            "status" => 0,
            "message" => $request->message
        ]);
        Helper::check_item($request->salesitem);
        return response()->json(['error' => 0], 200);
    }

    public function delete($id)
    {
        $work = WorkUser::find($id);

        if ($work->status == 3) {
            return response()->json(["error" => "1", "mssg" => "لايمكن حذفه لقد اتم المهمه"]);
        } else {
            Helper::check_item($work->sales_id);
            $work->delete();
        }
    }

    public function active($id)
    {
        $sales_item = SalesItem::find($id);
        $works = WorkUser::where('sales_id', $sales_item->id)->orderBy("order")->first();
        if (isset($works->id)) {
            $sales_item->status = 1;
            $sales_item->update();
            $works->status = 1;
            $works->update();
            Helper::check_item($id);
            return redirect()->back();
        } else {
            return redirect()->back()->with("delete", "يجب اختيار عمال لبداء العمل");
        }
    }

    public function getMyWork()
    {
        $work = WorkUser::query()->select('sales_items.descripe', "products.name", "users.name as username", "work_users.*")
            ->join("sales_items", "sales_items.id", "=", "work_users.sales_id")
            ->join('products', "products.id", "=", "sales_items.prodid")
            ->join('users', "users.id", "=", "work_users.user_id");
        if (Auth::user()->type == 0)
            $work->where(["work_users.user_id" => Auth::id()]);
        $work = $work->whereNotIN("work_users.status", [0, 3])->get();
        return view("work/our_work", compact('work'));
    }

    public function getSales($id)
    {
        $work = WorkUser::find($id);
        $id = $work->sales_id;
        $item = SalesItem::select("sales_items.*", "products.name")
            ->join("products", "products.id", "=", "sales_items.prodid")
            ->where("sales_items.id", $id)->first();
        $faces = SalesItemFace::select("product_faces.title", "item_face.*")
            ->join("product_faces", "product_faces.id", "=", "item_face.face_id")
            ->where("item_face.Item_id", $id)->get();
        $works = WorkUser::select("users.name", "work_users.*")->join("users", "users.id", "=", "work_users.user_id")
            ->where(["sales_id" => $item->id])->get();

        $ids = array();
        foreach ($faces as $val) {
            $ids[] = $val->id;
        }

        $material = SalesItemFaceMaterial::select("rawmaterials.material_name", "item_facea_material.*")
            ->join("faces_materials", "faces_materials.id", "=", "item_facea_material.material_id")
            ->join("rawmaterials", "rawmaterials.id", "=", "faces_materials.material_id")
            ->whereIn("item_facea_material.item_face_id", $ids)->get();

        $files = File_sales::where("sales_id", $id)->get();
        return view("work/sales_work", compact('item', "faces", "material", "work", "files", "works"));
    }

    public function startWork($id)
    {
        $work = WorkUser::find($id);
        $work->update([
            "status" => 2,
        ]);
        Helper::check_item($work->sales_id);
        return redirect()->route("self.work")->with("Add", "تم بداء العمل بنجاح ");
    }

    public function endWork($id)
    {

        $work = WorkUser::find($id);
        $price = SalesItem::find($work->sales_id);
        $work->update([
            "status" => 3,
        ]);
        Helper::count_salary($work->user_id, $price);
        $work2 = WorkUser::where(["sales_id" => $work->sales_id, "status" => 0])->orderBy("order")->first();
        if ($work2) {
            $work2->update([
                "status" => 1
            ]);
        }
        Helper::check_item($work->sales_id);
        return redirect()->route("self.work")->with("Add", "تم الانتهاء من العمل بنجاح ");
    }

    public function cancelWork($id)
    {
        WorkUser::find($id)->update([
            "status" => 1,
        ]);
        return redirect()->route("self.work")->with("edit", "تم الغاء بدء العمل بنجاح ");
    }

    public function save_file(Request $request)
    {
        $request->validate([
            "file" => ["required", "max:4000"]
        ], [
            "file.required" => "يجب اختيار مرفق"
        ]);
        $sales_item = SalesItem::find($request->id);
        foreach ($request->file('file') as $val) {
            $fileName = random_int(0, 10000000) . '.' . $val->extension();
            $val->move(public_path("uploads/$request->id"), $fileName);
            File_sales::create([
                "sales_id" => $sales_item->id,
                "file_name" => $fileName,
            ]);
        }
        return redirect()->back()->with("Add", "تم اضافة المرفقات بنجاح");
    }
}
