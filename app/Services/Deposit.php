<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class Deposit {
    public function interest_accrual() {
       DB::transaction(function () {
           // Добавить транзакции
           DB::insert(
               "insert into `deposit_history`(`deposit_id`, `amount_before`, `entry`, `transaction_amount`, `transaction_type`, `created_at`, `updated_at`) "
               . "SELECT `id`, `amount`, 'credit', (`amount` * (`interest` / 100)), 'interest_accrual', NOW(), NOW() "
               . "FROM deposit "
               . "WHERE DAY(`created_at`) = DAY(NOW())"
           );

           // Обновить счета по депозитам
           DB::update(
               "UPDATE `deposit` "
               . "SET `amount` = `amount` + (`amount` * (`interest` / 100)), `updated_at` = NOW() "
               . "WHERE DAY(`created_at`) = DAY(NOW());"
           );
       });
    }

    public function commission_withdrawal() {

    }
}
