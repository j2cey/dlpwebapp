<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeReponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_reponses', function (Blueprint $table) {
            $table->id();

            $table->integer('code')->unique()->comment('code de la Reponse');
            $table->string('name')->nullable()->comment('nom de la Reponse');
            $table->string('msg_reponse')->nullable()->comment('Message de la Reponse');

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
        // Schema::table('type_reponses', function (Blueprint $table) {
        //     $table->dropIndex(['code']);
        // });
        Schema::dropIfExists('type_reponses');
    }
}
