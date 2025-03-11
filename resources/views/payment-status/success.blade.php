<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معاملة ناجحة</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 dark:bg-gray-900">

    <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
        <div class="mx-auto max-w-2xl px-4 2xl:px-0 text-center">
            <div class="flex justify-center items-center mb-4">
                <svg class="w-16 h-16 text-green-500 dark:text-green-400" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">Thanks for your order!</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-6 md:mb-8">
                Your order <a href="{{ route('MyOrders') }}"
                    class="font-medium text-primary-700 dark:text-primary-400 hover:underline">
                    #{{ $request['id'] }}</a> has been placed successfully! 🎉
                <br>
                It will be processed within 24 hours during working days. We will notify you by email once your order
                has been shipped.
            </p>
            <div
                class="space-y-4 sm:space-y-2 rounded-lg border border-gray-100 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800 mb-6 md:mb-8">
                <dl class="sm:flex items-center justify-between gap-4">
                    <dt class="font-normal text-gray-500 dark:text-gray-400">🗓 Date</dt>
                    <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ $request['created_at'] }}</dd>
                </dl>
                <dl class="sm:flex items-center justify-between gap-4">
                    <dt class="font-normal text-gray-500 dark:text-gray-400">💳 Payment Method</dt>
                    <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ $request['source_data_type'] }}
                    </dd>
                </dl>
                <dl class="sm:flex items-center justify-between gap-4">
                    <dt class="font-normal text-gray-500 dark:text-gray-400">🙍 Name</dt>
                    <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ $customer_name }}</dd>
                </dl>
                <dl class="sm:flex items-center justify-between gap-4">
                    <dt class="font-normal text-gray-500 dark:text-gray-400">📍 Address</dt>
                    <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ $customer_address }}, Egypt
                    </dd>
                </dl>
                <dl class="sm:flex items-center justify-between gap-4">
                    <dt class="font-normal text-gray-500 dark:text-gray-400">🏙 Governorate</dt>
                    <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ $customer_governorate }}</dd>
                </dl>
                <dl class="sm:flex items-center justify-between gap-4">
                    <dt class="font-normal text-gray-500 dark:text-gray-400">📞 Phone</dt>
                    <dd class="font-medium text-gray-900 dark:text-white sm:text-end">+(2) {{ $customer_phone_number }}
                    </dd>
                </dl>
            </div>
            <div class="flex items-center justify-center space-x-4">
                <a href="{{ route('MyOrders') }}"
                    class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-500 dark:hover:bg-green-600 focus:outline-none dark:focus:ring-green-700">
                    📦 Track your order
                </a>
                <a href="{{ route('/') }}"
                    class="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    🛒 Return to shopping
                </a>
            </div>
        </div>
    </section>

    {{-- 
    <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
        <div class="mx-auto max-w-2xl px-4 2xl:px-0">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl mb-2">Thanks for your order!</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-6 md:mb-8">Your order <a href="{{ route('MyOrders') }}"
                    class="font-medium text-gray-900 dark:text-white hover:underline">#{{ $request['id'] }}</a> will be
                processed
                within 24 hours during working days. We will notify you by email once your order has been shipped.</p>
            <div
                class="space-y-4 sm:space-y-2 rounded-lg border border-gray-100 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800 mb-6 md:mb-8">
                <dl class="sm:flex items-center justify-between gap-4">
                    <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Date</dt>
                    <dd class="font-medium text-gray-900 dark:text-white sm:text-end">
                        {{ $request['created_at'] }}
                    </dd>
                </dl>
                <dl class="sm:flex items-center justify-between gap-4">
                    <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Payment Method</dt>
                    <dd class="font-medium text-gray-900 dark:text-white sm:text-end">
                        {{ $request['source_data_type'] }}
                    </dd>
                </dl>
                <dl class="sm:flex items-center justify-between gap-4">
                    <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Name</dt>
                    <dd class="font-medium text-gray-900 dark:text-white sm:text-end">
                        {{ $customer_name }}
                    </dd>
                </dl>
                <dl class="sm:flex items-center justify-between gap-4">
                    <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Address</dt>
                    <dd class="font-medium text-gray-900 dark:text-white sm:text-end">
                        {{ $customer_address }},
                        Egypt
                    </dd>
                </dl>
                <dl class="sm:flex items-center justify-between gap-4">
                    <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Phone</dt>
                    <dd class="font-medium text-gray-900 dark:text-white sm:text-end">
                        +(2) {{ $customer_phone_number }}
                    </dd>
                </dl>
            </div>
            <div class="flex items-center space-x-4">
                <a href="#"
                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">Track
                    your order</a>
                <a href="{{ route('/') }}"
                    class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Return
                    to shopping</a>
            </div>
        </div>
    </section>
 --}}
</body>

</html>
