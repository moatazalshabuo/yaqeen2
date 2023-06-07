<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\FacesMaterials;
use App\Models\SalesItem;
use App\Models\SalesItemFace;
use Illuminate\Http\Request;

class SalesItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesItem $salesItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesItem $salesItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesItem $salesItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $faces = SalesItemFace::where("item_id",$id)->get();

        foreach ($faces as $item) {

            $_pord = FacesMaterials::where("face_id",$item->face_id)->get();
            foreach ($_pord as $val){
                $quantity = $item->quantity * $val->quantity;
                $material_id = $val->material_id;
                Helper::AddQuantity($material_id, $quantity);
            }
        }
        $sales = SalesItem::find($id)->delete();
        // return $item;
        // $sales->delete();
    }

    public function get_item($id){
        $salesitem = SalesItem::select("sales_items.*","products.name")
        ->join("products","products.id","=","sales_items.prodid")
        ->where("sales_items.sales_id",$id)->get();
        return response()->json($salesitem);
    }
}
