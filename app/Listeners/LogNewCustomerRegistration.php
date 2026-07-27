<?php

namespace App\Listeners;

use App\Services\NotificationService;

class LogNewCustomerRegistration
{
    public function handle(object $event): void
    {
        $user = $event->user;
        app(NotificationService::class)->create(
            'new_customer',
            'Customer',
            "New Customer: {$user->name}",
            "{$user->name} ({$user->email}) has registered as a new customer."
        );
    }
}
