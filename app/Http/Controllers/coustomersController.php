<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Purchasesbill;
use Illuminate\Http\Request;

class coustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Customer::all();

        return view('customers.index', compact('user'));
    }
    public function store(Request $request)
    {

        $input = $request->all();
        $validtiondata = $request->validate(
            [
                'name' => 'required',
                "email"=>['max:80'],
                "address"=>['max:191']

            ],
            [
                'name.required' => 'يرجى ادخال اسم المورد',
            ]
        );
        customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            "email"=>$request->email,
            "address"=>$request->address
        ]);
        session()->flash('Add', 'تم اضافة بيانات المورد بنجاح');
        return redirect('/customers');
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $data_update = Customer::find($id);
        $data_update->update([
            'name' => $request->name,
            'phone' => $request->phone,
            "email"=>$request->email,
            "address"=>$request->address

        ]);
        session()->flash('edit', 'تم تعديل بيانات المورد بنجاج');
        return redirect('/customers');
    }

    public function destroy(Request $request)
    {
        $Products = Customer::findOrFail($request->pro_id);
        // echo $Products->id;
        $id = Purchasesbill::select()->where('custom',$Products->id)->count();

        if($id != 0){

            session()->flash('delete', 'لايمكن حذف اذا كان مسجل في احد الفواتير');
            return back();

        }else{
            $Products->delete();
            session()->flash('delete', 'تم حذف بيانات المورد بنجاح');
            return back();
        }
    }
}
