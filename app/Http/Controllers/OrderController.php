<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

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
    public function show(Order $order)
    {
        //
        // Order $order, 
        // return view('admin.orders.order', compact('order'));
        // 
        $order = Order::with('items')->findOrFail($order->id);
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
}
