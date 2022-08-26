<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->index();
            $table->foreignId('collection_id')->constrained();
            $table->longText('content')->nullable();
            $table->json('data');
            $table->dateTime('published_at')->nullable()->index();
            $table->string('status')->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entries');
    }
}
