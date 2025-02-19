<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('اهلا ، ') . Auth::user()->name }}
        </h2>
    </x-slot> --}}
    <section class="bg-white py-8 antialiased md:py-16">
        {{--  --}}
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <h2 class="text-xl font-semibold text-gray-900 sm:text-2xl">عربة التسوق</h2>

            <!-- هنا الحاوية الأساسية التي تحتوي على القسمين -->
            <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-6">

                <!-- قائمة المنتجات -->
                <div class="mx-auto w-full lg:w-1/2 flex-none lg:max-w-2xl xl:max-w-4xl">
                    <div class="space-y-6">
                        @foreach ($cartItems as $item)
                            <div class="rounded-lg border border-gray-300 bg-white p-4 shadow-sm md:p-6">
                                <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                                    <a href="{{ route('product.details', $item->product->name) }}" class="shrink-0 md:order-1">
                                        <img class="h-20 w-20"
                                            src="{{ asset('storage/products/' . $item->product->images->first()->image) }}"
                                            alt="{{ $item->product->name }}" />
                                    </a>

                                    <div class="flex items-center justify-between md:order-3 md:justify-end">
                                        <div class="flex items-center">
                                            <button type="button"
                                                class="decrement-button text-black bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded-md"
                                                data-input-counter-decrement="counter-input-{{ $item->product_id }}">
                                                -
                                            </button>

                                            <input type="text" id="counter-input-{{ $item->product_id }}"
                                                class="w-10 shrink-0 border border-gray-400 bg-white text-center text-sm font-medium text-black"
                                                value="{{ $item->quantity }}" required />

                                            <button type="button"
                                                class="increment-button text-black bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded-md"
                                                data-input-counter-increment="counter-input-{{ $item->product_id }}">
                                                +
                                            </button>
                                        </div>
                                        <div class="text-end md:order-4 md:w-32">
                                            <p class="text-base font-bold text-green-600 price"
                                                data-price="{{ $item->product->price }}">
                                                {{ $item->product->price }} جم
                                            </p>
                                            <p class="text-sm font-medium text-gray-600 total-product-price"
                                                data-id="{{ $item->product_id }}">
                                                إجمالي: {{ $item->product->price * $item->quantity }} جم
                                            </p>
                                        </div>
                                    </div>

                                    <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                                        <a href="#" class="text-base font-medium text-blue-600 hover:underline">
                                            {{ $item->product->name }}
                                        </a>

                                        <div class="flex items-center gap-4">
                                            <button type="button"
                                                class="text-sm font-medium text-gray-600 hover:text-gray-800 hover:underline">
                                                Add to Favorites
                                            </button>

                                            <div class="cart-item" data-product-id="{{ $item->product_id }}">
                                                <button type="button"
                                                    class="delete-btn text-red-500 hover:text-red-600"
                                                    data-product-id="{{ $item->id }}">
                                                    حذف
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- ملخص الطلب -->
                <div class="mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-1/2">
                    <div class="space-y-4 rounded-lg border border-gray-300 bg-white p-4 shadow-sm sm:p-6">
                        <p class="text-base font-bold text-black">
                            Order summary</p>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-base font-normal text-gray-600">Original price
                                    </dt>
                                    <dd class="text-base font-medium text-black total-price">
                                        {{ $totalprice }} جم
                                    </dd>
                                </dl>

                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-base font-normal text-gray-600">Savings</dt>
                                    <dd class="text-base font-medium text-green-600">-$299.00</dd>
                                </dl>

                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-base font-normal text-gray-600">Store Pickup
                                    </dt>
                                    <dd class="text-base font-medium text-black">$99</dd>
                                </dl>

                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-base font-normal text-gray-600">Tax</dt>
                                    <dd class="text-base font-medium text-black">$799</dd>
                                </dl>
                            </div>

                            <dl class="flex items-center justify-between gap-4 border-t border-gray-400 pt-2">
                                <dt class="text-base font-bold text-black">Total</dt>
                                <dd class="text-base font-bold text-black total-price">
                                    {{ $totalprice }} جم
                                </dd>
                            </dl>
                        </div>

                        <a href="{{ route('checkout') }}"
                            class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
                            Proceed to Checkout
                        </a>

                        <div class="flex items-center justify-center gap-2">
                            <span class="text-sm font-medium text-gray-600">Have a coupon?</span>
                            <a href="#"
                                class="flex items-center gap-2 text-sm font-medium text-blue-500 underline hover:no-underline">
                                <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M10 6h14M10 12h14M10 18h14" />
                                </svg>
                                Add a coupon code
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>
</x-app-layout>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- <script>
    $(document).ready(function() {
        $(".increment-button, .decrement-button").click(function() {
            let input = $("#" + ($(this).data("input-counter-increment") || $(this).data(
                "input-counter-decrement")));
            let productId = input.attr("id").replace("counter-input-", "");

            // ✅ جلب السعر الصحيح من العنصر المرتبط بالمنتج
            let totalProductPriceElement = $(`.total-product-price[data-id="${productId}"]`);
            let totalCartElement = $(".total-price");

            let currentValue = parseInt(input.val()) || 1;
            let newValue = $(this).hasClass("increment-button") ? currentValue + 1 : Math.max(
                currentValue - 1, 1);
            input.val(newValue);

            // ✅ إرسال الطلب لتحديث قاعدة البيانات
            $.ajax({
                url: "/update-cart",
                type: "POST",
                contentType: "application/json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                data: JSON.stringify({
                    product_id: productId,
                    quantity: newValue
                }),
                success: function(data) {
                    if (data.success) {
                        // ✅ تحديث إجمالي المنتج في الواجهة فورًا
                        totalProductPriceElement.text(
                            `إجمالي: ${data.new_product_total.toFixed(2)} جم`);

                        // ✅ تحديث إجمالي العربة بالكامل
                        totalCartElement.text(`${data.total_cart_price.toFixed(2)} جم`);
                    } else {
                        alert("❌ حدث خطأ أثناء تحديث السلة.");
                    }
                },
                error: function() {
                    alert("❌ فشل التحديث!");
                }
            });
        });
    });
