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
    Schema::create('sponsor_applications', function ($table) {

        $table->id();

        $table->string('name');

        $table->string('email')->unique();

        $table->string('contact_number', 20);

        $table->string('website_url');

        $table->string('industry');

        $table->text('address');

        $table->enum('status', [
            'pending',
            'approved',
            'rejected'
        ])->default('pending');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsor_applications');
    }
};

