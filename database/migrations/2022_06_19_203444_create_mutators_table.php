<?php

use App\Models\MutatorOption;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('mutators', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('name');
            $table->json('arguments')->default(new Expression('(JSON_ARRAY())'));
            $table->foreignIdFor(MutatorOption::class);
            $table->unique(['user_id', 'name']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mutators');
    }
};
