<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $coupons = Coupon::all();
        $coupons = Coupon::all()->map(function ($coupon) {
            $coupon->category_name = $coupon->category_id ? Category::where('id', $coupon->category_id)->value('name') : 'No';
            return $coupon;
        });

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.coupons.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:coupons|max:255',
            'discount_type' => 'required|in:fixed,percentage',
            'fixed_amount' => 'nullable|numeric|required_if:discount_type,fixed',
            'discount_percentage' => 'nullable|numeric|required_if:discount_type,percentage',
            'max_discount_amount' => 'nullable|numeric|required_if:discount_type,percentage',
            'category_id' => 'nullable|numeric',
            'usage_limit' => 'nullable|integer',
            'expires_at' => 'nullable|date',
        ]);

        // إذا فشل التحقق من الصحة، يتم إرجاع الأخطاء
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // إنشاء كوبون جديد
        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->discount_type = $request->discount_type;

        if ($request->discount_type == 'fixed') {
            $coupon->fixed_amount = $request->fixed_amount;
        } else {
            $coupon->discount_percentage = $request->discount_percentage;
            $coupon->max_discount_amount = $request->max_discount_amount;
        }
        $coupon->category_id = $request->category_id;
        $coupon->usage_limit = $request->usage_limit;
        $coupon->expires_at = $request->expires_at;
        $coupon->save();
        // dd($coupon);
        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('coupons.index')->with('success', 'تم إنشاء الكوبون بنجاح!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        return view('admin.coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:255|unique:coupons,code,' . $coupon->id,
            'discount_type' => 'required|in:fixed,percentage',
            'fixed_amount' => 'nullable|numeric|required_if:discount_type,fixed',
            'discount_percentage' => 'nullable|numeric|required_if:discount_type,percentage',
            'max_discount_amount' => 'nullable|numeric|required_if:discount_type,percentage',
            'usage_limit' => 'nullable|integer',
            'expires_at' => 'nullable|date',
        ]);

        // إذا فشل التحقق من الصحة، يتم إرجاع الأخطاء
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // تحديث بيانات الكوبون
        $coupon->code = $request->code;
        $coupon->discount_type = $request->discount_type;

        if ($request->discount_type == 'fixed') {
            $coupon->fixed_amount = $request->fixed_amount;
            $coupon->discount_percentage = null;
            $coupon->max_discount_amount = null;
        } else {
            $coupon->discount_percentage = $request->discount_percentage;
            $coupon->max_discount_amount = $request->max_discount_amount;
            $coupon->fixed_amount = null;
        }

        $coupon->usage_limit = $request->usage_limit;
        $coupon->expires_at = $request->expires_at;
        $coupon->save();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('admin.coupons.index')->with('success', 'تم تحديث الكوبون بنجاح!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('coupons.index')->with('success', 'تم حذف الكوبون بنجاح!');
    }

    // public function applyCoupon(Request $request)
    // {
    //     header("Content-Type: application/json");

    //     $request->validate([
    //         'coupon' => 'required|string',
    //         'subtotal' => 'required|numeric|min:1',
    //     ]);

    //     $coupon = Coupon::where('code', $request->coupon)->first();

    //     if (!$coupon) {
    //         return response()->json(['success' => false, 'message' => 'الكوبون غير صالح']);
    //     }

    //     // التحقق مما إذا كان المستخدم قد استخدم الكوبون مسبقًا
    //     $usedCoupon = DB::table('coupon_usage')
    //         ->where('user_id', auth::id())
    //         ->where('coupon_id', $coupon->id)
    //         ->exists();

    //     if ($usedCoupon) {
    //         return response()->json(['success' => false, 'message' => '.لقد استخدمت هذا الكوبون مسبقًا']);
    //     }

    //     // التحقق مما إذا كان الكوبون قد تم استخدامه مسبقًا في الجلسة
    //     // if (session()->has('applied_coupon') && session('applied_coupon.code') === $coupon->code) {
    //     //     return response()->json(['success' => false, 'message' => 'لقد استخدمت هذا الكوبون من قبل']);
    //     // }

    //     $discount = 0;

    //     // حساب قيمة الخصم
    //     if ($coupon->discount_type === 'fixed') {
    //         $discount = $coupon->fixed_amount;
    //     } elseif ($coupon->discount_type === 'percentage' && $coupon->discount_percentage > 0) {
    //         $discount = ($request->subtotal * $coupon->discount_percentage) / 100;
    //         $discount = min($discount, $coupon->max_discount_amount ?? $discount);
    //     }

    //     $newTotal = max(0, $request->subtotal - $discount);

    //     // حفظ الكوبون في الجلسة مع تحديد وقت انتهاء
    //     session([
    //         'applied_coupon' => [
    //             'code' => $coupon->code,
    //             'discount' => number_format($discount, 2),
    //             'newTotal' => number_format($newTotal, 2),
    //             'expires_at' => now()->addMinutes(30) // انتهاء الكوبون بعد 30 دقيقة
    //         ]
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'discount' => number_format($discount, 2),
    //         'newTotal' => number_format($newTotal, 2),
    //         'message' => 'تم تطبيق الخصم بنجاح'
    //     ]);
    //     $usedCoupon = DB::table('coupon_usage')
    //         ->where('user_id', auth::id())
    //         ->where('coupon_id', $coupon->id)
    //         ->exists();
    // }


    public function applyCoupon(Request $request)
    {
        header("Content-Type: application/json");

        $request->validate([
            'coupon' => 'required|string',
            'subtotal' => 'required|numeric|min:1',
        ]);

        $coupon = Coupon::where('code', $request->coupon)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'الكوبون غير صالح']);
        }

        $userId = auth::id(); // الحصول على ID المستخدم الحالي

        // التحقق مما إذا كان المستخدم قد استخدم الكوبون مسبقًا في قاعدة البيانات
        $usedCoupon = DB::table('coupon_usage')
            ->where('user_id', $userId)
            ->where('coupon_id', $coupon->id)
            ->exists();

        if ($usedCoupon) {
            return response()->json(['success' => false, 'message' => 'لقد استخدمت هذا الكوبون مسبقًا']);
        }

        // التحقق مما إذا كان الكوبون قد تم استخدامه مسبقًا في الجلسة
        if (session()->has('applied_coupon') && session('applied_coupon.code') === $coupon->code) {
            return response()->json(['success' => false, 'message' => 'لقد استخدمت هذا الكوبون من قبل']);
        }

        $discount = 0;

        // حساب قيمة الخصم
        if ($coupon->discount_type === 'fixed') {
            $discount = $coupon->fixed_amount;
        } elseif ($coupon->discount_type === 'percentage' && $coupon->discount_percentage > 0) {
            $discount = ($request->subtotal * $coupon->discount_percentage) / 100;
            $discount = min($discount, $coupon->max_discount_amount ?? $discount);
        }

        $newTotal = max(0, $request->subtotal - $discount);

        // حفظ الكوبون في الجلسة مع تحديد وقت انتهاء
        session([
            'applied_coupon' => [
                'code' => $coupon->code,
                'discount' => number_format($discount, 2),
                'newTotal' => number_format($newTotal, 2),
                'expires_at' => now()->addMinutes(30) // انتهاء الكوبون بعد 30 دقيقة
            ]
        ]);
        // if (session()->has('applied_coupon')) {
        //     session()->forget('applied_coupon');
        // }

        // تسجيل استخدام الكوبون في قاعدة البيانات لمنع إعادة استخدامه
        DB::table('coupon_usage')->insert([
            'user_id' => $userId,
            'coupon_id' => $coupon->id,
            'used_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'discount' => number_format($discount, 2),
            'newTotal' => number_format($newTotal, 2),
            'message' => 'تم تطبيق الخصم بنجاح'
        ]);
    }

























    // public function applyCoupon(Request $request)
    // {
    //     // التأكد من إرسال الكود والمبلغ
    //     $request->validate([
    //         'coupon' => 'required|string',
    //         'subtotal' => 'required|numeric|min:1',
    //     ]);

    //     $coupon = Coupon::where('code', $request->coupon)->first();

    //     if (!$coupon) {
    //         return response()->json(['success' => false, 'message' => 'الكوبون غير صالح']);
    //     }

    //     // التأكد من عدم استخدام الكوبون مسبقًا
    //     $usageCount = CouponUsage::where('coupon_id', $coupon->id)
    //         ->where('user_id', Auth::id())
    //         ->count();

    //     if ($usageCount > 0) {
    //         return response()->json(['success' => false, 'message' => 'لقد استخدمت هذا الكوبون من قبل']);
    //     }

    //     $discount = 0;

    //     // حساب قيمة الخصم
    //     if ($coupon->discount_type == 'fixed') {
    //         $discount = $coupon->fixed_amount;
    //     } elseif ($coupon->discount_type == 'percentage' && !empty($coupon->discount_percentage)) {
    //         $discount = ($request->subtotal * $coupon->discount_percentage) / 100;
    //         if (!empty($coupon->max_discount_amount) && $discount > $coupon->max_discount_amount) {
    //             $discount = $coupon->max_discount_amount;
    //         }
    //     }

    //     // التأكد من أن الخصم لا يتجاوز السعر الإجمالي
    //     $newTotal = max(0, $request->subtotal - $discount);

    //     // حفظ بيانات استخدام الكوبون
    //     CouponUsage::create([
    //         'coupon_id' => $coupon->id,
    //         'user_id' => Auth::id(),
    //         'total_after_discount' => $newTotal,
    //         'used_at' => now(),
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'discount' => number_format($discount, 2),
    //         'newTotal' => number_format($newTotal, 2),
    //         'message' => 'تم تطبيق الخصم بنجاح'
    //     ]);
    // }



    // public function applyCoupon(Request $request)
    // {
    //     $request->validate([
    //         'coupon' => 'required|string',
    //         'subtotal' => 'required|numeric|min:1',
    //     ]);

    //     $coupon = Coupon::where('code', $request->coupon)->first();

    //     if (!$coupon) {
    //         return response()->json(['success' => false, 'message' => 'الكوبون غير صالح']);
    //     }

    //     // التأكد من عدم استخدام الكوبون مسبقًا
    //     if (session()->has('applied_coupon') && session('applied_coupon')['code'] === $coupon->code) {
    //         return response()->json(['success' => false, 'message' => 'لقد استخدمت هذا الكوبون من قبل']);
    //     }

    //     $discount = 0;

    //     // حساب قيمة الخصم
    //     if ($coupon->discount_type == 'fixed') {
    //         $discount = $coupon->fixed_amount;
    //     } elseif ($coupon->discount_type == 'percentage' && !empty($coupon->discount_percentage)) {
    //         $discount = ($request->subtotal * $coupon->discount_percentage) / 100;
    //         if (!empty($coupon->max_discount_amount) && $discount > $coupon->max_discount_amount) {
    //             $discount = $coupon->max_discount_amount;
    //         }
    //     }

    //     $newTotal = max(0, $request->subtotal - $discount);

    //     // حفظ الكوبون في الجلسة بدلًا من إدخاله فورًا في قاعدة البيانات
    //     session([
    //         'applied_coupon' => [
    //             'code' => $coupon->code,
    //             'discount' => $discount,
    //             'newTotal' => $newTotal
    //         ]
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'discount' => number_format($discount, 2),
    //         'newTotal' => number_format($newTotal, 2),
    //         'message' => 'تم تطبيق الخصم بنجاح'
    //     ]);
    // }








    // public function applyCoupon(Request $request)
    // {
    //     $coupon = Coupon::where('code', $request->coupon)->first();

    //     if (!$coupon) {
    //         return response()->json(['success' => false, 'message' => 'الكوبون غير صالح']);
    //     }

    //     // التحقق من عدد مرات الاستخدام (مثلاً لو كان الكوبون يمكن استخدامه مرة واحدة لكل مستخدم)
    //     $usageCount = CouponUsage::where('coupon_id', $coupon->id)
    //         ->where('user_id', auth::id())
    //         ->count();

    //     if ($usageCount > 0) {
    //         return response()->json(['success' => false, 'message' => 'لقد استخدمت هذا الكوبون من قبل']);
    //     }



    //     if ($coupon->discount_type == 'fixed') {
    //         $discount = $coupon->fixed_amount;
    //     } elseif ($coupon->discount_type == 'percentage') {
    //         if (!empty($coupon->discount_percentage)) {
    //             // حساب قيمة الخصم بناءً على النسبة المئوية
    //             $discount = ($request->subtotal * $coupon->discount_percentage) / 100;

    //             // التأكد من أن الخصم لا يتجاوز الحد الأقصى المسموح به
    //             if (!empty($coupon->max_discount_amount) && $discount > $coupon->max_discount_amount) {
    //                 $discount = $coupon->max_discount_amount;
    //             }
    //         }
    //     }

    //     // حساب السعر الجديد بعد الخصم
    //     $newTotal = max(0, $request->subtotal - $discount);

    //     // حفظ الاستخدام
    //     CouponUsage::create([
    //         'coupon_id' => $coupon->id,
    //         'user_id' => auth::id(),
    //         'total_after_discount' => $newTotal,
    //         'used_at' => now(),
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'discount' => $discount,
    //         'newTotal' => $newTotal,
    //         'message' => 'تم تطبيق الخصم بنجاح'
    //     ]);
    // }

    // public function applyCoupon(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'coupon' => 'required|string',
    //         'subtotal' => 'required|numeric|min:0',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['success' => false, 'message' => 'بيانات غير صحيحة.'], 400);
    //     }

    //     $coupon = Coupon::where('code', $request->coupon)->first();

    //     if (!$coupon) {
    //         return response()->json(['success' => false, 'message' => 'كود الكوبون غير صالح.'], 404);
    //     }

    //     // التحقق من تاريخ انتهاء الصلاحية
    //     if (!empty($coupon->expires_at) && Carbon::parse($coupon->expires_at)->isPast()) {
    //         return response()->json(['success' => false, 'message' => 'انتهت صلاحية هذا الكوبون.'], 400);
    //     }

    //     // التحقق من عدد مرات الاستخدام
    //     $usageCount = CouponUsage::where('coupon_code', $coupon->code)->count();
    //     if ($coupon->usage_limit && $usageCount >= $coupon->usage_limit) {
    //         return response()->json(['success' => false, 'message' => 'تم استخدام هذا الكوبون بالكامل.'], 400);
    //     }

    //     $subtotal = $request->subtotal;
    //     $discount = 0;

    //     if ($coupon->discount_type === 'fixed') {
    //         $discount = min($coupon->fixed_amount, $subtotal);
    //     } elseif ($coupon->discount_type === 'percentage') {
    //         $discount = ($subtotal * $coupon->discount_percentage) / 100;
    //         if ($coupon->max_discount_amount) {
    //             $discount = min($discount, $coupon->max_discount_amount);
    //         }
    //     }

    //     $newTotal = max($subtotal - $discount, 0);

    //     return response()->json([
    //         'success' => true,
    //         'discount' => number_format($discount, 2),
    //         'newTotal' => number_format($newTotal, 2),
    //     ]);
    // }
}

































// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

// use App\Models\Coupon;
// use App\Models\CouponUsage;
// use App\Models\Category;

// class CouponController extends Controller
// {


//     public function index()
//     {
//         $coupons = Coupon::all();
//         return view('admin.coupons.coupons', compact('coupons'));
//     }

//     public function create()
//     {
//         $categories = Category::all();
//         return view('admin.coupons.create', compact('categories'));
//     }







//     // 
//     public function applyCoupon(Request $request)
//     {
//         $coupon = Coupon::where('code', $request->coupon_code)->first();

//         if (!$coupon || !$coupon->isValid()) {
//             return redirect()->back()->with('error', 'Invalid or expired coupon.');
//         }

//         if (CouponUsage::where('user_id', auth::id())->where('coupon_id', $coupon->id)->exists()) {
//             return redirect()->back()->with('error', 'You have already used this coupon.');
//         }

//         // إضافة الكوبون لاستخدام المستخدم
//         CouponUsage::create([
//             'user_id' => auth::id(),
//             'coupon_id' => $coupon->id,
//             'used_at' => now()
//         ]);

//         // تطبيق الخصم في السلة أو الشراء
//         return redirect()->route('cart.index')->with('success', 'Coupon applied successfully.');
//     }
// }
