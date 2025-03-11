<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\images;
use App\Models\product;
use App\Models\Control;

class AdminControl extends Controller
{
    public function index()
    {
        $controls = Control::orderBy('order', 'asc')->get();
        $categories = Category::all();
        return view('admin.control.index', compact('controls', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image',
            'order' => 'required|integer'
        ]);

        $path = $request->file('image')->store('control_images', 'public');

        Control::create([
            'category_id' => $request->category_id,
            'image' => $path,
            'order' => $request->order,
        ]);

        return redirect()->route('control')->with('success', 'تمت إضافة الفئة بنجاح!');
    }

    public function destroy($id)
    {
        $control = Control::findOrFail($id);
        Storage::disk('public')->delete($control->image);
        $control->delete();

        return redirect()->route('control')->with('success', 'تم الحذف بنجاح!');
    }



    // public function IndexToUser()
    // {
    //     //     // جلب جميع البيانات المطلوبة
    //     $controls = Control::with(['category.products'])->get();
    //     // dd($controls);
    //     return view('user', compact('controls'));
    // }
}
