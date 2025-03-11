<?php

namespace App\Http\Controllers;

use App\Models\images;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $images = images::all();
        return view('admin.settings.add-image', compact('images'));
    }

    // public function DisplayInagesToUser() {
    //     $images = images::all();
    //     return view('user', compact('images'));
    // }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        // 🔹 تحقق من صحة البيانات
        $request->validate([
            'image' => 'required',
            'location' => 'required|string',
            'belongsTo' => 'nullable|string|max:255',
        ]);

        // 🔹 حفظ الصورة في مجلد `storage/app/public/images`
        $path = $request->file('image')->store('uploads', 'public');

        // 🔹 تخزين معلومات الصورة في قاعدة البيانات
        images::create([
            'image' => $path,
            'location' => $request->location,
            'belongsTo' => $request->description,
        ]);

        // 🔹 إعادة توجيه مع رسالة نجاح
        return redirect()->back()->with('success', 'تم رفع الصورة بنجاح!');
    }

    /**
     * Display the specified resource.
     */
    public function show(images $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(images $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, images $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(images $image)
    {
        //

    }
    /**
     * حذف صورة منتج معينة.
     */
    public function deleteImage($id)
    {
        $image = images::findOrFail($id); // البحث عن الصورة

        // حذف الصورة من التخزين
        $imagePath = storage_path('app/public/' . $image->image);

        if (file_exists($imagePath) && is_file($imagePath)) {
            Storage::disk('public')->delete($image->image);
        } else {
            // dd("File not found or not a file.");
        }

        // حذف الصورة من قاعدة البيانات
        $image->delete();

        return back()->with('success', 'تم حذف الصورة بنجاح!');
    }
}
