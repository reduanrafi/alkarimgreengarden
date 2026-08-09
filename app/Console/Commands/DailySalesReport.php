<?php

namespace App\Console\Commands;

use App\Jobs\SendDailySalesReport;
use App\Models\Order;
use Illuminate\Console\Command;
use Throwable;

class DailySalesReport extends Command
{
    protected $signature = 'garden:daily-report';

    protected $description = 'Generate and queue the daily sales report for the administrator.';

    public function handle(): int
    {
        $reportDate = today();
        $orders = Order::query()->whereDate('ordered_at', $reportDate);

        $summary = [
            'total_orders' => (clone $orders)->count(),
            'completed_orders' => (clone $orders)->whereIn('status', ['delivered', 'completed'])->count(),
            'pending_orders' => (clone $orders)->where('status', 'pending')->count(),
            'cancelled_orders' => (clone $orders)->whereIn('status', ['cancelled', 'returned'])->count(),
            'total_sales' => (float) (clone $orders)
                ->whereIn('status', ['delivered', 'completed'])
                ->sum('grand_total'),
        ];

        $this->info('Daily sales report for ' . $reportDate->toFormattedDateString());
        $this->table(['Metric', 'Value'], [
            ['Total orders today', $summary['total_orders']],
            ['Completed orders', $summary['completed_orders']],
            ['Pending orders', $summary['pending_orders']],
            ['Cancelled orders', $summary['cancelled_orders']],
            ['Total sales today', formatPrice($summary['total_sales'])],
        ]);

        try {
            SendDailySalesReport::dispatch($summary, $reportDate->toDateString());
        } catch (Throwable $exception) {
            report($exception);
            $this->error('The daily sales report could not be queued.');

            return self::FAILURE;
        }

        $this->info('Daily sales report email has been queued.');

        return self::SUCCESS;
    }
}
