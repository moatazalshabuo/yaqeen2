<?php

use App\Http\Controllers\CLintController;
use App\Http\Controllers\CncToolsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\PayController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products;
use App\Http\Controllers\PurchasesbillController;
use App\Http\Controllers\PurchasesitemController;
use App\Http\Controllers\RawmaterialsController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SalesbillController;
use App\Http\Controllers\SalesItemController;
use App\Http\Controllers\ToolMaterialsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WorkItemController;
use App\Models\Purchasesbill;
use App\Models\rawmaterials;
use App\Models\SalaryUser;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Contracts\Role;

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

Route::post('material/update', [RawmaterialsController::class, 'update'])->name("materialupdate")->middleware(['auth', 'can:تعديل مادة خام']);
Route::get('material/edit/{id}', [RawmaterialsController::class, 'edit'])->name("materialedit")->middleware(['auth', 'can:تعديل مادة خام']);
Route::get('material/delete/{id}', [RawmaterialsController::class, 'delete'])->name("materialdelete")->middleware(['auth', 'can:حذف مادة خام']);

Route::get("get-item-mate", function () {
    $product = rawmaterials::select()->orderBy('id', 'DESC')->get();
    $i = 1;
    foreach ($product as $dates) {
        echo "<tr>
            <td>$i</td>
            <td>$dates->material_name</td>
            <td>";
        if ($dates->material_type == 1) {
            echo  "بمقاس المتر المربع";
        } elseif ($dates->material_type == 2) {
            echo "بمقاس المتر";
        } else {
            echo "بالقطعة";
        }
        echo "</td><td>" . floatval($dates->quantity) . " </td>
            <td>" . floatval($dates->price) . "</td>
            <td>" . floatval($dates->pace_price) . "</td>
            <td>$dates->created_by </td>";
        echo "<td class='d-flex'>";
        if (request()->user()->can("حذف مادة خام")) {
            echo "<a class='btn btn-danger ml-1 btn-icon' href='" . route('materialdelete', $dates->id) . "' ><i class='mdi mdi-delete'></i></a>";
        }
        if (request()->user()->can("تعديل مادة خام")) {
            echo "<button  data-target='#edit_material' data-toggle='modal' class='btn btn-info btn-icon edit_mate' id='$dates->id'><i class='mdi mdi-transcribe'></i></button>
           ";
        }
        echo "</td></tr>";
        $i += 1;
    }
})->name("getitem-mate")->middleware(['auth', "can:عرض المواد الخام"]);

//============================ purchases router ===========================//

Route::controller(PurchasesbillController::class)->group(function () {
    Route::prefix("Purchasesbill")->group(function () {
        Route::middleware(['auth', "can:فواتير المشتريات"])->group(function () {
            Route::get("index/{id?}", "index")->name("Purchasesbill");
            Route::get('create', "create")->name("Purchasesbill_create");
            Route::get("edit/{salesbill}", "edit")->name("purchasesbill_edit");
            Route::post("save/", "save")->name("purchasesbill_save");
            Route::get("get_bill/{id?}", "get_bill_data")->name("get_purbill");
            Route::get("to-receive/{id}", "ToReceive")->name("ToReceive");
            Route::get("cancel-receive/{id}", "CancelReceive")->name("CancelReceive");
        });
    });
});

Route::get('check_purbill/{id}', function ($id) {
    if (Purchasesbill::find($id)->status == 0) {
        return response()->json(["success" => "تم الحذف بنجاح"], 200);
    } else {
        return response()->json(["mass" => "يجب اغلاق الفاتورة اولا"], 200);
    }
})->name('check_purbill')->middleware(['auth']);

Route::controller(PurchasesitemController::class)->group(function () {
    Route::prefix("Purchasesitem")->group(function () {
        Route::middleware(['auth', 'can:فواتير المشتريات'])->group(function () {
            Route::post("add", "create")->name("add_puritem");
            Route::get("getTotalItem/{id}", "getItemTotal")->name("getItempurbill");
            Route::get("delete/{id}", "destroy")->name("deletePurItem");
            Route::get("edit/{id}", "edit_item")->name("editPurItem");
            // Route::get("get_bill/{id?}","get_bill_data")->name("get_purbill");
        });
    });
});

