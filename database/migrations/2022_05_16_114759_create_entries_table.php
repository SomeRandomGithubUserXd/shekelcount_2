<?php

use App\Models\Category;
use App\Models\Service\EntryImportBank;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration
{
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class);
            $table->dateTime('performed_at');
            $table->integer('sum');
            $table->text('description');
            $table->boolean('was_recently_imported')->default(0);
            $table->foreignIdFor(EntryImportBank::class)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('entries');
    }
}
