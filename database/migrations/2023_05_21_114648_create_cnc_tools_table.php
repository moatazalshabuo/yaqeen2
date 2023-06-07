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
        Schema::create('cnc_tools', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string("name",30);
            $table->boolean("status")->default(true);
            $table->string("created_by",30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cnc_tools');
    }
};
