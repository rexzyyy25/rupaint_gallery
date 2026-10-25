<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->unique()->after('id')->constrained()->cascadeOnDelete();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('gateway_order_id')->nullable()->unique('payments_gateway_order_id_unique')->after('order_id');
            $table->string('snap_token')->nullable();
            $table->text('checkout_url')->nullable();
            $table->json('gateway_response')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique('payments_gateway_order_id_unique');
            $table->dropColumn(['gateway_order_id', 'snap_token', 'checkout_url', 'gateway_response']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropUnique('carts_user_id_unique');
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
