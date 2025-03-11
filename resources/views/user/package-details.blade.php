<x-app-layout>
    <div class="bg-gray-100 min-h-screen flex items-center justify-center py-5">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white p-6 rounded-lg shadow-lg">

                <!-- صور الباكدج -->
                <div>
                    @if ($package->images->isNotEmpty())
                        <img src="{{ asset('storage/packages/' . $package->images->first()->image_path) }}"
                            alt="{{ $package->name }}" class="h-auto rounded-lg shadow-md cursor-pointer" id="mainImage"
                            onclick="changeMainImage('{{ asset('storage/packages/' . $package->images->first()->image_path) }}')">

                        <!-- الصور المصغرة -->
                        <div class="flex gap-2 py-4 justify-center overflow-x-auto">
                            @foreach ($package->images as $image)
                                <img src="{{ asset('storage/packages/' . $image->image_path) }}" alt="Thumbnail"
                                    class="thumbnail w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-md cursor-pointer opacity-60 hover:opacity-100 transition duration-300"
                                    onclick="changeMainImage('{{ asset('storage/packages/' . $image->image_path) }}')">
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- تفاصيل الباكدج -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $package->name }}</h2>
                    <p class="text-gray-700 mb-6">{{ $package->description }}</p>

                    <!-- جدول المنتجات داخل الباكدج -->

                    {{-- <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300 text-center">
                            <thead>
                                <tr class="bg-gray-200 text-gray-700">
                                    <th class="border border-gray-300 p-3">الصورة</th>
                                    <th class="border border-gray-300 p-3">المنتج</th>
                                    <th class="border border-gray-300 p-3">السعر</th>
                                    <th class="border border-gray-300 p-3">الكمية</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ([['name' => 'منتج تجريبي 1', 'price' => 19.99, 'quantity' => 2], ['name' => 'منتج تجريبي 2', 'price' => 29.99, 'quantity' => 1], ['name' => 'منتج تجريبي 3', 'price' => 9.99, 'quantity' => 5]] as $product)
                                    <tr>
                                        <td class="border border-gray-300 p-3">
                                            <img src="https://picsum.photos/100/100" alt="{{ $product['name'] }}"
                                                class="w-16 h-16 rounded-md">
                                        </td>
                                        <td class="border border-gray-300 p-3">{{ $product['name'] }}</td>
                                        <td class="border border-gray-300 p-3">${{ number_format($product['price'], 2) }}</td>
                                        <td class="border border-gray-300 p-3">{{ $product['quantity'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> --}}

                    <!-- زر الشراء -->
                    <div class="flex justify-center mt-6">
                        {{-- <button
                            class="shopping-cart-icon flex items-center gap-2 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-green-700 transition duration-200 text-lg font-semibold"
                            class="shopping-cart-icon cursor-pointer" id="add-to-cart" data-type="package"
                            data-product-id="{{ $package->id }}">
                            🛒
                            إضافة الباكدج إلى السلة
                        </button> --}}
                        <div class="cart-container flex items-center justify-center mt-2">
                            <span class="shopping-cart-icon cursor-pointer" id="add-to-cart" data-type="package"
                                data-product-id="{{ $package->id }}">🛒</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- سكريبت لتغيير الصورة عند الضغط على المصغرات -->
    <script>
        function changeMainImage(imageUrl) {
            document.getElementById('mainImage').src = imageUrl;
        }
    </script>
</x-app-layout>
