<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Notifications\OrderNotification;
use Illuminate\Support\Facades\Notification;
use App\Interfaces\PaymobServiceInterface;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Package;
use App\Models\PackageImages;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\PendingOrder;
use Database\Seeders\ProductImages;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $paymentService;

    // حقن PaymobServiceInterface داخل الكونستركتور
    public function __construct(PaymobServiceInterface $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    // 
    public function processPayment(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|string',
            'payment_method' => 'required|in:COD,visa,m_wallet',
            'governorate' => 'required|string',
            'city' => 'required|string',
            'village' => 'required|string',
            'shipping_address' => 'required|string',
            'total_amount' => 'required|numeric|min:1',
        ]);

        // تخزين بيانات العميل في الجلسة
        // جلب المنتجات من السلة
        $cartItems = Cart::where('user_id', $request->user_id)->with('product')->get();
        $totalAmount = $cartItems->sum(function ($cartItem) {
            return $cartItem->itemTotal;
        });

        $finalTotal = session()->get('applied_coupon.newTotal', $totalAmount);

        if ($request->payment_method === 'COD') {
            $order = Order::create(array_merge($validatedData, ['total_amount' => $finalTotal, 'payment_status' => 'unpaid']));
            return redirect()->route('checkout.success')->with('success', 'تم تقديم طلبك بنجاح!');
        } else {
            $customer_data = json_encode($validatedData);
            $cart_items = json_encode($cartItems);
            $final_total = json_encode($finalTotal);
            $order_reference = random_int(100000, 999999);

            $pendingOrder = PendingOrder::create([
                'order_reference' =>  $order_reference, // رقم عشوائي بين 100000 و 999999
                'user_id'         =>  Auth::id(),
                'customer_data'   =>  $customer_data,
                'cart_items'      =>  $cart_items,
                'final_total'     =>  $final_total,
            ]);
        }

        $orderId = $this->paymentService->createOrder($validatedData);
        $clientSecret = $this->paymentService->createIntention($order_reference, $validatedData);

        return redirect("https://accept.paymob.com/unifiedcheckout/?publicKey=" . env('PAYMOB_PUBLIC_KEY') . "&clientSecret=" . $clientSecret);
    }

    public function handleWebhook(Request $request)
    {

        // dd($request->all());
        Log::info('Webhook Received: ', $request->all());

        // التحقق من نجاح الدفع
        if (!$request->boolean('success') || $request->txn_response_code !== "APPROVED") {
            return response()->json(['error' => 'عملية الدفع لم تنجح'], 400);
        }

        $merchant_order_id = $request->input('merchant_order_id');
        $transactionId = $request->input('id');
        // $amount = $request->input('amount_cents') / 100; // تحويل المبلغ إلى الجنيه المصري

        // البحث عن الطلب في جدول PendingOrder
        $order_data = PendingOrder::where('order_reference', $merchant_order_id)->first();

        if ($order_data) {
            $customer_data = json_decode($order_data->customer_data, true); //true referse to convert to Associative Array $data['name']
            $cart_items     = json_decode($order_data->cart_items, true);

            // insert into orders && order_items Table
            $order = Order::create([
                'user_id'           => $customer_data['user_id'],
                'mid'               => $merchant_order_id,
                'transaction_id'    => $transactionId,
                'first_name'        => $customer_data['first_name'],
                'last_name'         => $customer_data['last_name'],
                'email'             => $customer_data['email'],
                'phone_number'      => $customer_data['phone_number'],
                'total_amount'      => $customer_data['total_amount'],
                'payment_status'    => 'paid',
                'payment_method'    => $request['source_data_type'],
                'shipping_address'  => $customer_data['shipping_address'],
                'village'           => $customer_data['village'],
                'city'              => $customer_data['city'],
                'governorate'       => $customer_data['governorate'],
            ]);
            // 
            // dd($cart_items);
            if ($order) {
                foreach ($cart_items as $item) {
                    $item_name = Product::where('id', $item['product_id'])->value('name')
                        ?? Package::where('id', $item['product_id'])->value('name');
                    $item_price = Product::where('id', $item['product_id'])->value('price')
                        ?? Package::where('id', $item['product_id'])->value('price');
                    $item_image = ProductImage::where('product_id', $item['product_id'])->value('image')
                        ?? PackageImages::where('package_id', $item['product_id'])->value('image_path');

                    OrderItems::create([
                        'order_id'       => $order->id,
                        'user_id'        => $item['user_id'] ?? Auth::id(),
                        'product_id'     => $item['product_id'],
                        'product_name'   => $item_name,
                        'type'           => $item['type'],
                        'quantity'       => $item['quantity'],
                        'price'          => $item_price,
                        'product_image'  => $item_image,
                        'total'     => $item['itemTotal'],
                        'created_at'     => $item['created_at'],
                    ]);
                }
            }
        } else {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // $pendingOrder->delete();
        // إرسال إشعار للمستخدم
        // if (Auth::check()) {
        $user = Auth::user();
        $user->notify(new OrderNotification($order, 'user'));

        // حذف المنتجات من سلة المشتريات بعد نجاح الدفع
        Cart::where('user_id', $user->id)->delete();
        session()->forget('applied_coupon');
        // }

        // إرسال إشعار للمشرف
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new OrderNotification($order, 'admin'));
        }
        // إرسال استجابة إلى Paymob بأن الـ Webhook تمت معالجته بنجاح
        // return response()->json(['message' => 'تمت معالجة الدفع وحفظ الطلب بنجاح'], 200);
        $customer_name            = $customer_data['first_name'] . $customer_data['last_name'];
        $customer_phone_number    = $customer_data['phone_number'];
        $customer_address         = $customer_data['shipping_address'] . " - " . $customer_data['village'] . " - " . $customer_data['city'];
        $customer_governorate     = " - " . $customer_data['governorate'];
        return view('payment-status.success', compact(['request', 'customer_name', 'customer_phone_number', 'customer_address', 'customer_governorate']));
    }



















    // 
    // public function handleCallback(Request $request)
    // {

    //     $pendingOrder = PendingOrder::where('order_reference', $request->input('order'))
    //         ->orWhere('order_reference', $request->input('order_reference'))
    //         ->first();

    //     dd($pendingOrder);


    //     // استرجاع order_reference من بيانات Paymob
    //     dd($request->all());
    //     $orderReference = $request->input('order_reference');

    //     if (!$orderReference) {
    //         Log::error('لم يتم العثور على order_reference في رد Paymob');
    //         return response()->json(['message' => 'بيانات غير صحيحة'], 400);
    //     }

    //     // البحث عن الطلب في قاعدة البيانات
    //     $pendingOrder = PendingOrder::where('order_reference', $orderReference)->first();

    //     if (!$pendingOrder) {
    //         Log::error('لم يتم العثور على الطلب المعلق');
    //         return response()->json(['message' => 'طلب غير موجود'], 400);
    //     }

    //     // تحويل البيانات إلى مصفوفة
    //     $customerData = json_decode($pendingOrder->customer_data, true);
    //     $cartItems = json_decode($pendingOrder->cart_items, true);
    //     $finalTotal = $pendingOrder->final_total;

    //     dd($customerData);
    //     dd($cartItems);
    //     dd($finalTotal);

    //     // حذف الطلب المعلق بعد استرجاعه
    //     // $pendingOrder->delete();

    //     Log::info('Payment Callback Data:', $request->all());

    //     $data = $request->all();
    //     $paymentStatus = $data['success'] ?? false;

    //     if ($paymentStatus == true) {
    //         try {
    //             $order = Order::create(array_merge($customerData, [
    //                 'payment_status' => 'paid',
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]));

    //             Log::info('Order Created Successfully', ['order_id' => $order->id]);

    //             if (!empty($data['items'])) {
    //                 foreach ($data['items'] as $item) {
    //                     OrderItems::create([
    //                         'order_id' => $order->id,
    //                         'product_image' => $item['product_image'] ?? 'default.png',
    //                         'product_name' => $item['name'] ?? 'Unknown',
    //                         'quantity' => $item['quantity'] ?? 1,
    //                         'price' => ($item['amount'] ?? 0) / 100,
    //                         'total' => (($item['amount'] ?? 0) * ($item['quantity'] ?? 0)) / 100,
    //                         'created_at' => now(),
    //                         'updated_at' => now(),
    //                     ]);
    //                 }
    //                 Log::info('Order Items Added Successfully', ['order_id' => $order->id]);
    //             }

    //             if (Auth::check()) {
    //                 $user = Auth::user();
    //                 $user->notify(new OrderNotification($order, 'user'));

    //                 // حذف السلة بعد الدفع الناجح
    //                 Cart::where('user_id', $user->id)->delete();
    //                 session()->forget('applied_coupon');
    //                 session()->forget('customer_data'); // حذف بيانات العميل من الجلسة بعد الاستخدام
    //             }

    //             // إشعار الأدمن
    //             $admin = User::where('role', 'admin')->first();
    //             if ($admin) {
    //                 $admin->notify(new OrderNotification($order, 'admin'));
    //             }

    //             return view('payment-status.success', [
    //                 'data' => $data,
    //                 'order' => $order
    //             ]);
    //         } catch (\Exception $e) {
    //             Log::error('Order Creation Failed', ['error' => $e->getMessage()]);
    //             return response()->json(['message' => $e->getMessage()], 500);
    //         }
    //     }

    //     session()->forget('applied_coupon');
    //     session()->forget('customer_data'); // حذف البيانات في حالة فشل الدفع

    //     return view('payment-status.success', [
    //         'data' => $data,
    //         'order' => null
    //     ]);
    // }









    // public function processPayment(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'user_id' => 'required|integer',
    //         'first_name' => 'required|string',
    //         'last_name' => 'required|string',
    //         'email' => 'required|email',
    //         'phone_number' => 'required|string',
    //         'payment_method' => 'required|in:COD,visa,m_wallet',
    //         'governorate' => 'required|string',
    //         'city' => 'required|string',
    //         'village' => 'required|string',
    //         'shipping_address' => 'required|string',
    //         'total_amount' => 'required|numeric|min:1',
    //     ]);


    //     // جلب المنتجات من السلة
    //     $cartItems = Cart::where('user_id', $request->user_id)->with('product')->get();

    //     // حساب المبلغ الإجمالي
    //     $totalAmount = $cartItems->sum(function ($cartItem) {
    //         return $cartItem->itemTotal;
    //     });

    //     $finalTotal = session()->get('applied_coupon.newTotal', $totalAmount);

    //     // الدفع عند الاستلام (COD)
    //     if ($request->payment_method === 'COD') {
    //         $order = Order::create(array_merge($validatedData, ['total_amount' => $finalTotal, 'payment_status' => 'unpaid']));
    //         return redirect()->route('checkout.success')->with('success', 'تم تقديم طلبك بنجاح!');
    //     }

    //     public $OrderData = $validatedData;


    //     // إرسال الطلب إلى Paymob
    //     $orderId = $this->paymentService->createOrder(array_merge($validatedData)); // ✅ إضافة ";"

    //     $clientSecret = $this->paymentService->createIntention(
    //         $orderId,
    //         array_merge($OrderData)
    //     );
    //     return redirect("https://accept.paymob.com/unifiedcheckout/?publicKey=" . env('PAYMOB_PUBLIC_KEY') . "&clientSecret=" . $clientSecret);
    // }

    // public function handleCallback(Request $request)
    // {
    //     $customer = session('customer_data');
    //     dd($customer);
    //     // مثال لاستخدامها
    //     Log::info('Payment Callback Data:', $request->all());

    //     $data = $request->all();
    //     $paymentStatus = $data['success'] ?? false;


    //     if ($paymentStatus == true) {
    //         try {
    //             // إنشاء الطلب
    //             $order = Order::create([
    //                 'user_id' => Auth::id(),
    //                 'first_name' => 'Eihab Adel',
    //                 'last_name' => 'Eihab Adel',
    //                 'email' => 'eihab2342@gmail.com',
    //                 'phone_number' => '01119842314',
    //                 'total_amount' => 441,
    //                 'governorate' => 'Eihab Adel',
    //                 'city' => 'mansourah',
    //                 'village' => 'selka',
    //                 'shipping_address' => 'Eihab Adel',
    //                 'payment_status' => "paid",
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]);

    //             Log::info('Order Created Successfully', ['order_id' => $order->id]);

    //             // إضافة المنتجات إلى الطلب
    //             if (!empty($data['items'])) {
    //                 foreach ($data['items'] as $item) {
    //                     OrderItems::create([
    //                         'order_id' => $order->id,
    //                         'product_image' => $item['product_image'] ?? 'default.png',
    //                         'product_name' => $item['name'] ?? 'Unknown',
    //                         'quantity' => $item['quantity'] ?? 1,
    //                         'price' => ($item['amount'] ?? 0) / 100,
    //                         'total' => (($item['amount'] ?? 0) * ($item['quantity'] ?? 0)) / 100,
    //                         'created_at' => now(),
    //                         'updated_at' => now(),
    //                     ]);
    //                 }
    //                 Log::info('Order Items Added Successfully', ['order_id' => $order->id]);
    //             }

    //             // إرسال إشعار للمستخدم
    //             if (Auth::check()) {
    //                 $user = Auth::user();
    //                 $user->notify(new OrderNotification($order, 'user'));

    //                 // حذف المنتجات من سلة المشتريات بعد نجاح الدفع
    //                 Cart::where('user_id', $user->id)->delete();
    //                 session()->forget('applied_coupon');
    //             }

    //             // إرسال إشعار للمشرف
    //             $admin = User::where('role', 'admin')->first();
    //             if ($admin) {
    //                 $admin->notify(new OrderNotification($order, 'admin'));
    //             }

    //             // **إرجاع صفحة النجاح مع تمرير بيانات الطلب والعميل**
    //             return view('payment-status.success', [
    //                 'data' => $data,
    //                 'order' => $order
    //             ]);
    //         } catch (\Exception $e) {
    //             Log::error('Order Creation Failed', ['error' => $e->getMessage()]);
    //             return response()->json(['message' => $e->getMessage()], 500);
    //         }
    //     }

    //     session()->forget('applied_coupon');
    //     return view('payment-status.success', [
    //         'data' => $data,
    //         'order' => null
    //     ]);
    // }
}
