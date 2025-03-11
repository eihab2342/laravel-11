<x-app-layout>
    <div class="container mx-auto p-4">
        <h2
            class="text-3xl font-extrabold text-gray-900 flex items-center gap-3 mb-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-3 rounded-lg shadow-md">
            <i class="fas fa-tags text-2xl"></i> <!-- أيقونة الفئة -->
            <span>{{ $category_name }}</span>
        </h2>

        <!-- شبكة عرض المنتجات -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            @foreach ($products as $product)
                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg  relative group">
                    <!-- شارة الخصم -->
                    @if ($product->old_price && $product->old_price > $product->price)
                        @php
                            $discount = round((($product->old_price - $product->price) / $product->old_price) * 100);
                        @endphp
                        <div class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ $discount }}% خصم
                        </div>
                    @endif

                    <a href="{{ route('product.details', $product->name) }}" class="block">
                        <div class="mb-4">
                            @if ($product->images->isNotEmpty())
                                <img src="{{ asset('storage/products/' . $product->images->first()->image) }}"
                                    alt="{{ $product->name }}" class="w-full h-40 object-contain rounded-lg ">
                            @else
                                <span class="text-gray-500 text-sm">لا توجد صورة</span>
                            @endif
                        </div>
                    </a>

                    <!-- اسم المنتج -->
                    <h3 class="text-sm font-semibold mb-2 text-gray-900 truncate">
                        {{ $product->name }}
                    </h3>

                    <!-- التقييم -->
                    <div class="flex items-center space-x-1 text-yellow-500 text-sm">
                        <span>★★★★★</span>
                        <span class="text-gray-500">(10)</span>
                    </div>

                    <!-- السعر -->
                    <div class="mt-2">
                        <span class="text-md font-bold text-green-500">
                            {{ number_format($product->price, 2) }} جنيه
                        </span>
                        @if ($product->old_price && $product->old_price > $product->price)
                            <span class="line-through text-gray-500 text-sm">
                                {{ number_format($product->old_price, 2) }} جنيه
                            </span>
                        @endif
                    </div>

                    <!-- زر الإضافة إلى السلة -->
                    <div class="cart-container flex items-center justify-center mt-2">
                        <span class="shopping-cart-icon cursor-pointer" id="add-to-cart" data-type="product"
                            data-product-id="{{ $product->id }}">🛒</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- أزرار التنقل بين الصفحات -->
        <div class="mt-6 flex justify-center">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>











{{-- <x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">منتجات فئة {{ $category_name }}</h2>


            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                @foreach ($products as $product)
                    <div class="bg-white p-4 rounded-lg shadow-lg relative">
                        <!-- Badge for Discount -->
                        @if ($product->old_price && $product->old_price > $product->price)
                            @php
                                $discount = round(
                                    (($product->old_price - $product->price) / $product->old_price) * 100,
                                );
                            @endphp
                            <div class="text-white bg-red-500 px-2 py-1 rounded-full absolute top-2 right-2 text-xs">
                                {{ $discount }}% خصم
                            </div>
                        @endif

                        <a href="{{ route('product.details', $product->name) }}">
                            <div class="mb-4">
                                @if (!empty($product->images) && $product->images->count() > 0)
                                    <img src="{{ asset('storage/products/' . $product->images->first()->image) }}"
                                        alt="{{ $product->name }}" class="w-full h-40 object-contain rounded-lg">
                                @else
                                    <span class="text-gray-500">لا توجد صورة</span>
                                @endif
                            </div>
                        </a>

                        <!-- Product Name -->
                        <div class="text-sm font-semibold mb-2 text-gray-900 line-clamp">
                            {{ $product->name }}
                        </div>

                        <!-- Rating -->
                        <div class="text-yellow-500 text-sm">★★★★★ (10)</div>

                        <!-- Price -->
                        <div class="text-md font-bold text-green-500">
                            {{ number_format($product->price, 2) }} جنيه
                            @if ($product->old_price && $product->old_price > $product->price)
                                <span class="line-through text-gray-500 text-sm">
                                    {{ number_format($product->old_price, 2) }} جنيه
                                </span>
                            @endif
                        </div>

                        <!-- Cart Icon (bottom-left) -->
                        <div class="flex justify-between items-center mt-3">
                            <span
                                class="cart-icon   cursor-pointer text-2xl text-blue-500 hover:text-blue-700 transition"
                                id="add-to-cart" data-product-id="{{ $product->id }}">🛒</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- أزرار التنقل بين الصفحات -->
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>
 --}}




{{-- <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
            @foreach ($products as $product)
                <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-xl transition duration-300">
                    <a href="{{ route('product.details', $product->name) }}">
                        <img src="{{ asset('storage/products/' . $product->images->first()->image) }}"
                            alt="{{ $product->name }}" class="w-full h-40 object-contain">
                    </a>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">{{ $product->name }}</h3>

                        @if ($product->old_price && $product->old_price > $product->price)
                            @php
                                $discount = round(
                                    (($product->old_price - $product->price) / $product->old_price) * 100,
                                );
                            @endphp
                            <div class="flex items-center space-x-2 mt-2">
                                <span
                                    class="text-sm line-through text-gray-500">{{ number_format($product->old_price, 2) }}
                                    جنيه</span>
                                <span class="text-lg font-bold text-red-600">{{ number_format($product->price, 2) }}
                                    جنيه</span>
                                <span
                                    class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $discount }}%
                                    خصم</span>
                            </div>
                        @else
                            <span class="text-lg font-bold text-blue-600">{{ number_format($product->price, 2) }}
                                جنيه</span>
                        @endif

                        <div class="flex justify-between items-center mt-3">
                            <div class="cart-container flex items-center">
                                <span
                                    class="shopping-cart-icon cursor-pointer text-2xl text-blue-500 hover:text-blue-700 transition"
                                    id="add-to-cart" data-product-id="{{ $product->id }}">🛒</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div> --}}
