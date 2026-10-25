<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration{public function up(){Schema::create('painters',function(Blueprint $t){$t->id();$t->string('name');$t->string('email')->nullable();$t->string('phone')->nullable();$t->text('bio')->nullable();$t->string('photo')->nullable();$t->timestamps();});}public function down(){Schema::dropIfExists('painters');}};
