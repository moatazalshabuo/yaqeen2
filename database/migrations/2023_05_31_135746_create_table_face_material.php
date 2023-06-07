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
        Schema::create('tool_face', function (Blueprint $table) {
            $table->id();
            $table->foreignId("tool_materials_id")->constrained("tool_materials")->cascadeOnDelete();
            $table->foreignId("product_faces_id")->constrained("product_faces")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_face');
    }
};
