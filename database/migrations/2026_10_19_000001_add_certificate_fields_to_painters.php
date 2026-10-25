<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('painters', function (Blueprint $table) {
            $table->string('certificate_title')->nullable();
            $table->string('certificate_image')->nullable();
            $table->text('certificate_description')->nullable();
            $table->text('exhibition_experience')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('painters', function (Blueprint $table) {
            $table->dropColumn([
                'certificate_title',
                'certificate_image',
                'certificate_description',
                'exhibition_experience',
            ]);
        });
    }
};
