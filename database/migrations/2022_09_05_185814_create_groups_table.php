<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('firebase_chat_id', 50);
            $table->string('name', 300);
            $table->string('description', 9999);
            $table->float('latitude',17,14);
            $table->float('longitude',17,14);// Example from Google Maps: 52.40117371024606, 16.91741090208518
            $table->date('event_date');
            $table->time('event_time',0);
            $table->integer('max_members');
            $table->unsignedBigInteger('owner');
            $table->foreign('owner')->references('id')->on('users');
            $table->unsignedBigInteger('popularity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
};
