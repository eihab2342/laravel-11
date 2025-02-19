<x-app-layout>

    <div class="bg-gray-100 min-h-screen flex items-center justify-center py-10">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white p-6 rounded-lg shadow-lg">

                <!-- صور المنتج -->
                <div>
                    <img src="{{ asset('storage/products/' . $product->images->first()->image) }}"
                        alt="{{ $product->name }}" class=" h-auto rounded-lg shadow-md cursor-pointer" id="mainImage"
                        onclick="openModal('{{ asset('storage/products/' . $product->images->first()->image) }}')">

                    <!-- الصور المصغرة -->
                    <div class="flex gap-2 py-4 justify-center overflow-x-auto">
                        @if ($product->images->count() > 1)
                            @foreach ($product->images as $image)
                                <img src="{{ asset('storage/products/' . $image->image) }}" alt="Thumbnail"
                                    class="thumbnail w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-md cursor-pointer opacity-60 hover:opacity-100 transition duration-300">
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- تفاصيل المنتج -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h2>
                    <p class="text-gray-500 mb-4">SKU: {{ $product->sku }}</p>

                    <!-- السعر -->
                    <div class="mb-4">
                        <span class="text-2xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                        @if ($product->old_price)
                            بدلا من
                            <span
                                class="text-gray-400 line-through ml-2">${{ number_format($product->old_price, 2) }}</span>
                        @endif
                    </div>

                    <!-- التقييم -->
                    <div class="flex items-center mb-4">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-6 {{ $i <= $product->rating ? 'text-yellow-500' : 'text-gray-300' }}">
                                <path fill-rule="evenodd"
                                    d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endfor
                        <span class="ml-2 text-gray-600">{{ $product->rating }} (تقييم)</span>
                    </div>

                    <p class="text-gray-700 mb-6">{{ $product->description }}</p>

                    <!-- زر الشراء -->
                    <button
                        class="shopping-cart-icon bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition duration-300"
                        data-product-id="{{ $product->id }}">
                        إضافة إلى السلة
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- مودال الصورة -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden">
        <div class="relative">
            <button onclick="closeModal()"
                class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full">X</button>
            <img id="modalImage" class="max-w-full max-h-[90vh] rounded-lg shadow-lg">
        </div>
    </div>

    {{--  --}}
    {{-- cards --}}
    <div class="swiper-container core-swiper-container mb-5"
        style="overflow: hidden; margin-top: 40px; margin-bottom: 40px;">
        <div class="swiper-wrapper mb-6" style="margin-bottom: 30px">
            @foreach ($relatedProducts as $product)
                <div class="swiper-slide core-swiper-slide">
                    <div class="core-product-card bg-white p-2 rounded-lg shadow-lg" style="height: 310px">
                        <a href="{{ route('product.details', $product->name) }}">
                            <div class="core-product-image mb-4">
                                @if (!empty($product->images) && $product->images->count() > 0)
                                    <img src="{{ asset('storage/products/' . $product->images->first()->image) }}"
                                        alt="{{ $product->name }}" class="w-full h-auto rounded-lg"
                                        style="height: 130px;">
                                @else
                                    <span class="text-gray-500">لا توجد صورة</span>
                                @endif
                            </div>
                        </a>
                        <div class="core-product-title text-sm font-semibold mb-2 line-clamp">
                            {{ $product->name }}
                        </div>
                        <div class="core-product-rating text-yellow-500">★★★★★ (10)</div>
                        <div class="core-product-price text-lg font-bold text-green-500">
                            {{ $product->price }} <span
                                class="core-old-price line-through text-gray-500">{{ $product->old_price }}</span>
                        </div>
                        <div class="core-discount text-red-500">
                            33% off
                        </div>
                        <div class="cart-container flex items-center">
                            <span class="shopping-cart-icon" id="add-to-cart"
                                data-product-id="{{ $product->id }}">🛒</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.core-swiper-container', {
            slidesPerView: 2.5,
            spaceBetween: 10,
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                280: { // الهواتف الصغيرة جدًا
                    slidesPerView: 1.5
                },
                360: { // الهواتف الصغيرة
                    slidesPerView: 2.2
                },
                480: { // الهواتف المتوسطة
                    slidesPerView: 3
                },
                640: { // الهواتف الكبيرة
                    slidesPerView: 4
                },
                768: { // التابلت
                    slidesPerView: 5
                },
                1024: { // اللابتوب الصغير
                    slidesPerView: 6
                },
                1280: { // شاشات أكبر
                    slidesPerView: 7
                },
                1440: { // شاشات FHD
                    slidesPerView: 8
                },
                1600: { // الشاشات الكبيرة جدًا
                    slidesPerView: 9
                },
                1920: { // شاشات 4K وما فوق
                    slidesPerView: 10
                }
            }
        });
    </script>

    {{-- end cards --}}


</x-app-layout>
