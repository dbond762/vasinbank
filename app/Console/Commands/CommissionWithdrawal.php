<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DepositService;

class CommissionWithdrawal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deposit:debit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Commission withdrawal of the deposits';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DepositService::commission_withdrawal();
        return 0;
    }
}
