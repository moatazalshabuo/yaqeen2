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
        Schema::create('product_faces', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string("title",30);
            $table->foreignId("product_id")->constrained("products")->onDelete('cascade');
            $table->double("price",15, 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_faces');
    }
};
