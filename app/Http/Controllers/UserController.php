<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductImage; // تأكد من أنك أضفت الـ Model للصور
use Illuminate\Support\Facades\Storage;
use App\Models\images;
use App\Models\Product;
use App\Models\Control;
use App\Models\Package;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    /**
     * عرض جميع المنتجات.
     */

    // public function displayUserPage()
    // {
    //     $controls = Control::with(['category.products.images'])->get();
    //     dd($controls);
    //     return view('user', compact(['controls']));
    // }

    public function IndexToUser()
    {
        //     // جلب جميع البيانات المطلوبة
        $controls = Control::with(['category.products.images'])->get();
        // dd($controls);
        $images = images::all();
        $categories = Category::all();
        $packages = Package::with('images')->get();
        return view('user', compact(['controls', 'images', 'categories', 'packages']));
    }
}
