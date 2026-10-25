<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration{public function up(){Schema::create('payments',function(Blueprint $t){$t->id();$t->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();$t->string('payment_method')->default('pending');$t->enum('payment_status',['pending','paid','failed'])->default('pending');$t->string('payment_proof')->nullable();$t->timestamp('paid_at')->nullable();$t->timestamps();});}public function down(){Schema::dropIfExists('payments');}};
