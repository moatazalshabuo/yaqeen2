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
        Schema::create('purchasesitems', function (Blueprint $table) {
            $table->id();
            // $table->foreignId("rawid")->constrained("rawmaterials");
            $table->foreignId('purchases_id')->constrained("purchasesbills")->onDelete('cascade');
            $table->double("quantity",15, 4);
            $table->double("descont",15, 4)->default(0);
            $table->double("totel",15, 4)->nullable();
            $table->double('price',15,4);
            $table->foreignId("rawmati")->constrained("rawmaterials");
            $table->foreignId("user_id")->constrained("users");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchasesitems');
    }
};
