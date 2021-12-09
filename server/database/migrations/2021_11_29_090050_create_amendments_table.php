<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmendmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amendments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('document_id');
            $table->string('ref', 10)->unique();
            $table->integer('num');
            $table->string('article');
            $table->string('title');
            $table->enum('type', ['addition', 'deletion', 'modification'])->default('modification');
            $table->enum('status', ['pending', 'approved', 'compromise', 'denied'])->default('pending');
            $table->text('original')->nullable();
            $table->text('amended')->nullable();
            $table->text('compromise')->nullable();
            $table->text('justification');
            $table->string('registered_by_assembly')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('document_id')->references('id')->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amendments');
    }
}
