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
            //$table->string('reqtype_name', 100)->comment('Nom du Type de requete');

            $table->unsignedBigInteger('type_demande_id')->nullable()->comment('reference du type de demande');
            $table->foreign('type_demande_id')->references('id')->on('type_demandes')->onDelete('set null');

            //$table->integer('resp_code')->nullable()->comment('Code de la reponse a la requete - apres traitement');
            //$table->string('msg')->nullable()->comment('Message de la reponse');
            $table->unsignedBigInteger('type_reponse_id')->nullable()->comment('reference du type de reponse');
            $table->foreign('type_reponse_id')->references('id')->on('type_reponses')->onDelete('set null');

            $table->dateTime('date_start')->comment('marque le debut de la requete');
            $table->dateTime('date_end')->nullable()->comment('marque la fin de la requete');
            $table->bigInteger('duree_traitement_milli')->nullable()->comment('duree de traitement en milisecondes');
            $table->bigInteger('duree_traitement_micro')->nullable()->comment('duree de traitement en microsecondes');

            // $table->unsignedBigInteger('autorisation_id')->nullable()->comment('reference de l autorisation accordee (le cas echeant)');
            // $table->foreign('autorisation_id')->references('id')->on('autorisations')->onDelete('set null');

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
            $table->dropForeign(['type_demande_id']);
            $table->dropForeign(['type_reponse_id']);
            // $table->dropForeign(['autorisation_id']);
        });
        Schema::dropIfExists('requetes');
    }
}
