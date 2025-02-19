<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Order;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        View::composer(['admin.orders.orders_summary', 'admin.dashboard', 'admin.orders.accepted_orders', 'admin.orders.orders'], function ($view) {
            $view->with('orders', Order::all())
                ->with('LimitOrders', Order::where('order_status', 'pending')->orderBy('created_at', 'desc')->limit(10)->get())
                ->with('paid_orders', Order::where('payment_status', 'paid')->get())
                ->with('OrdersCount', Order::where('payment_status', 'paid')->count())
                ->with('PendingOrders', Order::where('order_status', 'pending')->count());
        });
    }
}
