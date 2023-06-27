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
        Schema::create('purchasesbills', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('created_by',30);
            $table->double("totel",15, 4)->default(0);
            $table->double("sincere",15, 4)->default(0);
            $table->double("Residual",15, 4)->default(0);
            $table->boolean("status")->default(1);
            $table->integer("custom")->nullable();
            $table->boolean("receipt")->default(1);
            $table->string("note")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchasesbills');
    }
};
