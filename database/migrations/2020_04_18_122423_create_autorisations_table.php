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

            // $table->unsignedBigInteger('demandeur_id')->nullable()->comment('reference du demandeur (le cas echeant)');
            // $table->foreign('demandeur_id')->references('id')->on('demandeurs')->onDelete('set null');

            $table->string('demandeur', 100)->index()->comment('Numero de telephone du demandeur');

            $table->unsignedBigInteger('requete_id')->nullable()->comment('reference de la requete (le cas echeant)');
            $table->foreign('requete_id')->references('id')->on('requetes')->onDelete('set null');

            // $table->integer('code')->nullable()->comment('Code de l autorisation');
            // $table->string('msg')->nullable()->comment('Message de l autorisation');
            $table->unsignedBigInteger('type_demande_id')->nullable()->comment('reference du type de demande');
            $table->foreign('type_demande_id')->references('id')->on('type_demandes')->onDelete('set null');

            $table->dateTime('date_debut')->index()->comment('marque le debut de l autorisation');
            $table->dateTime('date_fin')->index()->comment('marque la fin de l autorisation');

            $table->boolean('is_active')->default(false)->index()->comment('indique si la requete est active');

            // $table->index('is_active');
            // $table->index('date_fin');
            // $table->index('date_debut');

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
            $table->dropForeign(['requete_id']);

            $table->dropIndex(['demandeur']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['date_fin']);
            $table->dropIndex(['date_debut']);
        });
        Schema::dropIfExists('autorisations');
    }
}
