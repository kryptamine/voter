<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('votes', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('hash', 500);
            $table->unsignedBigInteger('answer_id');
            $table->unsignedBigInteger('poll_id');
            $table->timestamps();

            $table->foreign('answer_id')
                ->references('id')
                ->on('answers')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('poll_id')
                ->references('id')
                ->on('polls')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
}
