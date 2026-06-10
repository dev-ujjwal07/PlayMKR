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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sponsor_id')
                ->constrained('sponsors')
                ->cascadeOnDelete();

            $table->string('deal_title');

            $table->text('deal_description');

            $table->enum('status', [
                'active',
                'pending',
                'completed'
            ])->default('pending');

            $table->foreignId('deal_type_id')
                ->constrained('deal_types')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};