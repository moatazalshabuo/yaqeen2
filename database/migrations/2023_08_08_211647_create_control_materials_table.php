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
        Schema::create('control_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId("raw_id")->constrained("rawmaterials")->cascadeOnDelete();
            $table->float('quantity');
            $table->boolean("type");
            $table->string("created_by");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_materials');
    }
};
