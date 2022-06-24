<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('mutator_options', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description');
            $table->json('params')->default(new Expression('(JSON_ARRAY())'));
            $table->string('class')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mutator_options');
    }
};
