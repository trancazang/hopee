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
        Schema::create('moderator_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('moderator_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->date('slot_date'); // ngày cố định (ví dụ 2025-06-07)
            $table->time('slot_time'); // giờ cố định (ví dụ 08:30:00)
         
            $table->boolean('is_available')->default(true);

            // mỗi moderator không được trùng slot
            $table->unique(['moderator_id','slot_start','slot_end']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moderator_schedules');
    }
};
