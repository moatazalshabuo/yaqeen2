<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // User::find(1)->givePermissionTo('show-users');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function account(Request $request)
    {
        if (isset($request->account)) {
            if ($request->account == 1) {
                $data  = Client::select('c_lints.name', "c_lints.phone", DB::raw("SUM(salesbills.sincere) as sincere"), DB::raw("SUM(salesbills.Residual) as Residual"))->join("salesbills", "salesbills.client", "c_lints.id")->groupBy("c_lints.id")->get();
            } else {
                $data  = Customer::select('customers.name', "customers.phone", DB::raw("SUM(purchasesbills.sincere) as sincere"), DB::raw("SUM(purchasesbills.Residual) as Residual"))->join("purchasesbills", "purchasesbills.custom", "customers.id")->groupBy("customers.id")->get();
            }
        } else {
            $data = array();
        }
        return view('account', ['data' => $data]);
    }
}
