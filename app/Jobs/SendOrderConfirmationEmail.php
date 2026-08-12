<?php

namespace App\Jobs;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int */
    public $tries = 3;

    /** @var int */
    public $backoff = 60;

    public function __construct(public int $orderId) {}

    public function handle(): void
    {
        $order = Order::find($this->orderId);

        if (! $order) {
            Log::warning('Order confirmation email skipped because the order no longer exists.', [
                'order_id' => $this->orderId,
            ]);

            return;
        }

        $email = trim((string) $order->email);

        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::info('Order confirmation email skipped because no valid customer email is available.', [
                'order_id' => $order->id,
            ]);

            return;
        }

        Mail::to($email)->send(new OrderConfirmation($order));
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Order confirmation email job failed.', [
            'order_id' => $this->orderId,
            'exception' => $exception->getMessage(),
        ]);
    }
}
