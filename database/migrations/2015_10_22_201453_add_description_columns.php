<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('descriptions', function (Blueprint $table) {
            $table->integer('vote_ups')->default(0)->after('body');
            $table->integer('vote_downs')->default(0)->after('vote_ups');
            $table->decimal('score', 8, 2)->default(0.00)->after('vote_downs');
            $table->timestamp('preferred_since')->nullable()->after('score');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('descriptions', function (Blueprint $table) {
            $table->dropColumn(['vote_ups', 'vote_downs', 'score', 'preferred_since']);
        });
    }
}
