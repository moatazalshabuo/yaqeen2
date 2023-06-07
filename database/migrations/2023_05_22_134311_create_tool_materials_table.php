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
        Schema::create('tool_materials', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('title',30);
            $table->foreignId("material")->constrained("rawmaterials")->onDelete('cascade');
            $table->foreignId("tool")->constrained("cnc_tools")->onDelete('cascade');
            $table->double("price")->default(0);
            $table->boolean("type")->default(0);
            $table->boolean("status")->default(1);
            $table->string("created_by",30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_materials');
    }
};
