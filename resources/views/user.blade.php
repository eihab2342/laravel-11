<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('اهلا ، ') . Auth::user()->name}}
        </h2>
    </x-slot>  --}}



    {{-- swiper --}}
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>


    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.0/flowbite.min.js"></script>
    <style>
        .line-clamp {
            font-size: 14px;
            /* تصغير حجم الخط */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* تحديد عدد الأسطر إلى سطرين */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            /* إظهار (...) عند القطع */
            height: 2.8em;
            /* ضبط الارتفاع حسب حجم الخط */
        }

        .cart-container {
            display: flex;
            justify-content: flex-end;
            /* جعل الأيقونة في اليسار */
            align-items: center;
            margin-top: 10px;
            border-bottom: 1px solid #27d117;
        }

        .shopping-cart-icon {
            font-family: 'Figtree', sans-serif;
            font-size: 24px;
            color: #007bff;
            cursor: pointer;
            transition: transform 0.3s ease-in-out;
        }

        .shopping-cart-icon:hover {
            transform: scale(1.2);
        }

        /* ----------- */
        /* ستايل التوست */
        .toast {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            display: none;
            z-index: 9999;
        }

        .toast.success {
            background-color: #28a745;
            /* اللون الأخضر للنجاح */
        }

        .toast.error {
            background-color: #dc3545;
            /* اللون الأحمر للفشل */
        }
    </style>

    <div id="animation-carousel" class="relative w-full" data-carousel="static">
        <!-- Carousel wrapper -->
        <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
            <!-- Item 1 -->
            <div class="hidden duration-200 ease-linear" data-carousel-item>
                <img src="https://m.media-amazon.com/images/I/71JkN7b9O6L._SX3000_.jpg"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2  left-1/2" alt="...">
                {{-- <img src="https://flowbite.com/docs/images/carousel/carousel-3.svg"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="..."> --}}
            </div>
            <!-- Item 2 -->
            <div class="hidden duration-200 ease-linear" data-carousel-item>
                <img src="https://m.media-amazon.com/images/I/71PHEDkw1oL._SX3000_.jpg"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                {{-- <img src="https://flowbite.com/docs/images/carousel/carousel-2.svg"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="..."> --}}
            </div>
            <!-- Item 3 -->
            <div class="hidden duration-200 ease-linear" data-carousel-item="active">
                <img src="https://m.media-amazon.com/images/I/71Iq4w1LwqL._SX3000_.jpg"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                {{-- <img src="https://flowbite.com/docs/images/carousel/carousel-4.svg"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="..."> --}}
            </div>
            <!-- Item 4 -->
            <div class="hidden duration-200 ease-linear" data-carousel-item>
                <img src="https://flowbite.com/docs/images/carousel/carousel-5.svg"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                {{-- <img src="https://flowbite.com/docs/images/carousel/carousel-5.svg"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="..."> --}}
            </div>
            <!-- Item 5 -->
            <div class="hidden duration-200 ease-linear" data-carousel-item>
                <img src="https://m.media-amazon.com/images/I/71e6QExLA9L._SX3000_.jpg"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                {{-- <img src="https://flowbite.com/docs/images/carousel/carousel-1.svg"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="..."> --}}
            </div>
        </div>
        <!-- Slider controls -->
        <button type="button"
            class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-prev>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 1 1 5l4 4" />
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button"
            class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-next>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 9 4-4-4-4" />
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>

