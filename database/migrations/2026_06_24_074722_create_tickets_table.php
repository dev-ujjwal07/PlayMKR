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
       Schema::create('tickets', function (Blueprint $table) {

    $table->id();

    $table->foreignId('deal_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('team_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('sponsor_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('name');

    $table->enum('priority', [
        'high',
        'medium',
        'low'
    ]);

    $table->date('start_date');

    $table->string('attachment')
        ->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
