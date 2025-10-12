<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalsTable extends Migration
{
    public function up()
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->enum('sexe', ['male', 'femelle']);
            $table->enum('type', ['poule', 'lapin']);
            $table->string('categorie');
            $table->date('date_naissance');
            $table->string('mere')->nullable(); // Ajouté
            $table->string('pere')->nullable(); // Ajouté
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('animals');
    }
}
