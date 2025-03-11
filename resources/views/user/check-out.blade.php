<x-app-layout>
    <div class="bg-white m-5">
        <div class="flex max-sm:flex-col gap-12 max-lg:gap-4 h-full">
            <div class="bg-gray-100 sm:h-screen sm:sticky sm:top-0 lg:min-w-[370px] sm:min-w-[300px]">
                <div class="relative h-full">
                    <div class="px-4 py-8 sm:overflow-auto sm:h-[calc(100vh-60px)]">
                        <div class="space-y-4">
                            @foreach ($cartItems as $cartItem)
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-32 h-28 max-lg:w-24 max-lg:h-24 flex p-3 shrink-0 bg-gray-200 rounded-md">
                                        @if (isset($cartItem->images))
                                            <img src="{{ asset('storage/products/' . $cartItem->product->images->first()->image) }}"
                                                class="w-full object-contain" />
                                        @elseif(isset($cartItem->packages->images))
                                            @foreach ($cartItem->package->images as $image)
                                                <img src="{{ asset('storage/packages/' . $image['image_path']) }}"
                                                    alt="{{ $cartItem->package->name }}"
                                                    class="h-20 w-20 md:h-28 md:w-28 object-cover rounded-lg shadow-md">
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="w-full">
                                        <h3 class="text-sm lg:text-base text-gray-800">
                                            {{ $cartItem->product->name ?? $cartItem->package->name }}
                                        </h3>
                                        <ul class="text-xs text-gray-800 space-y-1 mt-3">
                                            @if (!empty($cartItem->product->size))
                                                <li class="flex flex-wrap gap-4">Size: <span
                                                        class="ml-auto">{{ $cartItem->product->size }}</span></li>
                                            @endif
                                            <li class="flex flex-wrap gap-4">Quantity: <span
                                                    class="ml-auto">{{ $cartItem->quantity }}</span></li>
                                            <li class="flex flex-wrap gap-4">Total Price: <span
                                                    class="ml-auto text-green-800" style="font-size: 1rem">
                                                    {{ $cartItem->product->price ?? $cartItem->package->price * $cartItem->quantity }}
                                                    جم
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="md:absolute md:left-0 md:bottom-0 bg-gray-200 w-full p-4">
                        <h4 class="flex flex-wrap gap-4 text-sm lg:text-base text-gray-800">Total:
                            {{-- @php
                                $appliedCoupon = session('applied_coupon', []);
                                $discount = 0;

                                if (!empty($appliedCoupon) && isset($appliedCoupon['expires_at'])) {
                                    if (now()->greaterThan($appliedCoupon['expires_at'])) {
                                        session()->forget('applied_coupon'); // حذف الكوبون إذا انتهت مدته
                                    } else {
                                        $discount = $appliedCoupon['discount'] ?? 0;
                                    }
                                }

                                $finalTotal = $totalPrice - $discount;
                                session(['final_total' => $finalTotal]); // تحديث السيشن بالقيمة الجديدة
                            @endphp --}}

                            <span class="ml-auto text-xl">{{ session('applied_coupon')['newTotal'] ?? $totalPrice }} جنيهاَ
                                مصرياً</span>
                        </h4>
                    </div>
                </div>
            </div>

            <div class="max-w-4xl w-full h-max rounded-md px-4 py-8 sticky top-0">
                <h2 class="text-2xl font-bold text-gray-800">Complete your order</h2>
                <form class="mt-8" action="{{ route('order.place') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                    <input type="hidden" name="total_amount" value="{{ $totalPrice }}">

                    <div>
                        <h3 class="text-sm lg:text-base text-gray-800 mb-4">Personal Details</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <input type="text" placeholder="First Name"
                                    class="px-4 py-3 bg-gray-100 text-gray-800 w-full text-sm rounded-md focus:outline-blue-600"
                                    value="{{ old('first_name', $first_name) }}" required name="first_name" />
                            </div>

                            <div>
                                <input type="text" placeholder="Last Name"
                                    class="px-4 py-3 bg-gray-100 text-gray-800 w-full text-sm rounded-md focus:outline-blue-600"
                                    value="{{ old('last_name', $last_name) }}" required name="last_name" />
                            </div>

                            <div>
                                <input type="email" placeholder="Email"
                                    class="px-4 py-3 bg-gray-100 text-gray-800 w-full text-sm rounded-md focus:outline-blue-600"
                                    value="{{ old('email', $user->email) }}" required name="email" />
                            </div>

                            <div>
                                <input type="number" placeholder="Phone No."
                                    class="px-4 py-3 bg-gray-100 text-gray-800 w-full text-sm rounded-md focus:outline-blue-600"
                                    value="{{ old('phoneNumber', $user->phoneNumber) }}" name="phone_number" />
                            </div>
                        </div>
                    </div>

                    {{-- تفاصيل الدفع --}}
                    <div class="mt-8">
                        <h3 class="text-sm lg:text-base text-gray-800 mb-4">Payment Method</h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <input type="radio" id="cash_on_delivery" name="payment_method" value="COD"
                                    class="w-5 h-5 text-blue-600" checked>
                                <label for="cash_on_delivery" class="text-sm lg:text-base text-gray-800">Cash on
                                    Delivery</label>
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="radio" id="visa" name="payment_method" value="visa"
                                    class="w-5 h-5 text-blue-600">
                                <label for="visa" class="text-sm lg:text-base text-gray-800">Credit/Debit
                                    Card</label>
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="radio" id="m_wallet" name="payment_method" value="m_wallet"
                                    class="w-5 h-5 text-blue-600">
                                <label for="m_wallet" class="text-sm lg:text-base text-gray-800">Mobile-Wallet</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-sm lg:text-base text-gray-800 mb-4">Shipping Address</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <input type="text" placeholder="Address Line"
                                    class="px-4 py-3 bg-gray-100 focus:bg-transparent text-gray-800 w-full text-sm rounded-md focus:outline-blue-600"
                                    required name="shipping_address" value="{{ old('shipping_address') }}" required />
                            </div>
                            <div>
                                <input type="text" placeholder="Village	"
                                    class="px-4 py-3 bg-gray-100 focus:bg-transparent text-gray-800 w-full text-sm rounded-md focus:outline-blue-600"
                                    required name="village" value="{{ old('village') }}" />
                            </div>
                            <div>
                                <input type="text" placeholder="City"
                                    class="px-4 py-3 bg-gray-100 focus:bg-transparent text-gray-800 w-full text-sm rounded-md focus:outline-blue-600"
                                    required name="city" value="{{ old('city') }}" />
                            </div>
                            <div>
                                <input type="text" placeholder="State"
                                    class="px-4 py-3 bg-gray-100 focus:bg-transparent text-gray-800 w-full text-sm rounded-md focus:outline-blue-600"
                                    required name="governorate" value="{{ old('governorate') }}" />
                            </div>
                        </div>
                        <div class="flex gap-4 max-md:flex-col mt-8">
                            <button type="button"
                                class="rounded-md px-4 py-2.5 w-full text-sm tracking-wide bg-transparent hover:bg-gray-100 border border-gray-300 text-gray-800 max-md:order-1">Cancel</button>
                            <button type="submit"
                                class="rounded-md px-4 py-2.5 w-full text-sm tracking-wide bg-blue-600 hover:bg-blue-700 text-white">Complete
                                Purchase</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
