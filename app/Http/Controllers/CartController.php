<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Package;



class CartController extends Controller
{

    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['product.images', 'package.images']) // جلب المنتجات أو الباكدجات المرتبطة
            ->get();

        // حذف المنتجات الغير موجودة وإرسال إشعار للمستخدم
        // $cartItems = $cartItems->filter(function ($item) {
        //     if (!$item->product) {
        //         $item->delete();
        //         session()->flash('error', 'تم حذف بعض المنتجات من سلة المشتريات لأنها لم تعد متوفرة.');
        //         return false;
        //     }
        //     return true;
        // });

        $totalprice = $cartItems->sum(function ($item) {
            return ($item->product ? $item->product->price : ($item->package ? $item->package->price : 0)) * $item->quantity;
        });

        // dd($cartItems);

        return view('user.cart', [
            'cartItems' => $cartItems,
            'totalprice' => $totalprice
        ]);
    }


    public function addToCart(Request $request)
    {
        try {

            $user = Auth::check();
            if ($user) {
                $userId = Auth::id();
            }
            $productId = $request->product_id;
            $type = $request->type;


            if ($type === 'product') {
                // 
                $item = Product::where('id', $productId)->first();
            } elseif ($type === 'package') {
                // 
                $item = Package::where('id', $productId)->first();
            } else {
                return response()->json(['success' => false, 'message' => 'نوع المنتج غير موجود '], 400);
            }

            //

            if (!$item) {
                return response()->json(['success' => false, 'message' => 'العنصر غير موجود'], 404);
            }

            $price = $item->price;
            // البحث عن المنتج في السلة
            // عشان لو موجود نحدث السعر والإجمالي
            $cartItem = Cart::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('type', $type)
                ->first();

            if ($cartItem) {
                // إذا كان المنتج موجود، تحديث الكمية فقط
                $cartItem->increment('quantity');
                $cartItem->update(['itemTotal' => $cartItem->quantity * $price]);
                $message = 'تم تحديث الكمية في العربة';
            } else {
                // إذا كان المنتج غير موجود، إضافته للسلة
                Cart::create([
                    'user_id'    => $userId,
                    'product_id' => $productId,
                    'type'       => $type,
                    'price'      => $price,
                    'quantity'   => 1,
                    'itemTotal'  => $price,
                ]);
                if ($type === 'product') {
                    $message = 'تمت إضافة المنتج إلى السلة بنجاح';
                } else {
                    $message = 'تمت إضافة الباكدج  إلى السلة بنجاح';
                }
            }

            // حساب إجمالي السلة
            $total_price = Cart::where('user_id', $userId)->sum('itemTotal');

            return response()->json([
                'success' => true,
                'message' => $message,
                'total_price' => $total_price
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateCart(Request $request)
    {
        $user = auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'يجب تسجيل الدخول لتحديث العربة']);
        }

        $productId = $request->product_id;
        $quantity = $request->quantity;
        if ($quantity < 1) {
            return response()->json(['success' => false, 'message' => 'الكمية يجب أن تكون على الأقل 1'], 400);
        }

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'العنصر غير موجود في العربة']);
        }

        // تحديث الكمية
        $cartItem->quantity = $quantity;

        // التحقق من النوع لاختيار السعر الصحيح
        if ($cartItem->type === 'product' && $cartItem->product) {
            $itemPrice = $cartItem->product->price;
        } elseif ($cartItem->type === 'package' && $cartItem->package) {
            $itemPrice = $cartItem->package->price;
        } else {
            $itemPrice = 0; // تجنب الأخطاء إذا لم يتم العثور على المنتج أو الباكدج
        }

        $cartItem->itemTotal = $quantity * $itemPrice;
        $cartItem->save();

        // حساب السعر الجديد للعنصر
        // $price = $cartItem->product->price;
        // $newItemTotal = $price * $quantity;

        // حساب إجمالي الطلب
        $totalCartPrice = Cart::where('user_id', $user->id)->get()->sum(function ($item) {
            // return $item->product->price * $item->quantity;
            return $item->itemTotal;
        });

        return response()->json([
            'success' => true,
            'new_item_total' => $cartItem->itemTotal,
            'total_cart_price' => $totalCartPrice,
        ]);
    }

    // ✅ حذف منتج من السلة
    public function removeFromCart(Request $request)
    {
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('id', $request->cart_id) // تغيير product_id إلى cart_id
            ->first();

        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function getCartCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }
        $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        return response()->json(['count' => $cartCount]);
    }
    // ✅ تفريغ السلة بالكامل
    public function clearCart()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'يجب تسجيل الدخول'], 401);
        }
        Cart::where('user_id', Auth::id())->delete();
        return response()->json(['success' => true, 'message' => 'تم تفريغ السلة بنجاح']);
    }
}
