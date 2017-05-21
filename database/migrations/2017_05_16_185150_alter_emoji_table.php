<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEmojiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emoji', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->string('symbol')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emoji', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->string('symbol')->nullable(false)->change();
        });
    }
}
