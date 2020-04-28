<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeDemandeEntrycodeAndIsactiveColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('type_demandes', function (Blueprint $table) {
            $table->string('entry_code', 50)->index()->comment('code d entree du type de demande');
            $table->boolean('is_active')->default(false)->index()->comment('indique si le type de demande est actif');
        });

        \DB::statement('UPDATE type_demandes SET is_active = 1');
        \DB::statement('UPDATE type_demandes SET entry_code = code');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('type_demandes', function (Blueprint $table) {
            $table->dropIndex(['entry_code']);
            $table->dropIndex(['is_active']);
        });
    }
}
