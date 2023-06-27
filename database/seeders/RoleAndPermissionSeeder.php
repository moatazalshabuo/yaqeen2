<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
    }
}
