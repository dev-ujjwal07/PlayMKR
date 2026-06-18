<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::table('deals', function (Blueprint $table) {

        $table->decimal(
            'total_deal_value',
            12,
            2
        )->after('status');

    });
}

public function down(): void
{
    Schema::table('deals', function (Blueprint $table) {

        $table->dropColumn(
            'total_deal_value'
        );

    });
}
};
