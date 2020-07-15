<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $table = 'deposit';

    public function history() {
        return $this->hasMany('App\DepositHistory');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function currency() {
        return $this->belongsTo('App\Currency');
    }
}
