<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {

            $table->text('internal_team_description')
                ->nullable()
                ->after('number_of_tickets');

        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {

            $table->dropColumn(
                'internal_team_description'
            );

        });
    }
};