<?php

namespace App\Providers;

use App\Interfaces\PaymobServiceInterface;
use App\Services\PaymobService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Paymob payment gateway registraion
        $this->app->bind(PaymobServiceInterface::class, PaymobService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // انا هنا بمسح السيشن بتاع الخصم لو اليوزر خرج من الموقع ومكملش الطلب
        //  لما يرجع تاني هتكون السيشن انتهت وبالتالي اجمالي للطلب هيرجع لأصله قبل الخصم

        if (!Session::has('user_active')) { //بنشوف لو الجلسه نشطه ولا لأ 
            Session::forget('applied_coupon');
            Session::forget('final_total'); // لو مش نشطه بنمسح الخصم
        }


        //
        View::composer(['admin.orders.orders_summary', 'admin.dashboard', 'admin.orders.accepted_orders', 'admin.orders.orders'], function ($view) {
            $user = Auth::user();

            $view->with('orders', Order::all())
                ->with('LimitOrders', Order::where('order_status', 'pending')->orderBy('created_at', 'desc')->limit(10)->get())
                ->with('paid_orders', Order::where('payment_status', 'paid')->get())
                ->with('OrdersCount', Order::where('payment_status', 'paid')->count())
                ->with('PendingOrders', Order::where('order_status', 'pending')->count());
        });
    }
}