{{-- Category --}}
<!-- إضافة مكتبة Swiper -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-center mb-6">التوفير عليك و العروض لرمضان علينا</h2>

    <!-- Swiper Container -->
    <div class="relative">
        <!-- أزرار التنقل -->
        <div class="swiper-button-next category-next"></div>
        <div class="swiper-button-prev category-prev"></div>

        <div class="swiper core-swiper-container">
            <div class="swiper-wrapper">
                <!-- Category Card 1 -->
                <div class="swiper-slide bg-white shadow-lg rounded-lg p-4 transition-transform transform hover:scale-105">
                    <img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2025/Events/Q1/RMDSale/DBU-MKC/GW_Bubbler_Shop_all_deals_400x400._CB551250057_.jpg" alt="Category 1" class="w-full h-40 object-cover rounded-md">
                    {{-- <h3 class="mt-4 text-lg font-semibold text-center">Category 1</h3> --}}
                </div>
                <!-- Category Card 1 -->
                <div class="swiper-slide bg-white shadow-lg rounded-lg p-4 transition-transform transform hover:scale-105">
                    <img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2025/Events/Q1/RMDSale/DBU-MKC/2501RMD003_9_EG_RAM25_GW_Bubbler_CL_Supermarket_PricePoint_400x400_AR._CB551168070_.jpg" alt="Category 1" class="w-full h-40 object-cover rounded-md">
                    {{-- <h3 class="mt-4 text-lg font-semibold text-center">Category 1</h3> --}}
                </div>
                <!-- Category Card 1 -->
                <div class="swiper-slide bg-white shadow-lg rounded-lg p-4 transition-transform transform hover:scale-105">
                    <img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2025/Events/Q1/RMDSale/DBU-MKC/2501RMD003_21_EG_RAM25_GW_Bubbler_OHL_KitchenEssentials_PricePoint_400x400_AR._CB551168067_.jpg" alt="Category 1" class="w-full h-40 object-cover rounded-md">
                    {{-- <h3 class="mt-4 text-lg font-semibold text-center">Category 1</h3> --}}
                </div>
                <!-- Category Card 1 -->
                <div class="swiper-slide bg-white shadow-lg rounded-lg p-4 transition-transform transform hover:scale-105">
                    <img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2025/Events/Q1/RMDSale/DBU-MKC/2501RMD003_22_EG_RAM25_GW_Bubbler_OHL_Furniture_PricePoint_400x400_AR._CB551168067_.jpg" alt="Category 1" class="w-full h-40 object-cover rounded-md">
                    {{-- <h3 class="mt-4 text-lg font-semibold text-center">Category 1</h3> --}}
                </div>
                <!-- Category Card 1 -->
                <div class="swiper-slide bg-white shadow-lg rounded-lg p-4 transition-transform transform hover:scale-105">
                    <img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2025/Events/Q1/RMDSale/DBU-MKC/2501RMD003_11_EG_RAM25_GW_Bubbler_CL_Beauty_Perfumes_PricePoint_400x400_AR._CB551168070_.jpg" alt="Category 1" class="w-full h-40 object-cover rounded-md">
                    {{-- <h3 class="mt-4 text-lg font-semibold text-center">Category 1</h3> --}}
                </div>
                <!-- Category Card 1 -->
                <div class="swiper-slide bg-white shadow-lg rounded-lg p-4 transition-transform transform hover:scale-105">
                    <img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2025/Events/Q1/RMDSale/DBU-MKC/2501RMD003_3_EG_RAM25_GW_Bubbler_MensFashion_PricePoint_400x400_AR._CB551168066_.jpg" alt="Category 1" class="w-full h-40 object-cover rounded-md">
                    {{-- <h3 class="mt-4 text-lg font-semibold text-center">Category 1</h3> --}}
                </div>
                <!-- Category Card 1 -->
                <div class="swiper-slide bg-white shadow-lg rounded-lg p-4 transition-transform transform hover:scale-105">
                    <img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2025/Events/Q1/RMDSale/DBU-MKC/2501RMD003_15_EG_RAM25_GW_Bubbler_CE_PC_Laptop_PricePoint_400x400_AR._CB551168065_.jpg" alt="Category 1" class="w-full h-40 object-cover rounded-md">
                    {{-- <h3 class="mt-4 text-lg font-semibold text-center"></h3> --}}
                </div>
                <!-- Category Card 1 -->
                <div class="swiper-slide bg-white shadow-lg rounded-lg p-4 transition-transform transform hover:scale-105">
                    <img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2025/Events/Q1/RMDSale/DBU-MKC/2501RMD003_4_EG_RAM25_GW_Bubbler_WomensFashion_PricePoint_400x400_AR._CB551168070_.jpg" alt="Category 1" class="w-full h-40 object-cover rounded-md">
                    {{-- <h3 class="mt-4 text-lg font-semibold text-center"></h3> --}}
                </div>

                <!-- Category Card 2 -->
                <div class="swiper-slide bg-white shadow-lg rounded-lg p-4 transition-transform transform hover:scale-105">
                    <img src="https://images-eu.ssl-images-amazon.com/images/G/42/Homepage/2025/Events/Q1/RMDSale/DBU-MKC/2501RMD003_14_EG_RAM25_GW_Bubbler_CE_TVs_PricePoint_400x400_AR._CB551168065_.jpg" alt="Category 2" class="w-full h-40 object-cover rounded-md">
                    {{-- <h3 class="mt-2 text-lg font-semibold text-center"></h3> --}}
                </div>

                <!-- Category Card 3 -->
                <div class="swiper-slide bg-white shadow-lg rounded-lg p-4 transition-transform transform hover:scale-105">
                    <img src="https://via.placeholder.com/150" alt="Category 3" class="w-full h-40 object-cover rounded-md">
                    {{-- <h3 class="mt-2 text-lg font-semibold text-center"></h3> --}}
                </div>

                <!-- Category Card 4 -->
                <div class="swiper-slide bg-white shadow-lg rounded-lg p-4 transition-transform transform hover:scale-105">
                    <img src="https://via.placeholder.com/150" alt="Category 4" class="w-full h-40 object-cover rounded-md">
                    {{-- <h3 class="mt-2 text-lg font-semibold text-center"></h3> --}}
                </div>

                <!-- أضف المزيد من الفئات هنا -->
            </div>

            <!-- النقاط (Pagination) -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
