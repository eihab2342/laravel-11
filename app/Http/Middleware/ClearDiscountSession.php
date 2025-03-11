<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClearDiscountSession
{
    public function handle(Request $request, Closure $next)
    {
        // التحقق مما إذا كان المتصفح بدأ جلسة جديدة
        if (!$request->hasCookie(session_name()) && !$request->session()->has('user_active')) {
            Session::forget('applied_coupon'); // حذف سيشن الخصم فقط
            Session::forget('final_total'); // لو مش نشطه بنمسح الخصم
        }

        return $next($request);
    }
}
