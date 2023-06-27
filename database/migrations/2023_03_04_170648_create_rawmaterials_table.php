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
        Schema::create('rawmaterials', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('material_name',30);
            $table->integer('material_type');
            $table->double('quantity',12,4);
            $table->double("hiegth",12,4)->default(1);
            $table->double("width",12,4)->default(1);
            $table->double('price',12,4);
            $table->double('pace_price',12,4);
            $table->string('created_by',30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rawmaterials');
    }
};
