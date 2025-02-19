@extends('layouts.sideBar')

@section('title', 'إضافة منتج جديد')

@section('content')
    <div class="max-w-4xl mx-auto p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">إضافة منتج جديد</h2>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
            class="grid grid-cols-2 gap-6">
            @csrf

            <!-- اسم المنتج -->
            <div class="col-span-2">
                <label for="name" class="block text-gray-700 font-medium">اسم المنتج</label>
                <input type="text" id="name" name="name"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- وصف المنتج -->
            <div class="col-span-2">
                <label for="description" class="block text-gray-700 font-medium">وصف المنتج</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
            </div>

            <!-- السعر والسعر القديم -->
            <div>
                <label for="price" class="block text-gray-700 font-medium">السعر</label>
                <input type="number" id="price" name="price" step="0.01"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label for="old_price" class="block text-gray-700 font-medium">السعر القديم</label>
                <input type="number" id="old_price" name="old_price" step="0.01"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- الخصم -->
            <div>
                <label for="discount" class="block text-gray-700 font-medium">الخصم</label>
                <input type="number" id="discount" name="discount"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- التصنيف -->
            <div>
                <label for="category_id" class="block text-gray-700 font-medium">التصنيف</label>
                <input type="number" id="category_id" name="category_id"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- التقييم -->
            <div>
                <label for="rating" class="block text-gray-700 font-medium">التقييم</label>
                <input type="number" id="rating" name="rating" step="0.1"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- الكلمات المفتاحية -->
            <div class="col-span-2">
                <label for="keywords" class="block text-gray-700 font-medium">الكلمات المفتاحية</label>
                <input type="text" id="keywords" name="keywords"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- مكان المنتج -->
            <div>
                <label for="product_position" class="block text-gray-700 font-medium">مكان المنتج</label>
                <select id="product_position" name="product_position"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="trending">Trending</option>
                    <option value="best-selling">Best Selling</option>
                    <option value="new-arrivals">New Arrivals</option>
                </select>
            </div>

            <!-- الموقع -->
            <div>
                <label for="position" class="block text-gray-700 font-medium">الموقع</label>
                <input type="text" id="position" name="position"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- المخزون -->
            <div>
                <label for="stock" class="block text-gray-700 font-medium">المخزون</label>
                <input type="number" id="stock" name="stock"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- رفع الصور -->
            <div class="col-span-2">
                <label for="images" class="block text-gray-700 font-medium">رفع الصور</label>
                <input type="file" id="images" name="images[]" multiple
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required
                    onchange="previewImages(event)">
                <small class="text-gray-500 block mt-2">يمكنك رفع أكثر من صورة وسيتم عرضها أدناه.</small>
            </div>

            <!-- عرض الصور المختارة -->
            <div class="col-span-2">
                <div id="preview-container" class="flex flex-wrap gap-3"></div>
            </div>

            <!-- زر الإرسال -->
            <div class="col-span-2 flex justify-center">
                <button type="submit"
                    class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 transition">
                    إضافة المنتج
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewImages(event) {
            const container = document.getElementById("preview-container");
            container.innerHTML = "";

            const files = event.target.files;
            if (files) {
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.classList.add("w-24", "h-24", "rounded-lg", "shadow-md", "object-cover", "border");
                        container.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }
    </script>
    <script>
        @if (session('success'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "4000", // 4 ثوانٍ
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr.success("{{ session('success') }}");
        @endif
    </script>

@endsection
