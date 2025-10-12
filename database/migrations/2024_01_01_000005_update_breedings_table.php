<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBreedingsTable extends Migration
{
    public function up()
    {
        Schema::table('breedings', function (Blueprint $table) {
            $table->date('date_croisement')->nullable()->change();
            $table->string('heure')->nullable()->change();
            if (!Schema::hasColumn('breedings', 'taille_portee')) {
                $table->integer('taille_portee')->nullable();
            }
            if (!Schema::hasColumn('breedings', 'nb_morts')) {
                $table->integer('nb_morts')->nullable();
            }
            if (!Schema::hasColumn('breedings', 'reussite')) {
                $table->boolean('reussite')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('breedings', function (Blueprint $table) {
            $table->date('date_croisement')->nullable(false)->change();
            $table->string('heure')->nullable(false)->change();
            // Les suppressions de colonnes sont optionnelles selon ton historique
        });
    }
}
