@extends('layouts.sideBar')
@section('title', 'الفئات')

@section('content')

    <div class="container mx-auto p-6 bg-gray-900 text-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4">إدارة المنتجات حسب الفئة</h2>

        <!-- فلتر الفئات -->
        {{-- <div class="mb-4">
            <label for="category" class="block text-sm font-medium">اختر الفئة:</label>
            <select id="category" name="category" class="w-full p-2 bg-gray-800 border border-gray-600 rounded">
                <option value="">كل الفئات</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div> --}}

        <!-- جدول المنتجات -->
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-700">
                <thead>
                    <tr class="bg-gray-800 text-left">
                        <th class="p-3 border-b border-gray-700">#</th>
                        <th class="p-3 border-b border-gray-700">الصورة</th>
                        <th class="p-3 border-b border-gray-700">اسم المنتج</th>
                        <th class="p-3 border-b border-gray-700">السعر</th>
                        <th class="p-3 border-b border-gray-700">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr class="hover:bg-gray-800">
                            {{-- <td class="p-3 border-b border-gray-700">{{ $loop->iteration }}</td> --}}
                            <td class="p-3 border-b border-gray-700">
                                <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-12 h-12 rounded-md">
                            </td>
                            <td class="p-3 border-b border-gray-700">{{ $product->name }}</td>
                            <td class="p-3 border-b border-gray-700">${{ $product->price }}</td>
                            <td class="p-3 border-b border-gray-700 flex gap-2">
                                <a href="{{ route('products.edit', $product->id) }}"
                                    class="px-3 py-1 bg-blue-600 text-white rounded">تعديل</a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                    onsubmit="return confirm('هل أنت متأكد؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
