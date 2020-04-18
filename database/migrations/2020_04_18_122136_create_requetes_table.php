<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequetesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requetes', function (Blueprint $table) {
            $table->id();

            $table->string('reqtype', 100)->comment('Parametre reqtype la requete');
            $table->string('phonenum', 100)->comment('Parametre phonenum la requete');

            $table->unsignedBigInteger('demandeur_id')->nullable()->comment('reference du demandeur (le cas echeant)');
            $table->foreign('demandeur_id')->references('id')->on('demandeurs')->onDelete('set null');
            
            $table->integer('req_code')->nullable()->comment('Code de la requete - apres analyse');
            $table->string('msg')->nullable()->comment('Message de la reponse');

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
        Schema::table('requetes', function (Blueprint $table) {
            $table->dropForeign(['demandeur_id']);
        });
        Schema::dropIfExists('requetes');
    }
}