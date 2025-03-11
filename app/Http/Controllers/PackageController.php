<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Package;
use App\Models\PackageImages;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class PackageController extends Controller
{
    //
    public function index()
    {
        $packages = Package::with('images')->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function ShowPackageToUser($name)
    {
        // البحث عن المنتج أو الباكدج بناءً على الاسم
        // $product = Product::with('images', 'category')->where('name', $name)->first();
        $package = Package::with('images')->where('name', $name)->first();

        if ($package) {
            // إذا كان منتجًا، جلب المنتجات المشابهة في نفس الفئة
            $relatedPackages = Product::with('images')
                // ->where('category_id', $package->category_id)
                ->where('id', '!=', $package->id)
                ->get();

            return view('user.package-details', compact('package', 'relatedPackages'));
        } else {
            // في حالة عدم العثور على منتج أو باكدج، عرض صفحة 404
            abort(404);
        }
    }


    public function create()
    {
        $products = Product::all();
        return view('admin.packages.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'status' => 'in:active,inactive',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // إنشاء الباكدج
        $package = Package::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        // حفظ الصور
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName(); // اسم الصورة فقط
                $image->storeAs('packages', $imageName, 'public'); // حفظها في فولدر packages

                // حفظ اسم الصورة فقط في قاعدة البيانات
                PackageImages::create([
                    'package_id' => $package->id,
                    'image_path' => $imageName, // تخزين الاسم فقط بدون المسار
                ]);
            }
        }

        return redirect()->route('packages.index')->with('success', 'تمت إضافة الباكدج بنجاح!');
    }
    public function edit($id)
    {
        // جلب بيانات الباكدج من قاعدة البيانات
        $package = Package::findOrFail($id);

        // إرسال البيانات إلى صفحة التعديل
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        // تحديث بيانات الباكدج
        $package->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        // حذف الصور المحددة من الطلب
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = PackageImages::find($imageId);
                if ($image) {
                    Storage::delete('public/packages/' . $image->image_path);
                    $image->delete();
                }
            }
        }

        // رفع الصور الجديدة (إن وُجدت)
        // حفظ الصور
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName(); // اسم الصورة فقط
                $image->storeAs('packages', $imageName, 'public'); // حفظها في فولدر packages

                // حفظ اسم الصورة فقط في قاعدة البيانات
                PackageImages::create([
                    'package_id' => $package->id,
                    'image_path' => $imageName, // تخزين الاسم فقط بدون المسار
                ]);
            }
        }

        return redirect()->route('packages.edit', $package->id)->with('success', 'تم تحديث الباكدج بنجاح!');
    }
    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();

        return redirect()->route('packages.index')->with('success', 'تم حذف الباكدج بنجاح');
    }
}
