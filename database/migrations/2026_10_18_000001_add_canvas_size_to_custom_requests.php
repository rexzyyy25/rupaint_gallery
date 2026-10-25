<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_requests', function (Blueprint $table) {
            $table->string('canvas_size', 30)->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('custom_requests', function (Blueprint $table) {
            $table->dropColumn('canvas_size');
        });
    }
};
