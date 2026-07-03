<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'users',
            function (Blueprint $table) {

                $table->string('number')
                    ->nullable()
                    ->after('email');

                $table->string('profile')
                    ->nullable()
                    ->after('number');
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'users',
            function (Blueprint $table) {

                $table->dropColumn([
                    'number',
                    'profile'
                ]);
            }
        );
    }
};