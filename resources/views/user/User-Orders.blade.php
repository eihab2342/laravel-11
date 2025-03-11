<x-app-layout>

    <section class="bg-white py-8 antialiased md:py-16">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <h2 class="text-xl font-semibold text-gray-900 sm:text-2xl">طلباتي</h2>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="py-3 px-4 text-right">رقم الطلب</th>
                            <th class="py-3 px-4 text-right">التاريخ</th>
                            <th class="py-3 px-4 text-right">المبلغ</th>
                            <th class="py-3 px-4 text-right">الحالة</th>
                            <th class="py-3 px-4 text-right">التفاصيل</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- طلب رقم 1 -->
                        @foreach ($orders as $order)
                            <tr class="border-b">
                                <td class="py-3 px-4 text-right">#{{ $order->id }}</td>
                                <td class="py-3 px-4 text-right">{{ $order->created_at }}</td>
                                <td class="py-3 px-4 text-right">
                                    {{ $order->total_amount }} ج.م {{-- ✅ حساب إجمالي سعر المنتجات --}}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <span
                                        class=" rounded text-sm px-2 py-1  text-white
                                        {{-- ✅ عرض حالة الطلب --}}
                                        {{-- "bg-green-500 text-white px-2 py-1 rounded text-sm" --}}
                                        @switch($order->order_status)
                                            @case('pending') bg-yellow-500 @break
                                            @case('processing') bg-blue-500 @break
                                            @case('shipped') bg-purple-500 @break
                                            @case('delivered') bg-green-500 @break
                                            @default bg-gray-500 text-white
                                        @endswitch
                                        ">
                                        @switch($order->order_status)
                                            @case('pending')
                                                معلق
                                            @break

                                            @case('processing')
                                                قيد التحضير
                                            @break

                                            @case('shipped')
                                                تم الشحن
                                            @break

                                            @case('delivered')
                                                مكتمل
                                            @break

                                            @default
                                                غير معروف
                                        @endswitch
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <button onclick="toggleDetails({{ $order->id }})"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded">
                                        عرض
                                    </button>
                                </td>
                            </tr>

                            {{-- ✅ تفاصيل الطلب --}}
                            <tr id="details-{{ $order->id }}" class="hidden bg-gray-100">
                                <td colspan="5" class="py-3 px-4 text-right">
                                    <p><strong>العنوان:</strong> {{ $order->shipping_address }}</p>
                                    <p><strong>طريقة الدفع:</strong> {{ $order->payment_method ?? 'لم يتم تحديدها' }}
                                    </p>
                                    <p><strong>المنتجات:</strong></p>
                                    <ul class="list-disc pr-6">
                                        @foreach ($order->items as $item)
                                            <li>{{ $item->product_name }} {{ $item->name }} - {{ $item->quantity }} ×
                                                {{ $item->price }} ج.م</li>
                                        @endforeach
                                    </ul>
                                    <a href=""
                                        class="bg-blue-500 hover:bg-dark-600 text-white m-1 my-3 px-4 py-1 rounded float-end">
                                        ارجاع</a>
                                </td>
                            </tr>
                        @endforeach
                        {{-- <tr class="border-b">
                            <td class="py-3 px-4 text-right">#1023</td>
                            <td class="py-3 px-4 text-right">23 فبراير 2025</td>
                            <td class="py-3 px-4 text-right">350 ج.م</td>
                            <td class="py-3 px-4 text-right">
                                <span class="bg-green-500 text-white px-2 py-1 rounded text-sm">مكتمل</span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <button onclick="toggleDetails(1)"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded">عرض</button>
                            </td>
                        </tr>
                        <tr id="details-1" class="hidden bg-gray-100">
                            <td colspan="5" class="py-3 px-4 text-right">
                                <p><strong>العنوان:</strong> شارع التحرير، القاهرة</p>
                                <p><strong>طريقة الدفع:</strong> فيزا</p>
                                <p><strong>المنتجات:</strong> منتج 1، منتج 2، منتج 3</p>
                            </td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            function toggleDetails(id) {
                let detailsRow = document.getElementById('details-' + id);
                detailsRow.classList.toggle('hidden');
            }
        </script>
        </body>
</x-app-layout>
