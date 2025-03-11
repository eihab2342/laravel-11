@extends('layouts.sideBar')
@section('title', 'الطلبات')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">تعديل الفئة</h2>

            <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium mb-2">اسم الفئة</label>
                    <input type="text" id="name" name="name" value="{{ $category->name }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-medium mb-2">الوصف</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ $category->description }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-gray-700 font-medium mb-2">الصورة</label>
                    <input type="file" id="image" name="image"
                        class="w-full border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        onchange="previewImage(event)">
                </div>

                <!-- عرض الصورة المختارة -->
                <div id="imagePreview" class="mt-4">
                    @if ($category->image)
                        <img src="{{ asset('storage/categories/' . $category->image) }}" alt="صورة الفئة"
                            class="w-32 h-32 rounded-md shadow-md">
                    @endif
                </div>

                <div class="flex justify-center mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                        تحديث
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- إضافة سكربت لمعاينة الصورة -->
    <script>
        function previewImage(event) {
            const output = document.getElementById('imagePreview');
            output.innerHTML = '';

            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    const img = document.createElement('img');
                    img.src = reader.result;
                    img.classList.add('w-32', 'h-32', 'rounded-md', 'shadow-md');
                    output.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
