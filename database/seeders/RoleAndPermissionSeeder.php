<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name'=>"حذف المسؤولين"]);
        Permission::create(['name'=>"حذف المستخدمين"]);
        Permission::create(['name'=>"تعديل المستخدمين"]);
        Permission::create(['name'=>"اضافة مستخدم"]);
        Permission::create(['name'=>"عرض المستخدمين"]);
        Permission::create(['name'=>"عرض cnc"]);
        Permission::create(['name'=>"حذف cnc"]);
        Permission::create(['name'=>"تعديل cnc"]);
        Permission::create(['name'=>"اضافة cnc"]);
        Permission::create(['name'=>"عرض المنتجات"]);
        Permission::create(['name'=>"حذف المنتجات"]);
        Permission::create(['name'=>"تعديل المنتجات"]);
        Permission::create(['name'=>"اضافة منتج"]);
        Permission::create(['name'=>"عرض المواد الخام"]);
        Permission::create(['name'=>"حذف مادة خام"]);
        Permission::create(['name'=>"تعديل مادة خام"]);
        Permission::create(['name'=>"اضافة مادة خام"]);
        Permission::create(['name'=>"فواتير المبيعات"]);
        Permission::create(['name'=>"التنقل بين كل الفواتير المبيعات"]);
        Permission::create(['name'=>"فواتير المشتريات"]);
        Permission::create(['name'=>"التنقل بين كل فواتير المشتريات"]);
        Permission::create(['name'=>"اعطاء امر عمل"]);
        Permission::create(['name'=>"محاسب"]);

        $user = User::create([
            'name'=>"admin",
            "email"=>"admin@gmail.com",
            "password"=>Hash::make(12345678),
            "type"=>1
        ]);

        $permission = Permission::all();

        foreach ($permission as $value) {
            $user->givePermissionTo($value);
        }

    }
}
