<x-app-layout>

    {{-- swiper --}}
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.0/flowbite.min.js"></script>
    <div id="animation-carousel" class="relative w-full mb-3" data-carousel="static">

        <!-- Carousel wrapper -->
        <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
            @foreach ($images as $key => $image)
                <div class="{{ $key === 0 ? '' : 'hidden' }} duration-200 ease-linear" data-carousel-item>
                    <img src="{{ asset('storage/' . $image->image) }}"
                        class="absolute block w-full h-full object-cover -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 rounded-md shadow-md"
                        alt="صورة">
                </div>
            @endforeach
        </div>

        <!-- أزرار التنقل -->
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
        <h2 class="text-3xl font-bold mb-6 text-orange-500 text-center">الأقسام</h2>

        <!-- Swiper Container -->
        <div class="relative">
            <div class="swiper-button-next category-next"></div>
            <div class="swiper-button-prev category-prev"></div>

            <div class="swiper core-swiper-container">
                <div class="swiper-wrapper">
                    @foreach ($categories as $category)
                        <div
                            class="swiper-slide bg-white shadow-lg rounded-lg p-3 transition-transform transform hover:scale-105 flex flex-col items-center">
                            <a href="{{ route('category.details', $category->id) }}" class="w-full">
                                <img src="{{ asset('storage/categories/' . $category->image) }}"
                                    alt="{{ $category->name }}" class="w-full h-40 object-contain rounded-md">
                                <h5 class="mt-4 text-lg font-semibold text-center">{{ $category->name }}</h5>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
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
            breakpoints: {
                280: {
                    slidesPerView: 1.5
                },
                360: {
                    slidesPerView: 2.2
                },
                480: {
                    slidesPerView: 3
                },
                640: {
                    slidesPerView: 4
                },
                768: {
                    slidesPerView: 5
                },
                1024: {
                    slidesPerView: 6
                },
                1280: {
                    slidesPerView: 7
                },
                1440: {
                    slidesPerView: 8
                },
                1600: {
                    slidesPerView: 9
                },
                1920: {
                    slidesPerView: 10
                }
            }
        });
    </script>





    <!-- سلايدر المنتجات الخاصة بالباكدجات -->
    <div class="swiper-container core-swiper-container m-5"
        style="overflow: hidden; margin-top: 20px; margin-bottom: 40px;">
        <h2 class="text-3xl font-bold mb-6 text-orange-500 text-center">بكجات يزن العناية</h2>
        <div class="swiper-wrapper mb-6">
            @foreach ($packages as $package)
                <div class="swiper-slide core-swiper-slide">
                    <div class="core-product-card bg-white p-4 rounded-lg shadow-lg"
                        style="height: 320px; position: relative;">

                        <!-- عرض نسبة الخصم -->
                        @if ($package->old_price > $package->price)
                            <div
                                class="core-discount-badge text-white bg-red-500 px-2 py-1 rounded-full absolute top-2 right-2">
                                {{ round((($package->old_price - $package->price) / $package->old_price) * 100) }}% off
                            </div>
                        @endif

                        <!-- صورة المنتج -->
                        <a href="{{ route('product.details', $package->name) }}">
                            <div class="core-product-image mb-4">
                                @if (is_iterable($package->images) && count($package->images) > 0)
                                    @foreach ($package->images as $image)
                                        <img src="{{ asset('storage/packages/' . $image['image_path']) }}"
                                            alt="{{ $package->name }}" class="w-full h-auto rounded-lg"
                                            style="height: 130px;">
                                    @endforeach
                                @else
                                    <span class="text-gray-500">لا توجد صورة</span>
                                @endif
                            </div>
                        </a>

                        <!-- اسم المنتج -->
                        <div class="core-product-title text-sm font-semibold mb-2 text-center">
                            {{ $package->name }}
                        </div>

                        <!-- التقييم -->
                        <div class="core-product-rating text-yellow-500 text-center">★★★★★ (10)</div>

                        <!-- السعر -->
                        <div class="core-product-price text-md font-bold text-green-500 text-center">
                            {{ $package->price }}
                            <span class="core-old-price line-through text-gray-500 text-sm">
                                {{ $package->old_price ?? 0 }}
                            </span>
                        </div>

                        <div class="cart-container flex items-center justify-center mt-2">
                            <span class="shopping-cart-icon cursor-pointer add-to-cart-btn"
                                data-product-id="{{ $package->id }}" data-type="package">🛒</span>
                        </div>


                        <!-- باكدج -->
                        {{-- <div class="cart-container flex items-center justify-center mt-2">
                            <span class="shopping-cart-icon cursor-pointer" id="add-to-cart"
                                onclick="this.disabled=true; handleCartIconClick(this).then(() => this.disabled=false);"
                                data-product-id="{{ $package->id }}" data-type="package">🛒</span>
                        </div> --}}
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    @foreach ($controls as $control)
        <div class="mx-6">
            <!-- خط علوي بتدرج لوني للحصول على شكل أكثر أناقة -->
            <div class="h-1 bg-gradient-to-r from-orange-500 to-yellow-400 mx-5 mt-4 rounded-full"></div>

            <!-- قسم عنوان الفئة -->
            <div
                class="flex items-center justify-between bg-orange-500 text-white px-6 py-3 mx-5 mt-3 rounded-lg shadow-lg w-fit">
                <div class="flex items-center gap-3">
                    <i class="fas fa-bolt text-xl animate-pulse"></i> <!-- أيقونة مع نبض خفيف -->
                    <h2 class="text-xl font-extrabold uppercase tracking-wide">
                        {{ $control->category->name ?? 'Hot Deals' }}
                    </h2>
                </div>
            </div>
            <!-- خط سفلي متدرج -->
            <div class="h-1 bg-gradient-to-r from-yellow-400 to-orange-500 mx-5 mt-2 rounded-full"></div>

        </div>

        <!-- كـــاروســيــل صــور الــفــئــة (لصورة واحدة فقط) -->
        <div class="relative w-11/12 sm:w-10/12 mt-5 overflow-hidden mx-auto">
            <!-- صورة الفئة -->
            <div class="relative h-56 md:h-96 flex justify-center items-center rounded-lg overflow-hidden">
                <a href="{{ route('category.details', $control->category_id) }}">
                    <img src="{{ asset('storage/' . $control->image) }}"
                        class="block w-full h-full object-cover rounded-md shadow-md" alt="صورة">
                </a>
            </div>
        </div>
        <!-- سلايدر المنتجات الخاصة بالفئة -->
        <div class="swiper-container core-swiper-container m-5"
            style="overflow: hidden; margin-top: 20px; margin-bottom: 40px;">
            <div class="swiper-wrapper mb-6">
                @foreach ($control->category->products ?? [] as $product)
                    <div class="swiper-slide core-swiper-slide">
                        <div class="core-product-card bg-white p-4 rounded-lg shadow-lg"
                            style="height: 320px; position: relative;">

                            <!-- عرض نسبة الخصم -->
                            @if ($product->old_price > $product->price)
                                <div
                                    class="core-discount-badge text-white bg-red-500 px-2 py-1 rounded-full absolute top-2 right-2">
                                    {{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%
                                    off
                                </div>
                            @endif

                            <!-- صورة المنتج -->
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

                            <!-- اسم المنتج -->
                            <div class="core-product-title text-sm font-semibold mb-2 text-center">
                                {{ $product->name }}
                            </div>

                            <!-- التقييم -->
                            <div class="core-product-rating text-yellow-500 text-center">★★★★★ (10)</div>

                            <!-- السعر -->
                            <div class="core-product-price text-md font-bold text-green-500 text-center">
                                {{ $product->price }}<span class="core-old-price line-through text-gray-500 text-sm">
                                    {{ $product->old_price }}</span>
                            </div>


                            <div class="cart-container flex items-center justify-center mt-2">
                                <span class="shopping-cart-icon cursor-pointer add-to-cart-btn"
                                    data-product-id="{{ $product->id }}" data-type="product">🛒</span>
                            </div>
                            <!-- أيقونة السلة -->
                            {{-- <div class="cart-container flex items-center justify-center mt-2">
                                <span class="shopping-cart-icon cursor-pointer" id="add-to-cart"
                                    onclick="this.disabled=true; handleCartIconClick(this).then(() => this.disabled=false);"
                                    data-product-id="{{ $product->id }}" data-type="product">🛒</span>
                            </div> --}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach


    <!-- تضمين مكتبة Swiper -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.core-swiper-container', {
            slidesPerView: 2.5,
            spaceBetween: 10,
            loop: true,
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
