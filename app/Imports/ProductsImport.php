<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow, WithDrawings, WithChunkReading
{
    private $drawings = [];
    private $categoryId; // متغير لتخزين الـ category_id المحدد أثناء الرفع
    private $categoryName; // متغير لتخزين اسم الفئة المحددة أثناء الرفع

    // **استقبال category_id في الكونستركتور لتحديد الفئة في حالة الرفع الخاص بفئة معينة**
    public function __construct($categoryId = null)
    {
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryId ? Category::findOrFail($categoryId)->name : null;
    }
    public function model(array $row)
    {
        // الحصول على رقم الصف من المفتاح الأول في المصفوفة
        $rowIndex = array_key_first($row);

        $product = Product::create([
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'old_price' => $row['old_price'] ?? null,
            'discount' => $row['discount'] ?? null,
            'category_id' => $this->categoryId ?? $row['category_id'],
            'category' => $row['category'] ?? $this->categoryName,
            'rating' => $row['rating'] ?? '0',
            'keywords' => $row['keywords'],
            'product_position' => $row['product_position'] ?? 'new-arrivals',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // حفظ الصور إذا كانت موجودة في ملف Excel
        if (isset($this->drawings[$rowIndex])) {
            foreach ($this->drawings[$rowIndex] as $drawing) {
                try {
                    $imageExt = pathinfo($drawing->getPath(), PATHINFO_EXTENSION) ?? 'jpg';
                    $imagePath = 'products/' . uniqid() . '.' . $imageExt;

                    // حفظ الصورة في التخزين
                    Storage::disk('public')->put($imagePath, file_get_contents($drawing->getPath()));

                    // حفظ الصورة في قاعدة البيانات
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $imagePath,
                        'created_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    Log::error("فشل حفظ الصورة: " . $e->getMessage());
                }
            }
        }

        return $product;
    }

    // **استخراج الصور من ملف Excel**
    public function drawings()
    {
        return function (Drawing $drawing) {
            $rowNumber = $drawing->getCoordinates()[1]; // استخراج رقم الصف المرتبط بالصورة
            $this->drawings[$rowNumber][] = $drawing;
        };
    }

    public function chunkSize(): int
    {
        return 100;
    }
}


























// namespace App\Imports;

// use App\Models\Product;
// use App\Models\ProductImage;
// use Maatwebsite\Excel\Concerns\ToModel;  // بتستخدم عشان تحول البيانات من ملف Excel إلى موديل Product
// use Maatwebsite\Excel\Concerns\WithDrawings;  // بتستخدم عشان نخرج الصور من ملف Excel
// use Maatwebsite\Excel\Concerns\WithChunkReading; // بتخلي الإستيراد يتم على دفعات بدل ما يتحمل بالكامل مره واحد ودا عشان يحسن الاداء
// use PhpOffice\PhpSpreadsheet\Worksheet\Drawing; // بتستخدم عشان تتعامل مع الصور واستخراج الصور من ملف Excel
// use Illuminate\Support\Facades\Storage;  // بتستخدم عشان تتعامل مع التخزين
// use Illuminate\Support\Facades\Log; // بتستخدم عشان تسجل الأخطاء في السجل
// use Maatwebsite\Excel\Concerns\ToArray;     // بتستخدم عشان تحول البيانات من ملف Excel إلى مصفوفة
// use Maatwebsite\Excel\Concerns\WithHeadingRow; // عشان المكتبه تفهم ان اول صف من الملف هو اسماء الصفوف

    // **   كلاس عشان يقوم بإستيراد البيانات من ملف Excel**
// class ProductsImport implements ToModel, WithHeadingRow, WithDrawings, WithChunkReading
// {
//     // **متغير لحفظ الصور المستخرجة من ملف Excel**
//     private $drawings = [];

//     // **دالة لتحويل البيانات من ملف Excel إلى موديل Product**
//     public function model(array $row)
//     {
//         $product = Product::create([
//             'name' => $row['name'],
//             'description' => $row['description'],
//             'price' => $row['price'],
//             'old_price' => $row['old_price'] ?? null,
//             'discount' => $row['discount'] ?? null,
//             'category_id' => $row['category_id'],
//             'rating' => $row['rating'] ?? '0',
//             'keywords' => $row['keywords'],
//             'product_position' => $row['product_position'] ?? 'new-arrivals',
//             'created_at' => now(),
//             'updated_at' => now(),
//         ]);

//         // **حفظ الصور إذا كانت موجودة في ملف Excel**
//         if (isset($this->drawings[$row['row_number']])) {
//             foreach ($this->drawings[$row['row_number']] as $drawing) {
//                 try {
//                     $imageExt = pathinfo($drawing->getPath(), PATHINFO_EXTENSION) ?? 'jpg';
//                     $imagePath = 'products/' . uniqid() . '.' . $imageExt;

//                     // حفظ الصورة في التخزين
//                     Storage::disk('public')->put($imagePath, file_get_contents($drawing->getPath()));

//                     // حفظ الصورة في قاعدة البيانات
//                     ProductImage::create([
//                         'product_id' => $product->id,
//                         'image_path' => $imagePath
//                     ]);
//                 } catch (\Exception $e) {
//                     Log::error("فشل حفظ الصورة: " . $e->getMessage());
//                 }
//             }
//         }

//         return $product;
//     }

//     // **استخراج الصور من ملف Excel**
//     public function drawings()
//     {
//         return function (Drawing $drawing) {
//             $rowNumber = $drawing->getCoordinates()[1]; // استخراج رقم الصف المرتبط بالصورة
//             $this->drawings[$rowNumber][] = $drawing;
//         };
//     }

//     public function chunkSize(): int
//     {
//         return 100;
//     }
// }
