<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\clientsController;
use App\Http\Controllers\CncToolsController;
use App\Http\Controllers\coustomersController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchasesController;
use App\Http\Controllers\RawmaterialsController;
use App\Http\Controllers\SalesbillController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ToolMaterialsController;
use App\Models\ProductFaces;
use App\Models\ToolMaterials;
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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->middleware('auth');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware("auth");

Route::get('/account', [App\Http\Controllers\HomeController::class, 'account'])->name('account')->middleware(['auth']);


Route::resource('rawmaterials',RawmaterialsController::class)->middleware(['can:عرض المواد الخام','auth']);

Route::resource('products',ProductController::class)->middleware(["can:عرض المنتجات",'auth']);

Route::resource("cnc-tools",CncToolsController::class)->middleware(["can:عرض cnc",'auth']);

Route::resource("toolMaterial",ToolMaterialsController::class)->middleware(["can:عرض cnc",'auth']);

// Route::resource("salesbill",SalesbillController::class);

Route::get('/pages/{page}', [AdminController::class, 'index']);

Route::resource('customers', coustomersController::class)->middleware(['auth']);

Route::resource('clients', clientsController::class)->middleware(['auth']);
