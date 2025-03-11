<?php

namespace App\Services;

use App\Interfaces\PaymobServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;


class PaymobService implements PaymobServiceInterface
{
    private $apiKey;
    private $integrationId;

    public function __construct()
    {
        $this->apiKey = env('PAYMOB_API_KEY');
        $this->integrationId = env('PAYMOB_INTEGRATION_ID');
    }

    public function authenticate()
    {
        Cache::remember(('paymob_token'), 3600, function () {
            $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
                'api_key' => $this->apiKey,
            ]);

            return $response->json('token');
        });
    }


    public function createOrder($validatedData)
    {
        $authToken = $this->authenticate();

        // استخدام المبلغ الإجمالي بدون خصم
        $totalAmount = $validatedData['total_amount'] * 100; // تحويل إلى قروش

        $response = Http::post('https://accept.paymob.com/api/ecommerce/orders', [
            'auth_token' => $authToken,
            'amount_cents' => $totalAmount,
            'currency' => 'EGP',

        ]);

        return $response->json('id');
    }

    public function createIntention($order_reference, $OrderData)
    {
        // dd($order_reference, $OrderData);
        $token = env('PAYMOB_SECRET_KEY');

        // ✅ جلب المنتجات من السلة
        $cartItems = Cart::where('user_id', Auth::id())->with(['product', 'package'])->get();
        // ✅ تجهيز بيانات المنتجات
        $items = [];
        $totalItemsAmount = 0; // تخزين إجمالي المبلغ للحسابات

        foreach ($cartItems as $item) {
            if ($item->type === 'product') {
                $itemName = $item->product->name;
                $itemPrice = $item->product->price;
            } elseif ($item->type === 'package') {
                $itemName = $item->package->name;
                $itemPrice = $item->package->price;
            } else {
                return response()->json(['success' => 'false', 'message' => 'المنتج غير موجود لدينا']);
            }

            // $itemTotal = $item->product->price * 100 * $item->quantity; // تحويل السعر × الكمية إلى قروش
            $itemTotal = $item->itemTotal * 100; // تحويل السعر × الكمية إلى قروش
            $items[] = [
                "name"        => $itemName,
                "amount"      => $itemPrice * 100, // تحويل إلى قروش
                "description" => "NA",
                "quantity"    => $item->quantity,
            ];
            $totalItemsAmount += $itemTotal;
        }

        // ✅ حساب الخصم وإضافته كمنتج منفصل
        $discount = session('applied_coupon')['discount'] ?? 0;
        if ($discount > 0) {
            $items[] = [
                "name"        => "كوبون خصم",
                "amount"      => - ($discount * 100), // تحويل إلى قروش
                "description" => "خصم مخصوم من إجمالي الفاتورة",
                "quantity"    => 1,
            ];
        }

        // ✅ التأكد من أن `amount` يساوي `totalItemsAmount - الخصم`
        $paymobTotal = max(0, $totalItemsAmount - ($discount * 100));
        // ✅ تجهيز البيانات وإرسالها
        $response = Http::withHeaders([
            'Authorization' => "Token $token",
            'Content-Type'  => 'application/json',
        ])->post('https://accept.paymob.com/v1/intention/', [
            "amount"          => $paymobTotal, // ✅ تطابق تام مع إجمالي المنتجات
            "currency"        => "EGP",
            "payment_methods" => [4911841, 4876315],
            "items"           => $items,
            "special_reference" => $order_reference,
            "billing_data"    => [
                "apartment"    => "N/A",
                "first_name"   => $OrderData['first_name'],
                "last_name"    => $OrderData['last_name'],
                "street"       => $OrderData['shipping_address'] ?? "Unknown",
                "building"     => "N/A",
                "phone_number" => $OrderData['phone_number'],
                "country"      => "EGY",
                "email"        => $OrderData['email'],
                "floor"        => "1",
                "state"        => $OrderData['governorate'] ?? "Unknown"
            ],
            "customer" => [
                "first_name" => $OrderData['first_name'],
                "last_name"  => $OrderData['last_name'],
                "email"      => $OrderData['email'],
                "extras"     => ["re" => "22"]
            ],
            "extras" => ["ee" => 22]
        ]);

        // ✅ التحقق من الاستجابة
        if ($response->failed()) {
            dd([
                'status' => $response->status(),
                'error'  => $response->body(),
            ]);
        }

        return $response->json('client_secret');
        // بعد تأكيد الطلب والدفع بنجاح
        session()->forget('applied_coupon');
    }
}
