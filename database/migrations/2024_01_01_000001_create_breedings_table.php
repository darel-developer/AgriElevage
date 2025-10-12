<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreedingsTable extends Migration
{
    public function up()
    {
        Schema::create('breedings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('male_id');
            $table->unsignedBigInteger('female_id');
            $table->date('date_croisement');
            $table->string('heure');
            $table->date('date_mise_bas');
            $table->timestamps();

            $table->foreign('male_id')->references('id')->on('animals')->onDelete('cascade');
            $table->foreign('female_id')->references('id')->on('animals')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('breedings');
    }
}
