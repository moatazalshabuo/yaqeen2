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
        Schema::create('faces_materials', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId("face_id")->constrained("product_faces")->onDelete('cascade');
            $table->foreignId("material_id")->constrained("rawmaterials")->onDelete('cascade');
            $table->double('quantity',15,4)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faces_materials');
    }
};
