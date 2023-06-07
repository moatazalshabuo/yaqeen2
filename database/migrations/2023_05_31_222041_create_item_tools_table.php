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
        Schema::create('item_tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId("tool_materials_id")->constrained("tool_materials")->cascadeOnDelete();
            $table->double("price",15,4);
            $table->double('quantity',15,4);
            $table->foreignId("salesitem_id")->constrained("sales_items")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_tools');
    }
};
