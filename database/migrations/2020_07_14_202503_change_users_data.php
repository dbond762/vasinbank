<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUsersData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('id_number')->after('id');
            $table->dropColumn('name');
            $table->string('first_name', 50)->after('id_number');
            $table->string('last_name', 50)->after('first_name');
            $table->set('sex', ['male', 'female'])->default('male');
            $table->date('birthday');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id_number');
            $table->string('name')->after('id');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('sex');
            $table->dropColumn('birthday');
        });
    }
}
