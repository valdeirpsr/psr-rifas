<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class ClearExpiredOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-expired-orders-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove dados de pedidos e pagamentos expirados e libera os números reservados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Order::where('expire_at', '<=', now()->subMinutes(30))
            ->where('status', Order::STATUS_RESERVED)
            ->delete();
    }
}
