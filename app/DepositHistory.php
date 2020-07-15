<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepositHistory extends Model
{
    protected $table = 'deposit_history';

    protected $primaryKey = ['deposit_id', 'created_at'];

    public $incrementing = false;
}
