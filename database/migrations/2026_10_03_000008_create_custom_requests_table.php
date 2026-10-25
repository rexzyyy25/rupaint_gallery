<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration{public function up(){Schema::create('custom_requests',function(Blueprint $t){$t->id();$t->foreignId('order_id')->constrained()->cascadeOnDelete();$t->text('description')->nullable();$t->string('reference_image')->nullable();$t->enum('status',['pending','accepted','processing','completed','rejected'])->default('pending');$t->timestamps();});}public function down(){Schema::dropIfExists('custom_requests');}};
