<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CncToolsController;
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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::resource('rawmaterials',RawmaterialsController::class);

Route::resource('products',ProductController::class);

Route::resource("cnc-tools",CncToolsController::class);

Route::resource("toolMaterial",ToolMaterialsController::class);

// Route::resource("salesbill",SalesbillController::class);

Route::get('/pages/{page}', [AdminController::class, 'index']);

