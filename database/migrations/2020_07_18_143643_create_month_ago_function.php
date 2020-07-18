<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class CreateMonthAgoFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        DB::unprepared(DB::raw('delimiter ||'));
        DB::unprepared(DB::raw(
            "create function month_ago() "
            . "returns date "
            . "begin "
            . "    return if(month(now()) > 1, "
            . "       concat(year(now()), '-', month(now()) - 1, '-', day(now())), "
            . "       concat(year(now()) - 1, '-12-', day(now())) "
            . "    ); "
            . "end; "
//            . "|| "
        ));
//        DB::unprepared(DB::raw('delimiter ;'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared(DB::raw('DROP FUNCTION month_ago'));
    }
}