Route::controller(RawmaterialsController::class)->group(function () {
    Route::middleware(['auth', 'can:عرض المواد الخام'])->group(function () {
        Route::get("getmatedata/{id}", "getoldprice")->name("getoldprice");
    });
});

Route::controller(CustomerController::class)->group(function () {
    Route::prefix("custom")->group(function () {
        Route::middleware(['auth', 'can:عرض المستخدمين'])->group(function () {
            Route::get("showSelect/{id?}", "show_select")->name("customSelect");
            Route::post("create", "create")->name("createCustom");
        });
    });
});

// =============================== product route ====================

Route::controller(ProductController::class)->group(function () {
    Route::prefix("productajax")->group(function () {
        Route::middleware(['auth', "can:عرض المنتجات"])->group(function () {
            Route::get("get-products/{id?}", "GetProduct")->name("get-products");
            Route::post("edit/{id}", "update")->name("product.update")->middleware("can:تعديل المنتجات");
            Route::post("StoreFace", "StoreFace")->name("face.store")->middleware("can:تعديل المنتجات");
            Route::get("DeleteFace/{id}", "DeleteFace")->name("delete.face")->middleware("can:تعديل المنتجات");
            Route::get("ItemFace/{id}", "GetItemFace")->name("face.item")->middleware("can:تعديل المنتجات");
            Route::get("MaterialFace/{id?}", "MaterialFace")->name("face.material")->middleware("can:تعديل المنتجات");
            Route::get("ToolFace/{id?}", "ToolFace")->name("face.tool")->middleware("can:تعديل المنتجات");
            Route::get("MaterialProduct/{id}", "MaterialProduct")->name("product.material")->middleware("can:تعديل المنتجات");
            Route::get("ToolProduct/{id}", "ToolProduct")->name("product.tool")->middleware("can:تعديل المنتجات");
            Route::post("StoreMaterialFace", "StoreMaterialFace")->name("materialface.add")->middleware("can:تعديل المنتجات");
            Route::post("StoreToolFace", "StoreToolFace")->name("toolface.add")->middleware("can:تعديل المنتجات");
            Route::get("DeleteMaterialFace/{id}", "DeleteMaterialFace")->name("delete.material_face")->middleware("can:تعديل المنتجات");
            Route::get("DeleteToolFace/{id}", "DeleteToolFace")->name("delete.tool_face")->middleware("can:تعديل المنتجات");
            Route::post("StorePrice", "StorePrice")->name('price.store')->middleware("can:تعديل المنتجات");
        });
    });
});

###################== CnC =#################################
Route::controller(CncToolsController::class)->group(function () {
    Route::prefix("cncajax")->group(function () {
        Route::middleware(['auth', "can:عرض cnc"])->group(function () {
            Route::post("edit-tools", "edit")->name("tool.edit");
            Route::get("active/{id}", "active")->name('active');
            Route::get("unactive/{id}", "unactive")->name('unactive');
        });
    });
});

Route::controller(ToolMaterialsController::class)->group(function () {
    Route::prefix("tools-material")->group(function () {
        Route::middleware(['auth', "can:عرض cnc"])->group(function () {
            Route::post("edit-tm", "edit")->name("tool.material.edit");
            Route::get("active/{id}", "active")->name('tool.material.active');
            Route::get("unactive/{id}", "unactive")->name('tool.material.unactive');
            Route::get("cnc-tool-data/{id}", "get_data")->name("cnc.tool.data");
        });
    });
});
######################### end cnc route ###########################

######################### start salesbill route ###################
/*  start salesbill page all staff  */
Route::controller(SalesbillController::class)->group(function () {
    Route::prefix("Salesbill")->group(function () {
        Route::middleware(['auth', "can:فواتير المبيعات"])->group(function () {
            Route::get("index/{id?}", "index")->name("salesbill");
            Route::get('create', "create")->name("salesbiil_create");
            Route::get("edit/{salesbill}", "edit")->name("salesbill_edit");
            Route::post("save", "save")->name("salesbill_save");
            Route::get("Information-prodect/{id}", "InformationProduct")->name("product.information");
            Route::post("StoreSales", "StroeSales")->name("store.sales");
            Route::get("to-receive/{id}", "ToReceive")->name("ToReceiveSales");
            Route::get("cancel-receive/{id}", "CancelReceive")->name("CancelReceiveSales");
            Route::get("check-close/{id}", "CheckStatus")->name('salesbill.check');
            Route::get("get_bill/{id?}", "get_bill_data")->name("get_bill");
        });
    });
});

