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
       Schema::create('invoices', function (Blueprint $table) {

    $table->id();

    $table->foreignId('deal_id')
        ->constrained('deals')
        ->cascadeOnDelete();

    $table->foreignId('sponsor_id')
        ->constrained('sponsors')
        ->cascadeOnDelete();

    $table->string('invoice_id')
        ->unique();

    $table->string('invoice_title');

    $table->decimal(
        'invoice_amount',
        12,
        2
    );

 $table->enum(
    'payment_type',
    [
        'cash',
        'online'
    ]
)->nullable();

    $table->decimal(
        'tax',
        12,
        2
    )->default(0);

    $table->decimal(
        'discount',
        12,
        2
    )->default(0);

$table->decimal(
    'total_amount',
    12,
    2
)->nullable();

    $table->enum(
        'currency',
        [
            'USD',
            'EUR',
            'GBP',
            'INR'
        ]
    );

    $table->date('invoice_date');

    $table->date('due_date');

    $table->enum(
        'payment_status',
        [
            'Pending',
            'Paid',
            'Overdue'
        ]
    )->default('Pending');

    $table->text(
        'billing_address'
    );

    $table->string(
        'contact_email'
    );

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