</div>

<!-- إضافة سكريبت Swiper -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper('.core-swiper-container', {
        slidesPerView: 2.5,
        spaceBetween: 10,
        loop: true,
        navigation: {
            nextEl: '.category-next',
            prevEl: '.category-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            280: { slidesPerView: 1.5 },
            360: { slidesPerView: 2.2 },
            480: { slidesPerView: 3 },
            640: { slidesPerView: 4 },
            768: { slidesPerView: 5 },
            1024: { slidesPerView: 6 },
            1280: { slidesPerView: 7 },
            1440: { slidesPerView: 8 },
            1600: { slidesPerView: 9 },
            1920: { slidesPerView: 10 }
        }
    });
</script>




    {{-- ----------------------------------------------- --}}
    {{-- Start of displaying  product --}}
    <div class="swiper-container core-swiper-container m-5"
        style="overflow: hidden; margin-top: 40px; margin-bottom: 40px;">
        <div class="swiper-wrapper mb-6" style="margin-bottom: 30px">
            @foreach ($products as $product)
                <div class="swiper-slide core-swiper-slide">
                    <div class="core-product-card bg-white p-4 rounded-lg shadow-lg"
                        style="height: 320px; position: relative;">
                        <!-- Badge for Discount -->
                        <div
                            class="core-discount-badge text-white bg-red-500 px-2 py-1 rounded-full absolute top-2 right-2">
                            33% off
                        </div>

                        <a href="#">
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

                        <!-- Product Name -->
                        <div class="core-product-title text-sm font-semibold mb-2 line-clamp">
                            {{ $product->name }}
                        </div>

                        <!-- Rating -->
                        <div class="core-product-rating text-yellow-500">★★★★★ (10)</div>

                        <!-- Price -->
                        <div class="core-product-price text-lg font-bold text-green-500">
                            {{ $product->price }} <span
                                class="core-old-price line-through text-gray-500">{{ $product->old_price }}</span>
                        </div>

                        <!-- Cart Icon (bottom-left) -->
                        <div class="cart-container flex items-center">
                            <span class="shopping-cart-icon" id="add-to-cart"
                                data-product-id="{{ $product->id }}">🛒</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

















    {{-- ----------------------------------------------- --}}
    <!-- تضمين مكتبة Swiper -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.core-swiper-container', {
            slidesPerView: 2.5,
            spaceBetween: 10,
            loop: true,
            // navigation: {
            //     nextEl: '.swiper-button-next',
            //     prevEl: '.swiper-button-prev',
            // },
            // pagination: {
            //     el: '.swiper-pagination',
            //     clickable: true,
            // },
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
</x-app-layout>
