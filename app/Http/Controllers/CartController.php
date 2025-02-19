<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // ✅ عرض سلة المشتريات
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $totalprice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        return view('user.cart', [
            'cartItems' => $cartItems,
            'totalprice' => $totalprice
        ]);
    }

    public function addToCart(Request $request)
    {
        // جلب سعر المنتج من جدول المنتجات
        $price = DB::table('products')->where('id', $request->product_id)->value('price');

        // تحديث أو إنشاء العنصر في السلة
        $cartItem = Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $request->product_id],
            [
                'price' => $price,
                'quantity' => DB::raw('quantity'),
                'itemTotal' => DB::raw("($price * (quantity))") // حساب إجمالي المنتج بشكل صحيح
            ]
        );

        Cart::where('user_id', Auth::id())->where('product_id', $request->product_id)->update([
            'itemTotal' => DB::raw("($price * (quantity))") // حساب إجمالي المنتج بشكل صحيح
        ]);

        // حساب الإجمالي الجديد للعربة
        $total_price = Cart::where('user_id', Auth::id())->sum('itemTotal');

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة المنتج إلى السلة',
            'total_price' => $total_price
        ]);
    }


    // public function updateCart(Request $request)
    // {
    //     $cartItem = Cart::where('user_id', Auth::id())
    //     ->where('product_id', $request->product_id)
    //     ->first();

    //     if (!$cartItem) {
    //         return response()->json(['success' => false, 'message' => 'العنصر غير موجود في السلة']);
    //     }

    //     $productPrice = DB::table('products')->where('id', $request->product_id)->value('price');

    //     if (!$productPrice) {
    //         return response()->json(['success' => false, 'message' => 'المنتج غير موجود']);
    //     }

    //     // ✅ تحديث الكمية وإجمالي المنتج في استعلام واحد
    //     $newQuantity = $request->quantity;
    //     $newProductTotal = $productPrice * $newQuantity;

    //     $cartItem->update([
    //         'quantity' => $newQuantity,
    //         'itemTotal' => $newProductTotal
    //     ]);

    //     // ✅ حساب الإجمالي الجديد للعربة بالكامل
    //     $totalCartPrice = Cart::where('user_id', Auth::id())->sum('itemTotal');

    //     return response()->json([
    //         'success' => true,
    //         'new_product_total' => $newProductTotal, // السعر الجديد للمنتج بعد التحديث
    //         'total_cart_price' => $totalCartPrice // إجمالي السعر الجديد للعربة
    //     ]);
    // }

    public function updateCart(Request $request)
    {
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'العنصر غير موجود في السلة']);
        }

        $productPrice = DB::table('products')->where('id', $request->product_id)->value('price');

        if (!$productPrice) {
            return response()->json(['success' => false, 'message' => 'المنتج غير موجود']);
        }

        // ✅ تحديث الكمية وإجمالي المنتج في استعلام واحد
        $newQuantity = $request->quantity;
        $newProductTotal = $productPrice * $newQuantity;

        $cartItem->update([
            'quantity' => $newQuantity,
            'itemTotal' => $newProductTotal
        ]);

        // ✅ حساب الإجمالي الجديد للعربة بالكامل
        $totalCartPrice = Cart::where('cart.user_id', Auth::id())
            ->join('products', 'cart.product_id', '=', 'products.id')
            ->sum(DB::raw('cart.quantity * products.price'));


        return response()->json([
            'success' => true,
            'new_product_total' => $newProductTotal, // إجمالي السعر الجديد للمنتج
            'total_cart_price' => $totalCartPrice // إجمالي السعر الجديد للعربة
        ]);
    }


    // ✅ حذف منتج من السلة
    public function removeFromCart(Request $request)
    {
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function getCartCount()
    {
        return response()->json(['count' => Cart::where('user_id', Auth::id())->count()]);
    }

    // ✅ تفريغ السلة بالكامل
    public function clearCart()
    {
        Cart::where('user_id', Auth::id())->delete();
        return response()->json(['success' => true, 'message' => 'تم تفريغ السلة بنجاح']);
    }
}
