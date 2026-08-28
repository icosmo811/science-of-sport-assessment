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
        Schema::create('event_options', function (Blueprint $table) {
            $table->id();

            $table->foreignId('entry_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('category');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->unsignedSmallInteger('golfer_count')->nullable();
            $table->text('description')->nullable();
            $table->json('benefits')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_options');
    }
};
