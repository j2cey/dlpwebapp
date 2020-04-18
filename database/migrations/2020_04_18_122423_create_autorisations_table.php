<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutorisationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autorisations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('demandeur_id')->nullable()->comment('reference du demandeur (le cas echeant)');
            $table->foreign('demandeur_id')->references('id')->on('demandeurs')->onDelete('set null');

            $table->unsignedBigInteger('requete_id')->nullable()->comment('reference de la requete (le cas echeant)');
            $table->foreign('requete_id')->references('id')->on('requetes')->onDelete('set null');

            $table->integer('code')->nullable()->comment('Code de l autorisation');
            $table->string('msg')->nullable()->comment('Message de l autorisation');

            $table->dateTime('date_debut')->comment('marque le debut de l autorisation');
            $table->dateTime('date_fin')->nullable()->comment('marque la fin de l autorisation');

            $table->boolean('is_active')->default(false)->comment('indique si la requete est active');

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
        Schema::table('autorisations', function (Blueprint $table) {
            $table->dropForeign(['demandeur_id']);
            $table->dropForeign(['requete_id']);
        });
        Schema::dropIfExists('autorisations');
    }
}
