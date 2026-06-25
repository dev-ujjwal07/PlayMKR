<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'deliverables',
            function (Blueprint $table) {

                $table->string('name')
                    ->nullable()
                    ->after('title');

                $table->foreignId('team_id')
                    ->nullable()
                    ->constrained('teams')
                    ->nullOnDelete();

                $table->string('attachment')
                    ->nullable()
                    ->after('team_id');
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'deliverables',
            function (Blueprint $table) {

                $table->dropForeign(
                    ['team_id']
                );

                $table->dropColumn([
                    'name',
                    'team_id',
                    'attachment'
                ]);
            }
        );
    }
};