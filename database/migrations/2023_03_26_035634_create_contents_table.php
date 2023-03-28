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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pack_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->text('description')->default("");
            $table->string('image_path')->nullable();
            $table->date('release_date');
            $table->date('eu_release_date')->nullable();
            $table->date('console_release_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
