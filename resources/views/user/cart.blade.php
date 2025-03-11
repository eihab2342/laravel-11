<x-app-layout>
    <section class="bg-white py-8 antialiased md:py-16">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <h2 class="text-xl font-semibold text-gray-900 sm:text-2xl">عربة التسوق</h2>

            <!-- القسم الرئيسي -->
            <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-6">
                <!-- قائمة المنتجات -->
                <div class="mx-auto w-full lg:w-1/2 flex-none lg:max-w-2xl xl:max-w-4xl">
                    <div class="space-y-6">



                        @if ($cartItems->count() > 0)
                            @foreach ($cartItems as $item)
                                <!-- عرض كل منتج أو باكدج -->
                                <div class="rounded-lg border border-gray-300 bg-white p-4 shadow-sm md:p-6">
                                    <div
                                        class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">

                                        <!-- صورة المنتج أو الباكدج -->

                                        <a>
                                            <div class="shrink-0 md:order-1">
                                                @if (!empty($item->product) && optional($item->product->images)->count() > 0)
                                                    <img class="h-20 w-20 md:h-28 md:w-28 object-cover rounded-lg shadow-md"
                                                        src="{{ asset('storage/products/' . $item->product->images->first()->image) }}"
                                                        alt="{{ $item->product->name }}" />
                                                @elseif (!empty($item->package) && !empty($item->package->images))
                                                    <div class="shrink-0 md:order-1">
                                                        @foreach ($item->package->images as $image)
                                                            <img src="{{ asset('storage/packages/' . $image['image_path']) }}"
                                                                alt="{{ $item->package->name }}"
                                                                class="h-20 w-20 md:h-28 md:w-28 object-cover rounded-lg shadow-md">
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-gray-500">لا توجد صورة</span>
                                                @endif
                                            </div>
                                        </a>
                                        <!-- الكمية والسعر -->
                                        <div class="flex items-center justify-between md:order-3 md:justify-end">
                                            <div class="flex items-center">
                                                <button type="button"
                                                    class="decrement-button text-black bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded-md"
                                                    data-input-counter-decrement="counter-input-{{ $item->product_id ?? $item->package_id }}">
                                                    -
                                                </button>
                                                <input type="text"
                                                    id="counter-input-{{ $item->product_id ?? $item->package_id }}"
                                                    class="w-10 shrink-0 border border-gray-400 bg-white text-center text-sm font-medium text-black"
                                                    value="{{ $item->quantity }}" required />
                                                <button type="button"
                                                    class="increment-button text-black bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded-md"
                                                    data-input-counter-increment="counter-input-{{ $item->product_id ?? $item->package_id }}">
                                                    +
                                                </button>
                                            </div>
                                            <div class="text-end md:order-4 md:w-32">
                                                @php
                                                    $price = isset($item->product)
                                                        ? $item->product->price
                                                        : (isset($item->package)
                                                            ? $item->package->price
                                                            : 0);
                                                @endphp

                                                <p class="text-base font-bold text-green-600 price"
                                                    data-price="{{ $price }}">
                                                    {{ $price }} جم
                                                </p>

                                                <p class="text-sm font-medium text-gray-600 total-product-price"
                                                    data-id="{{ $item->product_id ?? $item->package_id }}">
                                                    إجمالي: {{ $price * $item->quantity }} جم
                                                </p>
                                            </div>
                                        </div>
                                        <!-- اسم المنتج أو الباكدج والأزرار -->
                                        <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                                            @php
                                                $name = isset($item->product)
                                                    ? $item->product->name
                                                    : (isset($item->package)
                                                        ? $item->package->name
                                                        : 'غير معروف');
                                            @endphp
                                            <a href="#"
                                                class="text-base font-medium text-blue-600 hover:underline">
                                                {{ $name }}
                                            </a>

                                            <div class="flex items-center gap-4">
                                                <button type="button"
                                                    class="text-sm font-medium text-gray-600 hover:text-gray-800 hover:underline">
                                                    إضافة إلى المفضلة
                                                </button>
                                                <button type="button"
                                                    class="delete-btn text-red-500 hover:text-red-600"
                                                    data-product-id="{{ $item->product_id ?? $item->package_id }}">
                                                    حذف
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- رسالة السلة الفارغة -->
                            <div class="flex flex-col items-center justify-center text-center p-8">
                                <span class="text-3xl">🛒</span>
                                <h2 class="text-2xl font-semibold text-gray-700">سلة المشتريات فارغة</h2>
                                <p class="text-gray-500 mt-2">ابدأ التسوق الآن وأضف منتجاتك المفضلة إلى السلة.</p>
                                <a href="{{ route('/') }}"
                                    class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    تصفح المنتجات
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ملخص الطلب -->
                @if ($cartItems->count() > 0)
                    <div class="mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-1/2">
                        <div class="space-y-4 rounded-lg border border-gray-300 bg-white p-4 shadow-sm sm:p-6">
                            <p class="text-base font-bold text-black">ملخص الطلب</p>

                            <div class="space-y-4">
                                <!-- الإجمالي الفرعي -->
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-base font-normal text-gray-600">الإجمالي الفرعي</dt>
                                    <dd class="text-base font-medium text-black subtotal-price">{{ $totalprice }} جم
                                    </dd>
                                </dl>

                                <!-- الخصم -->
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-base font-normal text-gray-600">الخصم</dt>
                                    <dd class="text-base font-medium text-green-600 discount-price">
                                        {{ session('applied_coupon')['discount'] ?? 0 }}</dd>
                                </dl>

                                <!-- الإجمالي النهائي -->
                                <dl class="flex items-center justify-between gap-4 border-t border-gray-400 pt-2">
                                    <dt class="text-base font-bold text-black">الإجمالي النهائي</dt>
                                    <dd class="text-base font-bold text-black total-price">{{ $totalprice }} جم</dd>
                                </dl>
                            </div>

                            <!-- حقل الكوبون -->
                            <div class="mt-4">
                                <label for="coupon" class="block text-sm font-medium text-gray-600">كود الخصم</label>
                                <div class="flex mt-1">
                                    <input type="text" id="coupon" name="coupon" placeholder="أدخل كود الخصم"
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <button type="button" id="apply-coupon"
                                        class="px-4 py-2 bg-blue-600 text-white font-medium rounded-r-lg hover:bg-blue-700">
                                        تطبيق
                                    </button>
                                </div>
                                <p id="coupon-message" class="mt-2 text-sm text-red-600"></p>
                            </div>

                            <!-- زر الدفع -->
                            <a href="{{ route('checkout') }}"
                                class="mt-4 flex w-full items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
                                اتمام الشراء
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>
</x-app-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".increment-button, .decrement-button").click(function() {
            let input = $("#" + ($(this).data("input-counter-increment") || $(this).data(
                "input-counter-decrement")));
            let productId = input.attr("id").replace("counter-input-", "");

            let newValue = $(this).hasClass("increment-button") ? parseInt(input.val()) + 1 : Math.max(
                parseInt(input.val()) - 1, 1);
            input.val(newValue);

            $.ajax({
                url: "/update-cart",
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                data: {
                    product_id: productId,
                    quantity: newValue
                },
                success: function(data) {
                    if (data.success) {
                        // تحديث إجمالي المنتج
                        $(`.total-product-price[data-id="${productId}"]`).html(
                            `إجمالي: ${data.new_item_total} جم`);

                        // تحديث الإجمالي الفرعي والإجمالي النهائي
                        $(".subtotal-price").html(`${data.total_cart_price} جم`);
                        $(".total-price").html(`${data.total_cart_price} جم`);
                    }
                },
                error: function(error) {
                    console.log("حدث خطأ أثناء تحديث العربة", error);
                }
            });
        });
    });

    // 
    $("#apply-coupon").click(function() {
        let coupon = $("#coupon").val().trim();
        let subtotal = parseFloat($(".subtotal-price").text().replace(" جم", ""));

        if (coupon === "") {
            $("#coupon-message").text("يرجى إدخال كود الخصم.");
            return;
        }

        $.ajax({
            url: "/apply-coupon",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            data: {
                coupon: coupon,
                subtotal: subtotal
            },
            success: function(response) {
                if (response.success) {
                    $(".discount-price").html(`${response.discount} جم`);
                    $(".total-price").html(`${response.newTotal} جم`);
                    $("#coupon-message").text("تم تطبيق الخصم بنجاح!").removeClass(
                        "text-red-600").addClass("text-green-600");
                } else {
                    $("#coupon-message").text(response.message).removeClass(
                        "text-green-600").addClass("text-red-600");
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                $("#coupon-message").text("حدث خطأ أثناء تطبيق الكوبون.").removeClass(
                    "text-green-600").addClass("text-red-600");
            }
        });
    });
</script>









{{-- 
        // تحديث الكمية
        /*
        $(".increment-button, .decrement-button").click(function() {
            let input = $("#" + ($(this).data("input-counter-increment") || $(this).data(
                "input-counter-decrement")));
            let productId = input.attr("id").replace("counter-input-", "");
            let newValue = $(this).hasClass("increment-button") ? parseInt(input.val()) + 1 : Math.max(
                parseInt(input.val()) - 1, 1);
            input.val(newValue);

            $.ajax({
                url: "/update-cart",
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                data: {
                    product_id: productId,
                    quantity: newValue
                },
                success: function(data) {
                    if (data.success) {
                        $(`.total-product-price[data-id="${productId}"]`).html(
                            `إجمالي: ${data.new_product_total} جم`);
                        $(".subtotal-price").html(`${data.total_cart_price} جم`);
                        $(".total-price").html(`${data.total_cart_price} جم`);
                    }
                },
                error: function() {
                    alert("حدث خطأ أثناء تحديث السلة.");
                }
            });
        });
        */ --}}
