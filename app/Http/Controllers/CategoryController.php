<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\product;
use Illuminate\Http\Request;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{
    // دالة لإنشاء كاتيجوري جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
            'description' => 'nullable|string',
            'image' => 'required'
        ]);

        // **الحصول على اسم الصورة الأصلي**
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName(); // اسم الصورة مع توقيت فريد
        $image->move(public_path('storage/categories'), $imageName); // نقل الصورة إلى المجلد

        // **إنشاء الفئة مع اسم الصورة فقط**
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imageName, // تخزين الاسم فقط في قاعدة البيانات
        ]);

        return redirect()->back()->with('success', 'تم إنشاء الفئة بنجاح!');
    }

    // دالة لجلب كل الفئات
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.category', compact('categories'));
    }

    public function showProducts($category_id)
    {
        $categories = Category::all();
        // جلب المنتجات التابعة لهذه الفئة
        $products = Product::where('category_id', $category_id)->get();

        return view('admin.categories.category-products', compact(['categories', 'products']));
    }


    // دالة لعرض تفاصيل فئة معينة
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.show', compact('category'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.categories.add-categories');
        // ->with(view('admin.products.add-products', compact('categories')));
    }

    public function edit($id, $name)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => "required|unique:categories,name,$id|max:255",
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // تحديث البيانات
        $category->name = $request->name;
        $category->description = $request->description;

        // تحديث الصورة إذا تم رفع صورة جديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إن وجدت
            if ($category->image) {
                $oldImagePath = public_path('storage/categories/' . $category->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // رفع الصورة الجديدة وحفظ اسمها فقط
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/categories'), $imageName);

            $category->image = $imageName; // حفظ الاسم فقط
        }

        $category->save();

        return redirect()->back()->with('success', 'تم تحديث الفئة بنجاح!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'تم حذف الفئة بنجاح!');
    }

    // دالة لعرض نموذج استيراد المنتجات
    public function showImportFormForCategory($categoryName)
    {
        $categoryId = Category::where('name', $categoryName)->first()->id;
        return view(
            'admin.categories.import',
            [
                'categoryName' => $categoryName,
                'categoryId' => $categoryId
            ]
        );
    }

    // دالة لاستيراد المنتجات
    public function importproducts(Request $request)
    {
        $categoryId = $request->input('category_id');
        // الكود هنا
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        Excel::import(new ProductsImport($categoryId), $request->file('file'));
        return redirect()->back()->with('success', 'تم استيراد المنتجات بنجاح');
    }

    public function deleteImage($id)
    {
        $category = Category::findOrFail($id);

        // حذف الصورة من التخزين
        if ($category->image) {
            Storage::delete('public/categories/' . $category->image);
            $category->image = null;
            $category->save();
        }

        return redirect()->route('categories.edit', $category->id)->with('success', 'تم حذف الصورة بنجاح.');
    }
}
