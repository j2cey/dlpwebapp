<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandeursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demandeurs', function (Blueprint $table) {
            $table->id();
            $table->string('phonenum', 100)->comment('Numero de telephone du demandeur');
            $table->boolean('is_requesting')->default(false)->comment('indique le demandeur a une requete en cours');
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
        Schema::dropIfExists('demandeurs');
    }
}
