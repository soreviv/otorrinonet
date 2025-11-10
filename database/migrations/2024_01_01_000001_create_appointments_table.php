<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the `appointments` table with columns for client contact, consultation type, motive, and scheduled date/time.
     *
     * The table includes: `id`, `name`, `phone`, `tipo_consulta`, `motivo`, `date`, `time`, `created_at`, and `updated_at`.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('tipo_consulta');
            $table->text('motivo');
            $table->date('date');
            $table->time('time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};