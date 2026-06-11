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
    Schema::create('deliverables', function (Blueprint $table) {

        $table->id();

        $table->foreignId('deal_id')
            ->constrained('deals')
            ->cascadeOnDelete();

        $table->foreignId('deliver_type_id')
            ->constrained('deliver_types')
            ->cascadeOnDelete();

        $table->string('title');

        $table->text('description')->nullable();

        $table->integer('quantity');

        $table->string('attachment')->nullable();

        $table->foreignId('sponsor_id')
            ->constrained('sponsors')
            ->cascadeOnDelete();

        $table->unsignedBigInteger('assigned_to')
            ->nullable();
        $table->enum(
          'status',
        [
        'pending',
        'in_progress',
        'completed'
            ]
         )->default('pending');

        $table->timestamp('status_updated_at')
            ->nullable();

        $table->date('distribution_date')
            ->nullable();

        $table->string('priority');

        $table->date('start_date');

        $table->date('due_date');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliverables');
    }
};
