<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration{public function up(){Schema::create('order_items',function(Blueprint $t){$t->id();$t->foreignId('order_id')->constrained()->cascadeOnDelete();$t->foreignId('painting_id')->constrained()->restrictOnDelete();$t->unsignedInteger('quantity')->default(1);$t->decimal('price',15,2);$t->decimal('subtotal',15,2);$t->timestamps();});}public function down(){Schema::dropIfExists('order_items');}};
