<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class Deposit {
    public function interest_accrual() {
       DB::transaction(function () {
           // Добавить транзакции
           $select = DB::table('deposit')
               ->select([
                   'id',
                   'amount',
                   DB::raw("'credit'"),
                   DB::raw('(`amount` * (`interest` / 100))'),
                   DB::raw("'interest_accrual'"),
                   DB::raw('NOW()'),
                   DB::raw('NOW()'),
               ])
               ->where(DB::raw('DAY(`created_at`)'), '=', DB::raw('DAY(NOW())'));
           $bindings = $select->getBindings();
           $insert = 'INSERT INTO `deposit_history`(`deposit_id`, `amount_before`, `entry`, `transaction_amount`, `transaction_type`, `created_at`, `updated_at`) '
               . $select->toSql();
           DB::insert($insert, $bindings);

           // Обновить счета по депозитам
           \App\Deposit::where(DB::raw('DAY(`created_at`)'), '=', DB::raw('DAY(NOW())'))
               ->update([
                   'amount'     => DB::raw('`amount` + (`amount` * (`interest` / 100))'),
                   'updated_at' => DB::raw('NOW()'),
               ]);
       });
    }

    public function commission_withdrawal() {
        DB::transaction(function () {
            // Создадим временную таблицу с комиссиями
            DB::insert(DB::raw('CREATE TEMPORARY TABLE `deposit_commission` LIKE `deposit_history`'));

            // Добавляем туда стандартную комиссию для всех депозитов
            $select = DB::table('deposit')
                ->select([
                    'id',
                    'amount',
                    DB::raw("'debit'"),
                    DB::raw(
                        "CASE "
                        . "    WHEN `amount` < (1000*1e5) THEN IF((`amount` * 0.05) < (50*1e5), 50*1e5, `amount` * 0.05) "
                        . "    WHEN `amount` >= (1000*1e5) AND `amount` < (10000*1e5) THEN `amount` * 0.06 "
                        . "    WHEN `amount` >= (10000*1e5) THEN IF((`amount` * 0.07) > (5000*1e5), 5000*1e5, `amount` * 0.07) "
                        . "END"
                    ),
                    DB::raw("'commission_withdrawal'"),
                    DB::raw('NOW()'),
                    DB::raw('NOW()'),
                ]);
            $bindings = $select->getBindings();
            $insert = 'INSERT INTO `deposit_commission`(`deposit_id`, `amount_before`, `entry`, `transaction_amount`, `transaction_type`, `created_at`, `updated_at`) '
                      . $select->toSql();
            DB::insert($insert, $bindings);

            // Комиссия взымается частично, если депозит заведен меньше месяца назад
            DB::table('deposit_commission AS dc')
                ->leftJoin('deposit AS d', 'd.id', '=', 'dc.deposit_id')
                ->where(DB::raw('(TO_DAYS(NOW()) - TO_DAYS(`d`.`created_at`)) < (TO_DAYS(NOW()) - TO_DAYS(MONTH_AGO()))'))
                ->update([
                    'dc.transaction_amount' => DB::raw('`dc`.`transaction_amount` * (TO_DAYS(NOW()) - TO_DAYS(`d`.`created_at`)) / (TO_DAYS(NOW()) - TO_DAYS(MONTH_AGO()))'),
                ]);

            // Переносим комиссии в таблицу истории
            $select = DB::table('deposit_commission');
            $bindings = $select->getBindings();
            $insert = 'INSERT INTO `deposit_history` ' . $select->toSql();
            DB::insert($insert, $bindings);

            // Обновить счета по депозитам
            DB::table('deposit AS d')
              ->leftJoin('deposit_commission AS dc', 'd.id', '=', 'dc.deposit_id')
              ->update([
                  'd.amount'     => DB::raw('`d`.`amount` - `dc`.`transaction_amount`'),
                  'd.updated_at' => DB::raw('NOW()'),
              ]);

            // Удаляем временную таблицу
            DB::unprepared(DB::raw('DROP TEMPORARY TABLE `deposit_commission`'));
        });
    }
}
