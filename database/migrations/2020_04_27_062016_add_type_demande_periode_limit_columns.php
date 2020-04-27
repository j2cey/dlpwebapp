<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeDemandePeriodeLimitColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('type_demandes', function (Blueprint $table) {
            $table->integer('periode_debut_heure')->default(6);
            $table->integer('periode_debut_minute')->default(30);
            $table->integer('periode_fin_heure')->default(19);
            $table->integer('periode_fin_minute')->default(30);
        });

        \DB::statement('UPDATE type_demandes SET periode_debut_heure = 6');
        \DB::statement('UPDATE type_demandes SET periode_debut_minute = 30');
        \DB::statement('UPDATE type_demandes SET periode_fin_heure = 19');
        \DB::statement('UPDATE type_demandes SET periode_fin_minute = 30');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('type_demandes', function (Blueprint $table) {
            //
        });
    }
}
