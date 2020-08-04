<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->string('matchCode');
            $table->string('host');
            $table->string('otherPlayers')->default("[]");
            $table->longText('matchMoves')->default('[]');
            $table->boolean('matchStarted')->default(false);
            $table->boolean('matchOngoing')->default(false);
            $table->boolean('matchEnded')->default(false);
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
        Schema::dropIfExists('matches');
    }
}