</script> --}}
<script>
    $(document).ready(function() {
        $(".increment-button, .decrement-button").click(function() {
            let input = $("#" + ($(this).data("input-counter-increment") || $(this).data(
                "input-counter-decrement")));
            let productId = input.attr("id").replace("counter-input-", "");

            // ✅ تحديد العناصر التي سيتم تحديثها
            let totalProductPriceElement = $(`.total-product-price[data-id="${productId}"]`);
            let totalCartElement = $(".total-price");

            let currentValue = parseInt(input.val()) || 1;
            let newValue = $(this).hasClass("increment-button") ? currentValue + 1 : Math.max(
                currentValue - 1, 1);
            input.val(newValue);

            // ✅ إرسال الطلب إلى السيرفر
            $.ajax({
                url: "/update-cart",
                type: "POST",
                contentType: "application/json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                data: JSON.stringify({
                    product_id: productId,
                    quantity: newValue
                }),
                success: function(data) {
                    if (data.success) {
                        console.log("✅ تحديث ناجح", data);

                        // ✅ تحديث إجمالي المنتج فورًا
                        totalProductPriceElement.html(
                            `إجمالي: <b>${parseFloat(data.new_product_total).toFixed(2)}</b> جم`
                        );

                        // ✅ تحديث إجمالي العربة فورًا
                        totalCartElement.html(
                            `<b>${parseFloat(data.total_cart_price).toFixed(2)}</b> جم`);
                    } else {
                        alert("❌ حدث خطأ أثناء تحديث السلة.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("❌ خطأ في التحديث", error);
                    alert("❌ فشل التحديث!");
                }
            });
        });
    });
</script>
