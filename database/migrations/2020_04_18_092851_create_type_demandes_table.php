<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeDemandesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_demandes', function (Blueprint $table) {
            $table->id();

            $table->string('code', 50)->index()->comment('code de la Demande');
            $table->string('name')->nullable()->comment('nom de la Demande');
            $table->integer('validite_heure')->nullable()->comment('validite en heure');
            $table->integer('plafond_hebdo')->nullable()->comment('plafond hebdo du nombre d autorisation de ce type');
            $table->string('msg_succes')->nullable()->comment('Message Succes de la Demande');
            $table->string('msg_consultation')->nullable()->comment('Message Consultation de la Demande');

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
        Schema::table('type_demandes', function (Blueprint $table) {
            $table->dropIndex(['code']);
        });
        Schema::dropIfExists('type_demandes');
    }
}
