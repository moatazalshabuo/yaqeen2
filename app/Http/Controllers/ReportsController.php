<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchasesbill;
use App\Models\Purchasesitem;
use App\Models\rawmaterials;
use App\Models\Salesbill;
use App\Models\SalesItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function RawmaterialReports()
    {
        # code...
        $product = rawmaterials::all();

        return view("rawmaterials/reports", ['product' => $product]);
    }

    public function search_raw(Request $request)
    {
          // $where = array();
          $date = array();
          $data1 = array();
          // $data2 = array();
          $data = array();
          # code...

          $request->validate([
              'product' => "required"
          ], ['product.required' => "يرجى اختيار المادة"]);
          $raw = $request->product;

          if (isset($request->from)) {
              $request->validate([
                  'to' => "required"
              ]);
              $date['from'] = $request->from;
              $date['to'] = $request->to;
          }
          //    $i=0;
          $prus = Purchasesitem::query();
          $prus->select(
              DB::raw("min(rawmaterials.material_name) as material_name"),
              DB::raw("min(rawmaterials.id) as rawm"),
              DB::raw("sum(purchasesitems.`quantity`) as qaunt"),
              DB::raw("min(purchasesitems.created_at) as created_at"),
              DB::raw("purchasesitems.purchases_id"),
              DB::raw("min(users.name) as username")
          )
              ->join("users", "users.id", "=", "purchasesitems.user_id")
              ->join("rawmaterials", "rawmaterials.id", "=", "purchasesitems.rawmati")
              ->where('purchasesitems.rawmati', $raw);

          $prus1 = SalesItem::query();

          $prus1->select(
              DB::raw("min(sales_items.sales_id) as sales_id"),
              DB::raw("sum(item_face.quantity * faces_materials.quantity) as qaunt"),
              DB::raw("min(sales_items.created_at) as created_at"),
              DB::raw("min(rawmaterials.material_name) as material_name"),
              DB::raw("min(sales_items.created_by) as name"),
              DB::raw("min(rawmaterials.id) as rawm")
          )->join("item_face", "item_face.Item_id", "=", "sales_items.id")
            ->join('item_facea_material', "item_facea_material.item_face_id", "=", "item_face.id")
            ->join('faces_materials', "faces_materials.id", "=", "item_facea_material.material_id")
            ->join('rawmaterials', "rawmaterials.id", "=", "faces_materials.material_id")
            ->where('rawmaterials.id', $raw);
            // print_r($prus1->groupBy(["sales_items.sales_id"])->get());die();
          if (isset($date['from']) && isset($date['to'])) {

              $prus->whereBetween('purchasesitems.created_at', [$date]);

              $prus1->whereBetween('sales_items.created_at', [$date]);
          }
          if ($prus->count() > 0 || $prus1->count() > 0) {
              foreach ($prus->groupBy(["purchasesitems.purchases_id"])->get() as $val) {
                  array_push(
                      $data1,
                      [
                          'id_bill' => $val->purchases_id,
                          'name' => $val->material_name,
                          'rawid' => $val->rawm,
                          "qoa" => $val->qaunt,
                          'created_at' => $val->created_at,
                          "username" => $val->username,
                          "type" => 1
                      ]
                  );
              }
              foreach ($prus1->groupBy(["sales_items.sales_id"])->get() as $val) {

                  array_push(
                      $data1,
                      [
                          'id_bill' => $val->sales_id,
                          'name' => $val->material_name,
                          'rawid' => $raw,
                          "qoa" => $val->qaunt,
                          'created_at' => $val->created_at,
                          "username" => $val->name,
                          "type" => 2
                      ]
                  );
              }
              $data = collect($data1)->sortBy('created_at')->reverse()->toArray();
              //    print_r($data);die();
          }
          return redirect()->route('reports.rawmaterial')->with('data', $data);
    }

    public function sales_index()
    {
        # code...
        $user = User::all();

        return view("salesbill/reports", ['user' => $user]);
    }

    public function search_sales(Request $request)
    {
        $where = array();
        $date = array();
        $data = array();
        # code...

        if ($request->status != 2) {
            $where['salesbills.status'] = $request->status;
        }
        if (isset($request->user)) {
            $where['salesbills.created_by'] = $request->user;
        }
        if (isset($request->from)) {
            $request->validate([
                'to' => "required"
            ]);
            $date['from'] = $request->from;
            $date['to'] = $request->to;
        }

        if (isset($date['from']) && isset($date['to'])) {
            $data = Salesbill::select()
                ->where($where)->whereBetween('salesbills.created_at', [$date['from'], $date['to']])->get();
        } else {
            $data = Salesbill::select()->where($where)->get();
        }

        return redirect()->route('reports.sales')->with('data', $data);
    }

    public function pur_index()
    {
        # code...
        $user = User::all();

        return view("purchases/reports", ['user' => $user]);
    }
    public function search_pur(Request $request)
    {
        $where = array();
        $date = array();
        $data = array();
        # code...

        if ($request->status != 2) {
            $where['purchasesbills.status'] = $request->status;
        }
        if (isset($request->user)) {
            $where['purchasesbills.created_by'] = $request->user;
        }
        if (isset($request->from)) {
            $request->validate([
                'to' => "required"
            ]);
            $date['from'] = $request->from;
            $date['to'] = $request->to;
        }

        if (isset($date['from']) && isset($date['to'])) {
            $data = Purchasesbill::select("users.name", "purchasesbills.*")->join("users", "users.id", "=", "purchasesbills.created_by")->whereBetween('purchasesbills.created_at', [$date])->get();
        } else {
            $data = Purchasesbill::select("users.name", "purchasesbills.*")->join("users", "users.id", "=", "purchasesbills.created_by")->where($where)->get();
        }
        return redirect()->route('reports.pur')->with('data', $data);
    }

    public function product_index()
    {
        # code...
        $product = Product::all();

        return view("products/reports", ['product' => $product]);
    }
    public function search_product(Request $request)
    {
        $where = array();
        $date = array();
        $data = array();
        # code...
        $request->validate([
            'product' => "required"
        ], ['product.required' => "يرجى اختيار صنف"]);
        if (isset($request->product)) {
            $where['sales_items.prodid'] = $request->product;
        }
        if (isset($request->from)) {
            $request->validate([
                'to' => "required"
            ]);
            $date['from'] = $request->from;
            $date['to'] = $request->to;
        }

        if (isset($date['from']) && isset($date['to'])) {
            $data = SalesItem::select("products.name", "sales_items.*")
            ->join("products", "products.id", "=", "sales_items.prodid")
            ->whereBetween('sales_items.created_at', [$date])->get();
        } else {
            $data = SalesItem::select("products.name", "sales_items.*")
            ->join("products", "products.id", "=", "sales_items.prodid")->where($where)->get();
        }
        return redirect()->route('reports.product')->with('data', $data);
    }
}
