<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntryImportBanksTable extends Migration
{
    public function up()
    {
        Schema::create('entry_import_banks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('parser')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('entry_import_banks');
    }
}
