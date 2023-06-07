<?php

use App\Http\Controllers\CncToolsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products;
use App\Http\Controllers\PurchasesbillController;
use App\Http\Controllers\PurchasesitemController;
use App\Http\Controllers\RawmaterialsController;
use App\Http\Controllers\SalesbillController;
use App\Http\Controllers\SalesItemController;
use App\Http\Controllers\ToolMaterialsController;
use App\Models\Purchasesbill;
use App\Models\rawmaterials;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ======================== roawmaterial route ==================================

Route::post('material/update', [RawmaterialsController::class, 'update'])->name("materialupdate")->middleware(['auth']);
Route::get('material/edit/{id}', [RawmaterialsController::class, 'edit'])->name("materialedit")->middleware(['auth']);
Route::get('material/delete/{id}', [RawmaterialsController::class, 'delete'])->name("materialdelete")->middleware(['auth']);

Route::get("get-item-mate", function () {
    $product = rawmaterials::select()->orderBy('id', 'DESC')->get();
    $i = 1;
    foreach ($product as $dates) {
        echo "<tr>
            <td>$i</td>
            <td>$dates->material_name</td>
            <td>";
        if ($dates->material_type == 1) {
            echo  "بمقاس المتر";
        } elseif ($dates->material_type == 2) {
            echo "بمقاس الطرف";
        }
        echo "</td><td>" . floatval($dates->quantity) . " </td>
            <td>" . floatval($dates->price) . "</td>
            <td>$dates->created_by </td>";
        echo "<td class='d-flex'>";
        echo "<a class='btn btn-danger ml-1 btn-icon' href='" . route('materialdelete', $dates->id) . "' ><i class='mdi mdi-delete'></i></a>";
        echo "<button  data-target='#edit_material' data-toggle='modal' class='btn btn-info btn-icon edit_mate' id='$dates->id'><i class='mdi mdi-transcribe'></i></button>
            </td></tr>";
        $i += 1;
    }
})->name("getitem-mate");

//============================ purchases router ===========================//

Route::controller(PurchasesbillController::class)->group(function () {
    Route::prefix("Purchasesbill")->group(function () {
        Route::middleware(['auth'])->group(function () {
            Route::get("index/{id?}", "index")->name("Purchasesbill");
            Route::get('create', "create")->name("Purchasesbill_create");
            Route::get("edit/{salesbill}", "edit")->name("purchasesbill_edit");
            Route::post("save/", "save")->name("purchasesbill_save");
            Route::get("get_bill/{id?}", "get_bill_data")->name("get_purbill");
            Route::get("to-receive/{id}","ToReceive")->name("ToReceive");
            Route::get("cancel-receive/{id}","CancelReceive")->name("CancelReceive");
        });
    });
});

Route::get('check_purbill/{id}',function($id){
    if(Purchasesbill::find($id)->status == 0){
        return response()->json(["success"=>"تم الحذف بنجاح"], 200);
    }else{
        return response()->json(["mass"=>"يجب اغلاق الفاتورة اولا"], 200);
    }
})->name('check_purbill')->middleware(['auth']);

Route::controller(PurchasesitemController::class)->group(function () {
    Route::prefix("Purchasesitem")->group(function () {
        Route::middleware(['auth'])->group(function () {
            Route::post("add", "create")->name("add_puritem");
            Route::get("getTotalItem/{id}", "getItemTotal")->name("getItempurbill");
            Route::get("delete/{id}", "destroy")->name("deletePurItem");
            Route::get("edit/{id}", "edit_item")->name("editPurItem");
        });
    });
});

Route::controller(RawmaterialsController::class)->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get("getmatedata/{id}", "getoldprice")->name("getoldprice");
    });
});

Route::controller(CustomerController::class)->group(function(){
    Route::prefix("custom")->group(function(){
        Route::middleware(['auth'])->group(function(){
            Route::get("showSelect/{id?}","show_select")->name("customSelect");
            Route::post("create","create")->name("createCustom");
        });
    });
});

// ======,========================= product route ====================

Route::controller(ProductController::class)->group(function(){
    Route::prefix("productajax")->group(function(){
        Route::middleware(['auth'])->group(function(){
            Route::get("get-products/{id?}","GetProduct")->name("get-products");
            Route::post("edit/{id}","update")->name("product.update");
            Route::post("StoreFace","StoreFace")->name("face.store");
            Route::get("DeleteFace/{id}","DeleteFace")->name("delete.face");
            Route::get("ItemFace/{id}","GetItemFace")->name("face.item");
            Route::get("MaterialFace/{id?}","MaterialFace")->name("face.material");
            Route::get("ToolFace/{id?}","ToolFace")->name("face.tool");
            Route::get("MaterialProduct/{id}","MaterialProduct")->name("product.material");
            Route::get("ToolProduct/{id}","ToolProduct")->name("product.tool");
            Route::post("StoreMaterialFace","StoreMaterialFace")->name("materialface.add");
            Route::post("StoreToolFace","StoreToolFace")->name("toolface.add");
            Route::get("DeleteMaterialFace/{id}","DeleteMaterialFace")->name("delete.material_face");
            Route::get("DeleteToolFace/{id}","DeleteToolFace")->name("delete.tool_face");
            Route::post("StorePrice","StorePrice")->name('price.store');
        });
    });
});

###################== CnC =#################################
Route::controller(CncToolsController::class)->group(function(){
    Route::prefix("cncajax")->group(function(){
        Route::middleware(['auth'])->group(function(){
            Route::post("edit-tools","edit")->name("tool.edit");
            Route::get("active/{id}","active")->name('active');
            Route::get("unactive/{id}","unactive")->name('unactive');
        });
    });
});

Route::controller(ToolMaterialsController::class)->group(function(){
    Route::prefix("tools-material")->group(function(){
        Route::middleware(['auth'])->group(function(){
            Route::post("edit-tm","edit")->name("tool.material.edit");
            Route::get("active/{id}","active")->name('tool.material.active');
            Route::get("unactive/{id}","unactive")->name('tool.material.unactive');
        });
    });
});
######################### end cnc route ###########################

######################### start salesbill route ###################
/*  start salesbill page all staff  */
Route::controller(SalesbillController::class)->group(function(){
    Route::prefix("Salesbill")->group(function(){
        Route::middleware(['auth'])->group(function(){
            Route::get("index/{id?}","index")->name("salesbill");
            Route::get('create',"create")->name("salesbiil_create");
            Route::get("edit/{salesbill}","edit")->name("salesbill_edit");
            Route::post("save","save")->name("salesbill_save");
            Route::get("Information-prodect/{id}","InformationProduct")->name("product.information");
            Route::post("StoreSales","StroeSales")->name("store.sales");
        });
    });
});

Route::controller(SalesItemController::class)->group(function(){
    Route::prefix("SalesItem")->group(function(){
        Route::middleware(['auth'])->group(function(){
        // Route::post("add","create")->name("add_item");
        Route::get("get_item/{id}","get_item")->name("getItembill");
        Route::get("delete/{id}","destroy")->name("delete-item");
        // Route::get("edit/{id}","edit")->name("editSaleItem");
        });
    });
});

################### end salesbill route #############################
