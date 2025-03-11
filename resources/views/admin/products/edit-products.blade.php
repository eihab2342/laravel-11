@extends('layouts.sideBar')

@section('title', 'تعديل بيانات المنتج')

@section('content')

    <body class="bg-gray-100 font-sans">
        <!-- شريط التنقل العلوي -->
        <nav class="bg-white p-4 shadow flex justify-between items-center">
            <div class="text-lg font-bold">Shopx Demo</div>
            <div class="flex items-center space-x-4">
                <a href="#" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">الرجوع
                    للقائمة</a>
            </div>
        </nav>

        <div class="container mx-auto mt-6 p-6">
            <div class="bg-white p-6 shadow-md rounded-lg">
                <h2 class="text-2xl font-semibold mb-6">تعديل بيانات المنتج</h2>

                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- اسم المنتج -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700">اسم المنتج</label>
                            <input type="text" id="name" name="name" value="{{ $product->name }}"
                                placeholder="أدخل اسم المنتج"
                                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        </div>

                        <!-- وصف المنتج -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700">وصف المنتج</label>
                            <textarea id="description" name="description" placeholder="أدخل وصف المنتج"
                                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500" rows="4" required>{{ $product->description }}</textarea>
                        </div>

                        <!-- سعر المنتج -->
                        <div>
                            <label for="price" class="block text-sm font-semibold text-gray-700">سعر المنتج</label>
                            <input type="number" id="price" name="price" value="{{ $product->price }}"
                                placeholder="مثال: 150"
                                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500" step="0.01"
                                required>
                        </div>

                        <!-- السعر القديم -->
                        <div>
                            <label for="old_price" class="block text-sm font-semibold text-gray-700">السعر القديم</label>
                            <input type="number" id="old_price" name="old_price" value="{{ $product->old_price }}"
                                placeholder="مثال: 200"
                                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500" step="0.01">
                        </div>

                        <!-- الكمية -->
                        <div>
                            <label for="stock" class="block text-sm font-semibold text-gray-700">الكمية</label>
                            <input type="number" id="stock" name="stock" value="{{ $product->stock }}"
                                placeholder="عدد القطع المتاحة"
                                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        </div>

                        <!-- SKU -->
                        <div>
                            <label for="sku" class="block text-sm font-semibold text-gray-700">رقم تعريف المنتج
                                (SKU)</label>
                            <input type="text" id="sku" name="sku" value="{{ $product->sku }}"
                                placeholder="مثال: ABC123"
                                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="col-span-2">
                            <label for="images" class="block text-gray-700 font-medium">رفع الصور</label>
                            <input type="file" id="images" name="images[]" multiple
                                class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                onchange="previewImages(event)">
                            <small class="text-gray-500 block mt-2">يمكنك رفع أكثر من صورة وسيتم عرضها أدناه.</small>

                            <!-- عرض الصور المحفوظة مسبقًا -->
                            <div class="mt-4">
                                <h3 class="text-gray-700 font-medium mb-2">الصور الحالية:</h3>

                                <div id="preview-container" class="flex flex-nowrap gap-3 overflow-x-auto p-2">
                                    @foreach ($product->images as $image)
                                        <div class="relative group w-32">
                                            <img src="{{ asset('storage/products/' . $image->image) }}" alt="صورة المنتج"
                                                class="w-32 h-32 object-cover rounded-lg shadow-md">

                                            <!-- زر حذف الصورة -->
                                            <form action="{{ route('products.image.delete', $image->id) }}" method="POST"
                                                onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
                                                    ×
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- معاينة الصور الجديدة -->
                            <div id="imagePreview" class="flex flex-nowrap gap-3 overflow-x-auto p-2">
                            </div>
                        </div>

                        <script>
                            function previewImages(event) {
                                let previewContainer = document.getElementById('imagePreview');
                                previewContainer.innerHTML = ''; // مسح المعاينات السابقة
                                let files = event.target.files;

                                for (let i = 0; i < files.length; i++) {
                                    let reader = new FileReader();
                                    reader.onload = function(e) {
                                        let img = document.createElement('img');
                                        img.src = e.target.result;
                                        img.classList.add('w-32', 'h-32', 'object-contain', 'rounded-lg', 'shadow-md');
                                        previewContainer.appendChild(img);
                                    };
                                    reader.readAsDataURL(files[i]);
                                }
                            }
                        </script>

                        <!-- حالة المنتج -->
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" class="mr-2"
                                {{ $product->is_active ? 'checked' : '' }}>
                            <label for="is_active" class="text-sm font-semibold text-gray-700">المنتج نشط</label>
                        </div>

                        <!-- الوزن -->
                        <div>
                            <label for="weight" class="block text-sm font-semibold text-gray-700">وزن المنتج</label>
                            <input type="number" id="weight" name="weight" value="{{ $product->weight }}"
                                placeholder="مثال: 2.5 كجم"
                                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500" step="0.01">
                        </div>

                        <!-- العلامة التجارية -->
                        <div>
                            <label for="brand" class="block text-sm font-semibold text-gray-700">العلامة
                                التجارية</label>
                            <input type="text" id="brand" name="brand" value="{{ $product->brand }}"
                                placeholder="مثال: سامسونج"
                                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- الكلمات الدلالية -->
                        <div>
                            <label for="tags" class="block text-sm font-semibold text-gray-700">الكلمات
                                الدلالية</label>
                            <textarea id="tags" name="tags" placeholder="مثال: هاتف، ذكي، سامسونج"
                                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500" rows="3">{{ $product->tags }}</textarea>
                        </div>

                    </div>

                    <!-- زر الإرسال -->
                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                            تعديل المنتج
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");

            form.addEventListener("submit", function(event) {
                event.preventDefault();

                let formData = new FormData(form);

                fetch("{{ route('products.update', $product->id) }}", {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.errors) {
                            // عرض الأخطاء باستخدام `Toast`
                            Object.values(data.errors).forEach(error => {
                                toastr.error(error[0]);
                            });
                        } else if (data.message) {
                            // عرض رسالة النجاح
                            toastr.success(data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        toastr.error("حدث خطأ أثناء تحديث المنتج.");
                    });
            });
        });
    </script>

@endsection
