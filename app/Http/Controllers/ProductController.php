<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\images;
use App\Models\Package;
use App\Models\Control;
use App\Models\ProductImage;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::all();
        // $categories = Category::all();
        return view('admin.products.products', compact('products'));
    }
    public function DisplayDataToUser()
    {
        //
        $products = Product::with('images')->get();
        $categories = Category::all();
        $images = images::all();
        $Ad_images = images::where('location', 'ad');
        $controls = Control::with(['category.products.images'])->get();
        // dd($controls);
        $packages = Package::with('images')->get();
        return view('user', [
            'products' => $products,
            'categories' => $categories,
            'images' => $images,
            'Ad_images' => $Ad_images,
            'controls' => $controls,
            'packages' => $packages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('admin.products.add-products', compact('categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
        // $product = Product::find($product->id);
        return view('admin.products.edit-products', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // ✅ التحقق من البيانات باستخدام Validator
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id, // تعديل شرط التحقق من الـ SKU (إستثناء المنتج الحالي)
            'image_url' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'brand' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after_or_equal:available_from',
            'keywords' => 'nullable|string',
        ]);

        // ✅ التحقق من وجود صورة جديدة وتحميلها
        if ($request->hasFile('image_url')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($product->image_url && Storage::exists('public/products/' . basename($product->image_url))) {
                Storage::delete('public/products/' . basename($product->image_url));
            }

            // رفع الصورة الجديدة
            $file = $request->file('image_url');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->storeAs('public/products', $filename);

            // تحديث المسار للصورة الجديدة
            $validatedData['image_url'] = "storage/products/" . $filename;
        }

        // ✅ تحديث المنتج باستخدام البيانات المُحققة
        $product->update($validatedData);

        // ✅ إرجاع استجابة بنجاح
        return response()->json([
            'message' => 'تم تحديث المنتج بنجاح',
            'product' => $product,
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // حذف الصورة إذا كانت موجودة
        if ($product->image_url && Storage::exists('public/products/' . basename($product->image_url))) {
            Storage::delete('public/products/' . basename($product->image_url));
        }

        // حذف المنتج من قاعدة البيانات
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'old_price' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $product = Product::create($request->only('name', 'description', 'price', 'old_price', 'discount', 'category_id'));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName(); // حفظ الاسم مع timestamp لمنع التكرار
                $path = $image->storeAs('products', $filename, 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $filename,
                ]);
            }
        }

        return redirect()->route('products')->with('success', 'تم إضافة المنتج بنجاح!');
    }

    /**
     * Display the specified Product.
     */
    // public function ShowToUser($name)
    // {
    //     $product = Product::with('images', 'category')->where('name', $name)->firstOrFail();
    //     $
    //     $relatedProducts = Product::with('images')
    //         ->where('category_id', $product->category_id)
    //         ->where('id', '!=', $product->id)
    //         // ->limit(6) // تحديد عدد المنتجات المشابهة المعروضة
    //         ->get();

    //     // تمرير البيانات إلى الـ View
    //     return view('user.product-details', compact('product', 'relatedProducts'));
    // }
    public function ShowToUser($name)
    {
        // البحث عن المنتج أو الباكدج بناءً على الاسم
        $product = Product::with('images', 'category')->where('name', $name)->first();
        $package = Package::with('images')->where('name', $name)->first();

        if ($product) {
            // إذا كان منتجًا، جلب المنتجات المشابهة في نفس الفئة
            $relatedProducts = Product::with('images')
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->get();

            return view('user.product-details', compact('product', 'relatedProducts'));
        } elseif ($package) {
            // إذا كان باكدج، جلب المنتجات الموجودة داخله
            $packageProducts = $package->products; // المنتجات المرفقة بالباكدج

            return view('user.package-details', compact('package', 'packageProducts'));
        } else {
            // في حالة عدم العثور على منتج أو باكدج، عرض صفحة 404
            abort(404);
        }
    }

    /**
     * Display the specified Product search.
     */

    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orWhere('keywords', 'LIKE', "%{$query}%")
            ->with('images')
            ->get();
        return response()->json($products);
    }

    public function showImportForm()
    {
        return view('admin.products.import');
    }

    //     // معالجة رفع الملف
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        Excel::import(new ProductsImport, $request->file('file'));

        return redirect()->back()->with('success', 'تم استيراد المنتجات بنجاح!');
    }

    public function ShowProductsToUser($category_id)
    {
        $products = Product::where('category_id', $category_id)->paginate(6);
        $category_name = Category::where('id', $category_id)->pluck('name')->first();
        return view('user.category-products', compact(['products', 'category_name']));
    }

    //     /**
    //      * حذف صورة منتج معينة.
    //      */
    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id); // البحث عن الصورة

        // حذف الصورة من التخزين
        if (Storage::exists('public/products/' . $image->image)) {
            Storage::delete('public/products/' . $image->image);
        }

        // حذف الصورة من قاعدة البيانات
        $image->delete();

        return back()->with('success', 'تم حذف الصورة بنجاح!');
    }
}
