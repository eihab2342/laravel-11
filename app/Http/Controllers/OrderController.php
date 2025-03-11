<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderNotification;
use Illuminate\Notifications\DatabaseNotification;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        $LimitOrders = Order::where('order_status', 'pending')->orderBy('created_at', 'desc')->limit(10)->get();
        $OrdersCount = Order::where('payment_status', 'paid')->count();
        $PendingOrders = Order::where('order_status', 'pending')->count();
        // جلب الطلبات المدفوعة فقط
        $paid_orders = Order::where('payment_status', 'paid')->get();

        // تمرير البيانات إلى كل من الواجهات المطلوبة
        return view('admin.orders.orders_summary', compact('orders'))
            ->with('dashboard', view('admin.dashboard', compact('LimitOrders', 'OrdersCount', 'PendingOrders')))
            ->with('orders', view('admin.orders.order', compact('orders')));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // 
        $order = Order::with('items')->findOrFail($id);
        return view('admin.orders.order', compact(['order']));
        $notification = DatabaseNotification::find($notificationID);
        // عشان نخلى الاشعار مقروء
        if ($notification && !$notification->read_at) {
            $notification->markAsRead();
        }
    }
    /**
     * Display the specified resource.
     */
    public function showID($id, $notificationID)
    {
        $notification = DatabaseNotification::find($notificationID);
        // عشان نخلى الاشعار مقروء
        if ($notification && !$notification->read_at) {
            $notification->markAsRead();
        }


        $order = Order::with('items')->findOrFail($id);
        return view('admin.orders.order', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }


    // 
    public function updateOrderStatus(Request $request, $id)
    {
        // البحث عن الطلب باستخدام الـ ID
        $order = Order::findOrFail($id);

        // تحديث حالة الطلب بناءً على البيانات المرسلة
        $order->order_status = $request->status;
        $order->save();

        // إرجاع استجابة JSON لتأكيد التحديث
        return response()->json(['success' => true]);
    }

    public function UserOrders()
    {
        $orders = Order::where('user_id', Auth::id())->with('items')->get();
        return view('user.User-Orders', compact('orders'));
    }

    // public function UserOrders()
    // {
    //     $orders = Order::where('user_id', Auth::id())->with('order_items');
    //     // $LimitOrders = Order::where('order_status', 'pending')->orderBy('created_at', 'desc')->limit(10)->get();
    //     // $OrdersCount = Order::where('payment_status', 'paid')->count();
    //     // $PendingOrders = Order::where('order_status', 'pending')->count();
    //     // جلب الطلبات المدفوعة فقط
    //     // $paid_orders = Order::where('payment_status', 'paid')->get();

    //     // تمرير البيانات إلى كل من الواجهات المطلوبة
    //     return view('user.User-Orders', compact('orders'));
    // }
}
