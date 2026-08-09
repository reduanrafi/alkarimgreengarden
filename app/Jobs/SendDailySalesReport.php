<?php

namespace App\Jobs;

use App\Mail\DailySalesReport;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendDailySalesReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int */
    public $tries = 3;

    /** @var int */
    public $backoff = 60;

    /**
     * @param array{total_orders: int, completed_orders: int, pending_orders: int, cancelled_orders: int, total_sales: float} $summary
     */
    public function __construct(public array $summary, public string $reportDate) {}

    public function handle(): void
    {
        $email = Setting::get('admin_email');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = User::query()
                ->where(fn ($query) => $query->where('is_admin', true)->orWhere('role', 'admin'))
                ->orderByDesc('is_admin')
                ->value('email');
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::warning('Daily sales report email skipped because no valid admin email is available.');

            return;
        }

        Mail::to($email)->send(new DailySalesReport($this->summary, $this->reportDate));
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Daily sales report job failed.', [
            'report_date' => $this->reportDate,
            'exception' => $exception->getMessage(),
        ]);
    }
}
