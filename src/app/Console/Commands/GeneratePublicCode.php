<?php

namespace App\Console\Commands;

use App\Api\V1\Controllers\Qsc1;
use App\Models\Transaction;
use Illuminate\Console\Command;

class GeneratePublicCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'public-code:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Public Code for existing Transactions';

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
     * @return mixed
     */
    public function handle()
    {
        $transactions = Transaction::whereNull('public_code')->get();

        foreach ($transactions as $transaction){
            $transaction->public_code = Qsc1::generatePublicCode();
            $transaction->save();
        }
    }
}
