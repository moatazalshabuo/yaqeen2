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
        Schema::create('item_facea_material', function (Blueprint $table) {
            $table->id();
            $table->foreignId("material_id")->constrained("faces_materials")->cascadeOnDelete();
            $table->foreignId("item_face_id")->constrained("item_face")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_facea_material');
    }
};
