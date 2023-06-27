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
        Schema::create('exchange_receipt', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->integer("bill_id")->nullable();
            $table->string('desc')->nullable();
            $table->double("price",15, 2);
            $table->integer("type");
            $table->string("created_by");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchanges');
    }
};
