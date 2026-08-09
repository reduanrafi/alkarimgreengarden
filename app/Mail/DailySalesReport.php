<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailySalesReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param array{total_orders: int, completed_orders: int, pending_orders: int, cancelled_orders: int, total_sales: float} $summary
     */
    public function __construct(public array $summary, public string $reportDate) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Sales Report - ' . $this->reportDate,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-sales-report',
        );
    }
}
