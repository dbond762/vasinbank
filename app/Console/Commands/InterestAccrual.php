<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DepositService;

class InterestAccrual extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deposit:credit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Interest accrual of the deposits';

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
        DepositService::interest_accrual();
        return 0;
    }
}
