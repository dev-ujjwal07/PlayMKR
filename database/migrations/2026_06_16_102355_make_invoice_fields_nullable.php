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
    Schema::table('invoices', function (Blueprint $table) {

        $table->string(
            'invoice_title'
        )->nullable()->change();

        $table->enum(
            'currency',
            [
                'USD',
                'EUR',
                'GBP',
                'INR'
            ]
        )->nullable()->change();

        $table->text(
            'billing_address'
        )->nullable()->change();

        $table->string(
            'contact_email'
        )->nullable()->change();

        $table->decimal(
            'tax',
            12,
            2
        )->nullable()->change();

        $table->decimal(
            'discount',
            12,
            2
        )->nullable()->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
