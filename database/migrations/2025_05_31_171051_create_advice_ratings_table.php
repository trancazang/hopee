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
        Schema::create('advice_ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('advice_request_id')
                  ->constrained('advice_requests')
                  ->cascadeOnDelete();

            $table->unsignedTinyInteger('rating');   // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advice_ratings');
    }
};
