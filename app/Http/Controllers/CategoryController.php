<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // دالة لإنشاء كاتيجوري جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'تم إنشاء الفئة بنجاح!');
    }

    // دالة لجلب كل الفئات
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    // دالة لعرض تفاصيل فئة معينة
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.show', compact('category'));
    }
}
