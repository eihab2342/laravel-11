<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage; // تأكد من أنك أضفت الـ Model للصور
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    /**
     * عرض جميع المنتجات.
     */
    public function index(Request $request)
    {
        $products = Product::with('images')->get();
        return view('admin.products.products', compact('products'));
    }

    /**
     * عرض نموذج إضافة منتج جديد.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * تخزين منتج جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'category' => 'required|string|max:255',
            'rating' => 'nullable|string|max:255',
            'keywords' => 'nullable|string|max:255',
            'product_position' => 'required|in:trending,best-selling,new-arrivals',
            'position' => 'nullable|string|max:255',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // صور المنتج
        ]);

        // حفظ البيانات في قاعدة البيانات
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->old_price = $request->old_price;
        $product->discount = $request->discount;
        $product->category = $request->category;
        $product->rating = $request->rating;
        $product->keywords = $request->keywords;
        $product->product_position = $request->product_position;
        $product->position = $request->position;
        $product->save();

        // معالجة رفع الصور
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public'); // حفظ الصورة في مجلد storage/app/public/products
                $imagePaths[] = $path;
            }
            $product->images = json_encode($imagePaths); // حفظ المسارات في قاعدة البيانات بصيغة JSON
            $product->save();
        }

        return redirect()->route('products.index')->with('success', 'تمت إضافة المنتج بنجاح!');
    }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'description' => 'required|string',
    //         'price' => 'required|numeric',
    //         'quantity' => 'required|integer',
    //     ]);

    //     // حفظ المنتج أولًا بدون الصور
    //     $product = Product::create([
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'price' => $request->price,
    //         'quantity' => $request->quantity,
    //     ]);

    //     // حفظ الصور إذا تم رفعها
    //     if ($request->hasFile('images')) {
    //         foreach ($request->file('images') as $image) {
    //             $imagePath = $image->store('products', 'public'); // يخزنها في storage/app/public/products

    //             // تخزين المسار في جدول الصور
    //             $product->images()->create(['image' => $imagePath]);
    //         }
    //     }

    //     return redirect()->route('products')->with('success', 'تمت إضافة المنتج بنجاح!');
    // }

    /**
     * عرض تفاصيل منتج معين.
     */
    public function show(Product $product)
    {
        // $product = Product::with('images')->find($product->id);
        return view('admin.products.show-product', compact('product'));
    }
    //    public function ShowToUser($name)
    public function ShowToUser($name)
    {
        // جلب المنتج الحالي بناءً على الاسم
        $product = Product::with('images', 'category')->where('name', $name)->firstOrFail();

        // جلب المنتجات المشابهة (نفس الفئة) مع استثناء المنتج الحالي
        $relatedProducts = Product::with('images')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id) // استثناء المنتج الحالي
            // ->limit(6) // تحديد عدد المنتجات المشابهة المعروضة
            ->get();

        // تمرير البيانات إلى الـ View
        return view('user.product-details', compact('product', 'relatedProducts'));
    }


    /**
     * عرض نموذج تعديل المنتج.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit-products', compact('product'));
    }

    /**
     * تحديث المنتج في قاعدة البيانات.
     */
    public function update(Request $request, Product $product)
    {
        // التحقق من صحة المدخلات
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        // تحديث المنتج
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * حذف المنتج من قاعدة البيانات.
     */
    /**
     * حذف صورة منتج معينة.
     */
    public function destroy(Product $product)
    {
        // جلب جميع الصور المرتبطة بالمنتج
        foreach ($product->images as $image) {
            // حذف الصورة من المجلد
            Storage::disk('public')->delete('products/' . $image->image);
        }

        // حذف جميع الصور من قاعدة البيانات
        $product->images()->delete();

        // حذف المنتج نفسه
        $product->delete();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('products')->with('success', 'تم حذف المنتج وصوره بنجاح');
    }

    /**
     * حذف صورة منتج معينة.
     */
    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id); // البحث عن الصورة

        // حذف الصورة من التخزين
        if (Storage::exists('public/' . $image->image)) {
            Storage::delete('public/' . $image->image);
        }

        // حذف الصورة من قاعدة البيانات
        $image->delete();

        return back()->with('success', 'تم حذف الصورة بنجاح!');
    }
}