Route::controller(SalesItemController::class)->group(function () {
    Route::prefix("SalesItem")->group(function () {
        Route::middleware(['auth', "can:فواتير المبيعات"])->group(function () {
            Route::post("add", "create")->name("add_item");
            Route::get("get_item/{id}", "get_item")->name("getItembill");
            Route::get("delete/{id}", "destroy")->name("delete-item");
            // Route::get("edit/{id}","edit")->name("editSaleItem");
        });
    });
});

################### end salesbill route #############################

/*  start client page all staff  */
Route::controller(CLintController::class)->group(function () {
    Route::prefix("client")->group(function () {
        Route::middleware(['auth'])->group(function () {
            Route::get("showSelect/{id?}", "show_select")->name("clientSelect");
            Route::post("create", "create")->name("createClient");
        });
    });
});
/*end*/


################### start work item route #############################

Route::controller(WorkItemController::class)->group(function () {
    Route::prefix("start-work")->group(function () {
        Route::middleware(['auth'])->group(function () {
            Route::get("work/{id}", "index")->name("startwork.index");
            Route::get("get-users/{id}", "getUser")->name("work.user");
            Route::post("save", "save")->name("save.work");
            Route::get("delete/{id}", "delete")->name("work.delete");
            Route::get("active/{id}", "active")->name("work.active");
            Route::get("my-work", "getMyWork")->name("self.work");
            Route::get("sales/{id}", "getSales")->name("work.sales");
            Route::get("start-work/{id}", "startWork")->name("start.work");
            Route::get("end-work/{id}", "endWork")->name("end.work");
            Route::get("cancel-work/{id}", "cancelWork")->name("cancel.work");
            Route::post("files", "save_file")->name("work.files");
        });
    });
});


########################## user route ####################################

Route::controller(UsersController::class)->group(function () {
    Route::prefix("users")->group(function () {
        Route::middleware(['auth',"can:عرض المستخدمين"])->group(function () {
            Route::get("index", "index")->name("users.index");
            Route::get("create", "create")->name("users.create");
            Route::post('store', "store")->name("users.store");
            Route::get("edit/{id}", "edit")->name('users.edit');
            Route::put("update/{id}", "update")->name("users.update");
            Route::delete("/{id}", "delete")->name('users.delete');
        });
    });
});

Route::controller(SalaryController::class)->group(function () {
    Route::prefix("salary")->group(function () {
        Route::middleware(['auth',"can:محاسب"])->group(function () {
            Route::get("/", "index")->name("salary");
            Route::post("save", "save_salary")->name("salary.save");
            Route::get("get/{id}", "getData")->name("salary.get");
            Route::post("update", "updata_salary")->name("salary.update");
            Route::get("mount/", "salary_index")->name("salary.mount");
            Route::post("dept", "save_dept")->name("salary.dept");
            Route::post("save/salary", "Salary_save")->name("save.salary");
            Route::get("delete/{id}", "delete")->name("delete.salary");
            Route::get("delete-dept/{id}", "deleteDept")->name("delete.depts");
        });
    });
});

Route::controller(PayController::class)->group(function () {
    Route::prefix('pay')->group(function () {
        Route::post('pay_receipt', "pay")->name("pay_receipt");
        // ايصال القبض
        Route::get("client_pay/{id?}", "client_pay")->name("client_pay");
    });
});

Route::controller(ExchangeController::class)->group(function () {
    Route::prefix("exchsnge")->group(function () {
        Route::post('exchnge_receipt', "pay")->name("Exchange_receipt");
        Route::post('exchnge-exc', "pay1")->name("Exchange-exc");
    });
});
