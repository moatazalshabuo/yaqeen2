<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salary_mounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId("salary_users")->constrained("salary_users");
            $table->string("mount");
            $table->string("user_name");
            $table->float("salary");
            $table->float("plus");
            $table->float("dept_on");
            $table->float("still");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_mounts');
    }
};
