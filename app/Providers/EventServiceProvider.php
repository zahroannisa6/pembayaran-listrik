<?php

namespace App\Providers;

use App\Observers\{
    BillObserver, LevelObserver, LevelPermissionObserver, 
    PaymentObserver, PaymentDetailObserver, PermissionObserver,
    PlnCustomerObserver, UsageObserver, UserObserver,
    TariffObserver
};
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\{
    Payment, PaymentDetail, User, 
    Bill, Usage, PlnCustomer, Tariff, 
    Permission, LevelPermission, Level
};

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Bill::observe(BillObserver::class);
        Level::observe(LevelObserver::class);
        LevelPermission::observe(LevelPermissionObserver::class);
        Payment::observe(PaymentObserver::class);
        PaymentDetail::observe(PaymentDetailObserver::class);
        Permission::observe(PermissionObserver::class);
        PlnCustomer::observe(PlnCustomerObserver::class);
        Tariff::observe(TariffObserver::class);
        Usage::observe(UsageObserver::class);
        User::observe(UserObserver::class);
    }
}
