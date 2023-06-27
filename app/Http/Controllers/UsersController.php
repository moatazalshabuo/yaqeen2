<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
    public function index(): View
    {
        $users = User::all();

        return view("users/index", compact("users"));
    }

    public function create(): View
    {
        $permission = Permission::all();
        return view("users/create", compact('permission'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            "permission" => ['required']
        ], [
            'name.required' =>      "يرجى ادخال الاسم",
            "email.required" =>     "يرجى ادخال الايميل",
            "password.required" =>   "يجب ادخال كلمة المرور ",
            "permission.required" => "يجب اختيار الصلاحيات"
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        foreach ($request->permission as $val) {
            $user->givePermissionTo($val);
        }
        return redirect()->back()->with("Add", "تم اضافة المستخدم بنجاح");
    }
    public function edit($id)
    {
        $user = User::with('permissions')->where('id', $id)->get()[0];
        $ourpermission = array();

        foreach ($user->permissions as $val) {
            $ourpermission[] = $val->name;
        }
        $permission = Permission::all();
        return view("users/edit", compact("user", "permission", "ourpermission"));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $roule = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            "permission" => ['required']
        ];
        if(isset($request->password))
        $roule["password"] = ['string', 'min:8', 'confirmed'];
        $request->validate($roule, [
            'name.required' =>      "يرجى ادخال الاسم",
            "email.required" =>     "يرجى ادخال الايميل",
            "permission.required" => "يجب اختيار الصلاحيات"
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if (isset($request->password)) {
            $user = Hash::make($request->password);
        }
        $user->update();
        $permission = Permission::all();
        foreach ($permission as $val) {
            $user->revokePermissionTo($val->name);
        }
        foreach ($request->permission as $value) {
            $user->givePermissionTo($value);
        }

        return redirect()->route("users.index")->with('edit', "تم التعديل بنجاح");
    }

    public function delete($id){
        User::find($id)->delete();

        return redirect()->route("users.index")->with("delete","تم الحذف بنجاح");
    }
}
