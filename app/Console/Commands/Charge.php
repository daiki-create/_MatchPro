<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Charge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'charge:exe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '一日に一回行われる課金処理';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->cardService = app()->make('App\Services\CardService');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if($customer = $this->cardService->charge() )
        {
            $this->info('決済対象いました');
            return;
        }
        $this->info('決済対象いませんでした');
    }
}
