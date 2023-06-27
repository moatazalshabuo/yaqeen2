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
        Schema::create('item_face', function (Blueprint $table) {
            $table->engine ="InnoDB";
            $table->id();
            $table->foreignId("face_id")->constrained("product_faces")->cascadeOnDelete();
            $table->foreignId("Item_id")->constrained("sales_items")->cascadeOnDelete();
            $table->double("quantity",15,4);
            $table->double("height",15,4)->nullable();
            $table->double("width",15,4)->nullable();
            $table->integer("count");
            $table->double("price",15,4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_facea');
    }
};
