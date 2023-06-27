<?php

namespace App\Http\Controllers;

use App\Models\dept;
use App\Models\SalaryMount;
use App\Models\SalaryUser;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;

class SalaryController extends Controller
{
    public function index()
    {
        $salary = SalaryUser::select("salary_users.*", "users.name")
            ->join("users", "salary_users.user_id", "=", "users.id")->get();
        $users = User::all();
        return view("salary/index", compact('users', "salary"));
    }

    public function save_salary(Request $request)
    {
        $roles = [
            "user_id" => "required",
            "type_salary" => "required"
        ];
        if ($request->type_salary == 1) {
            $roles['salary'] = "required|numeric|max:100|min:1";
        } else {
            $roles['salary'] = "required|numeric|min:1";
        }
        $massage = [
            "user_id.required" => "يجب اختيار مستخدم",
            "type_user.required" => "يجب اختبار نوع الراتب",
            "salary.required" => "يجب ادخال القيمة",
            "salary.min" => "لايمكن ادخل قيمة اقل من 1 ",
            "salary.max" => "لايمكن ادخال نسبة اكبر من 100"
        ];
        $request->validate($roles, $massage);

        if ($request->type_salary == 1) {
            $salary = 0;
            $rate = $request->salary;
        } else {
            $salary = $request->salary;
            $rate = 0;
        }
        $salary = SalaryUser::where("user_id", $request->user_id)->get();
        if (!isset($salary->id)) {
            SalaryUser::create([
                "user_id" => $request->user_id,
                "type_salary" => $request->type_salary,
                "rate" => $rate,
                "salary" => $salary,
                "totel_salary" => $salary,

            ]);
            return response()->json(["error" => 0]);
        }
        return response()->json(["error" => 1]);
    }

    public function getData($id)
    {
        $salary = SalaryUser::find($id);
        return response()->json($salary, 200);
    }


    public function updata_salary(Request $request)
    {
        $salaryl = SalaryUser::find($request->id);
        $roles = [
            "user_id" => "required",
            "type_salary" => "required"
        ];
        if ($request->type_salary == 1) {
            $roles['salary'] = "required|numeric|max:100|min:1";
        } else {
            $roles['salary'] = "required|numeric|min:1";
        }
        $massage = [
            "user_id.required" => "يجب اختيار مستخدم",
            "type_user.required" => "يجب اختبار نوع الراتب",
            "salary.required" => "يجب ادخال القيمة",
            "salary.min" => "لايمكن ادخل قيمة اقل من 1 ",
            "salary.max" => "لايمكن ادخال نسبة اكبر من 100"
        ];
        $request->validate($roles, $massage);

        if ($request->type_salary == 1) {
            $salary = 0;
            $rate = $request->salary;
        } else {
            $salary = $request->salary;
            $rate = 0;
        }

        $salaryl->update([
            "type_salary" => $request->type_salary,
            "rate" => $rate,
            "salary" => $salary,
        ]);
    }


    public function  salary_index()
    {
        $users = User::join("salary_users", "salary_users.user_id", "=", "users.id")->get();
        $date = (request('date')) ? request('date') : date("Y-m");
        if(request("all")){
            $salary = SalaryMount::all();
            $dept = dept::select("depts.*","users.name")
            ->join("users","users.id","=","depts.user_id")
            ->get();
        }else{
            $salary = SalaryMount::where("mount", "like", $date)->get();
            $dept = dept::select("depts.*","users.name")
            ->join("users","users.id","=","depts.user_id")
            ->where('depts.created_at','like',"%".$date."%")->get();
        }
        return view("salary/salary_mount", compact("users", 'salary',"dept"));
    }

    public function save_dept(Request $request)
    {
        $request->validate([
            'price' => "required|numeric|min:1|max:999999",
            'user_id' => "required"
        ]);

        $salary = SalaryUser::find($request->user_id);
        dept::create([
            "user_id" => $salary->user_id,
            "price" => $request->price,
        ]);
        $salary->dept = $salary->dept + $request->price;

        $salary->update();
    }

    public function Salary_save(Request $request)
    {
        $request->validate([
            'user_id' => "required",
            "mounth" => "required",
        ]);
        $salary = SalaryUser::find($request->user_id);

        $dept_on = isset($request->dept_on) ? $request->dept_on : 0;
        $plus = isset($request->plus) ? $request->plus : 0;
        $mysalary = ($salary->type_salary == 1) ? $salary->totel_salary : $salary->salary;
        $totel_salary = ($plus + $mysalary) - $dept_on;

        $salary->dept = $salary->dept - $dept_on;
        $salary->update();

        SalaryMount::create([
            "mount" => $request->mounth,
            "salary_id"=>$salary->id,
            "user_name" => User::find($salary->user_id)->name,
            "salary" => $totel_salary,
            "plus" => $plus,
            "dept_on" => $dept_on,
            "still" => $salary->dept
        ]);
    }
    public function delete($id){
        $sal = SalaryMount::find($id);

        echo "<br><br>";
        $saluser = SalaryUser::find($sal->salary_users);

        $saluser->update([
            'dept'=>$saluser->dept + $sal->dept_on,
        ]);
        $sal->delete();

        return redirect()->back()->with("delete","تم الحذف بنجاح");
    }

    public function deleteDept($id){
        $dept = dept::find($id);
        $salary = SalaryUser::where("user_id",$dept->user_id)->first();

        $salary->dept = $salary->dept - $dept->price;
        $salary->update();
        $dept->delete();

        return redirect()->back()->with("delete","تم الحذف بنجاح");

    }
}
