<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatsToBreedingsTable extends Migration
{
    public function up()
    {
        Schema::table('breedings', function (Blueprint $table) {
            $table->integer('taille_portee')->nullable();
            $table->integer('nb_morts')->nullable();
            $table->boolean('reussite')->default(true);
            $table->string('espece')->nullable();
        });
    }

    public function down()
    {
        Schema::table('breedings', function (Blueprint $table) {
            $table->dropColumn(['taille_portee', 'nb_morts', 'reussite', 'espece']);
        });
    }
}
