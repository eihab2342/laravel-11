<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function index()
    {
        return view('admin.dashboard');
    }


    public function dashboard()
    {
        $notifications = Notification::where('user_id', Auth::id())->latest()->get();
        return view('admin.dashboard', compact('notifications'));
    }
}
