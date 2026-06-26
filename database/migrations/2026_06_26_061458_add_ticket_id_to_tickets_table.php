<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('tickets', function (Blueprint $table) {

        $table->integer('number_of_tickets')
              ->default(1)
              ->after('ticket_id');

    });
}

public function down(): void
{
    Schema::table('tickets', function (Blueprint $table) {

        $table->dropColumn('number_of_tickets');

    });
}
};
