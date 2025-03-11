<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
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
            /* color: #007bff; */
            cursor: pointer;
            bottom: 1;
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

        #animation-carousel {
            overflow: hidden;
        }
    </style>

</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-md p-4">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('/') }}" class="text-3xl font-bold text-gray-800">YAZAN</a>
            <!-- Mobile Search Bar -->
            <!-- Search Bar in large screens -->
            <div class="hidden md:flex flex-1 mx-6 max-w-lg relative">
                <input type="text" id="search-desktop" placeholder="ابحث عن منتج..."
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div id="search-results-desktop"
                    class="absolute left-0 mt-1 w-full bg-white text-black rounded-lg shadow-lg hidden"></div>
            </div>
            <!-- Navigation Links -->
            <ul class="hidden md:flex space-x-8 space-x-reverse text-gray-600 font-medium">
                {{-- <li><a href="{{ route('user') }}" class="hover:text-blue-600 transition">الرئيسية</a></li> --}}
                <li><a href="#" class="hover:text-blue-600 transition">المتجر</a></li>
                <li><a href="#" class="hover:text-blue-600 transition">العروض</a></li>
                <li><a href="{{ route('MyOrders') }}" class="hover:text-green-600 transition">طلباتي</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-blue-600 transition">اتصل بنا</a></li>
            </ul>

            <!-- Icons in large screens-->
            <div class="hidden md:flex items-center space-x-6 space-x-reverse">
                <!-- أضف Alpine.js -->
                <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

                <!-- الحاوية الأساسية -->
                <!-- زر الإشعارات للشاشات الكبيرة -->
                <button class="relative flex items-center notification-btn-lg">
                    <i data-lucide="bell" class="w-6 h-6 text-gray-600"></i>
                    <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full px-1">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                </button>

                {{-- cart icon in large screen --}}
                <a href="{{ route('user.cart') }}" class="relative">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-gray-600"></i>
                    <span id="cart-count-lg"
                        class="count absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1">
                        0
                    </span>
                </a>
                {{-- if user has no account --}}
                <div class="flex items-center space-x-6 space-x-reverse">
                    @auth
                        <!-- إذا كان المستخدم مسجلاً، أظهر أيقونة الحساب -->
                        <a href="{{ route('profile.edit') }}">
                            <i data-lucide="user" class="w-6 h-6 text-gray-600"></i>
                        </a>
                    @else
                        <!-- إذا لم يكن المستخدم مسجلاً، أظهر زر "إنشاء حساب" -->
                        <a href="{{ route('register') }}"
                            class="flex items-center space-x-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i data-lucide="user-plus" class="w-5 h-5"></i>
                            <span>إنشاء حساب</span>
                        </a>
                    @endauth
                </div>
                @auth
                    <div class="max-w-screen-xl flex flex-wrap items-center justify-between ">
                        @csrf
                        <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                            <button type="button" class="flex text-sm bg-gray-800 rounded-full md:me-0 "
                                id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                                data-dropdown-placement="bottom">
                                <img class="w-7 h-7 rounded-full" id="avatarPreview"
                                    src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/MAINLOGO.jpg') }}">
                            </button>
                            <!-- Dropdown menu -->
                            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-700 dark:divide-gray-600"
                                id="user-dropdown">
                                <div class="px-4 py-3">
                                    <span
                                        class="block text-sm text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                                    <span
                                        class="block text-sm  text-gray-500 truncate dark:text-gray-400">{{ Auth::user()->email }}</span>
                                </div>
                                <ul class="py-2" aria-labelledby="user-menu-button">
                                    <li>
                                        <a href="{{ route('profile.edit') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Profile</a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Orders</a>
                                    </li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                Sign out
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
            <!-- Mobile top icons search and bar -->
            <div class="flex items-center space-x-4 space-x-reverse md:hidden">

                {{-- create account in mobile --}}
                <div class="flex items-center space-x-reverse">
                    @auth
                        <!-- إذا كان المستخدم مسجلاً، أظهر أيقونة الحساب -->
                        <!-- زر الإشعارات -->
                        <button class="relative flex items-center notification-btn">
                            <i data-lucide="bell" class="w-6 h-6 text-gray-600"></i>
                            <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full px-1">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        </button>
                    @else
                        <!-- إذا لم يكن المستخدم مسجلاً، أظهر زر "إنشاء حساب" -->
                        <a href="{{ route('register') }}"
                            class="flex items-center space-x-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i data-lucide="user-plus" class="w-5 h-5"></i>
                            <span>إنشاء حساب</span>
                        </a>
                    @endauth
                </div>

                <button id="search-toggle">
                    <i data-lucide="search" class="w-6 h-6 text-gray-600"></i>
                </button>
                <button class="text-gray-600" id="menu-toggle">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
        <!-- Mobile Search Bar -->
        <div id="mobile-search" class="hidden md:hidden p-4 bg-white relative">
            <input type="text" id="search-mobile" placeholder="ابحث عن منتج..."
                class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div id="search-results-mobile"
                class="absolute left-0 mt-1 w-full bg-white text-black rounded-lg shadow-lg hidden"></div>
        </div>
        <!-- Mobile Menu -->
        <ul class="md:hidden hidden flex-col bg-white mt-2 p-4 space-y-2 text-gray-600" id="mobile-menu">
            <li><a href="{{ route('user') }}" class="block hover:text-blue-600 transition">الرئيسية</a></li>
            <li><a href="#" class="block hover:text-blue-600 transition">المتجر</a></li>
            <li><a href="#" class="block hover:text-blue-600 transition">العروض</a></li>
            <li><a href="{{ route('contact') }}" class="block hover:text-blue-600 transition">اتصل بنا</a></li>
        </ul>
    </nav>
    <div
        class="fixed bottom-0 left-0 w-full bg-white shadow-md py-3 flex justify-around items-center md:hidden border-t z-50">
        <a href="{{ route('user') }}">
            <i data-lucide="home" class="w-6 h-6 text-gray-600"></i>
        </a>
        {{-- small screens --}}
        <a href="{{ route('user.cart') }}" class="relative">
            <i data-lucide="shopping-bag" class="w-6 h-6 text-gray-600"></i>
            <span id="cart-count"
                class="count absolute -top-2 -right-3 bg-red-500 text-white text-xs rounded-full px-1">
                0
            </span>
        </a>
        <!-- أضف Alpine.js -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <!-- زر الإشعارات -->
        <button class="relative flex items-center notification-btn">
            <i data-lucide="bell" class="w-6 h-6 text-gray-600"></i>
            <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full px-1">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        </button>

        <a href="{{ route('profile.edit') }}">
            <img class="w-7 h-7 rounded-full" id="avatarPreview"
                src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/MAINLOGO.jpg') }}">
        </a>
    </div>


    <div class="relative">
        <!-- قائمة الإشعارات -->
        <div
            class="hidden absolute left-2 top-full mt-2 w-72 bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200 z-50 dropdown-menu">
            <div class="px-4 py-3 bg-gray-100 text-gray-700 font-semibold">
                الإشعارات
            </div>
            <ul class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
                @forelse (auth()->user()->unreadNotifications as $notification)
                    @php
                        $data = is_array($notification->data)
                            ? $notification->data
                            : json_decode($notification->data, true);
                    @endphp
                    <li class="p-3 hover:bg-gray-50 flex justify-between items-center">
                        <a href="{{ route('MyOrders', $data['order_id'] ?? '#') }}" class="flex-1">
                            <p class="text-sm font-medium">{{ $data['message'] ?? 'لديك طلب جديد' }}</p>
                            <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                        </a>
                        <!-- زر مسح إشعار واحد -->
                        <form action="{{ route('notifications.markOneAsRead', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-red-500 text-xs hover:underline">مسح</button>
                        </form>
                    </li>
                @empty
                    <li class="p-3 text-center text-gray-500">لا توجد إشعارات جديدة</li>
                @endforelse
            </ul>

            <!-- زر مسح كل الإشعارات -->
            @if (auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST"
                    class="p-3 border-t border-gray-200">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 text-white p-2 rounded hover:bg-red-600">مسح
                        الكل</button>
                </form>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const buttons = document.querySelectorAll('.notification-btn');
            const menus = document.querySelectorAll('.dropdown-menu');

            buttons.forEach((btn, index) => {
                btn.addEventListener('click', (event) => {
                    event.stopPropagation();
                    // تأكد من وجود قائمة لهذا الزر
                    if (menus[index]) {
                        menus[index].classList.toggle('hidden');
                    }
                });
            });

            document.addEventListener('click', (event) => {
                menus.forEach((menu) => {
                    if (!menu.contains(event.target) &&
                        !event.target.classList.contains('notification-btn') &&
                        !event.target.classList.contains('notification-btn-lg')) {
                        menu.classList.add('hidden');
                    }
                });
            });
        });
        // 

        document.addEventListener("DOMContentLoaded", function() {
            const buttons = document.querySelectorAll('.notification-btn-lg');
            const menus = document.querySelectorAll('.dropdown-menu');

            buttons.forEach((btn, index) => {
                btn.addEventListener('click', (event) => {
                    event.stopPropagation();
                    // تأكد من وجود قائمة لهذا الزر
                    if (menus[index]) {
                        menus[index].classList.toggle('hidden');
                    }
                });
            });

            document.addEventListener('click', (event) => {
                menus.forEach((menu) => {
                    if (!menu.contains(event.target) &&
                        !event.target.classList.contains('notification-btn-lg')) {
                        menu.classList.add('hidden');
                    }
                });
            });
        });

    </script>

    <!-- إضافة padding لمنع المحتوى من التغطية -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        lucide.createIcons();
        document.getElementById("menu-toggle").addEventListener("click", function() {
            document.getElementById("mobile-menu").classList.toggle("hidden");
        });
        document.getElementById("search-toggle").addEventListener("click", function() {
            document.getElementById("mobile-search").classList.toggle("hidden");
        });
    </script>

    <style>
        #search-results-mobile,
        #search-results-desktop {
            position: absolute;
            /* يجعل النتائج تطفو فوق باقي العناصر */
            top: 100%;
            /* يظهر النتائج أسفل شريط البحث مباشرة */
            left: 0;
            width: 100%;
            background-color: white;
            z-index: 9999;
            /* تأكد أن القيمة أكبر من أي عنصر آخر */
            border: 1px solid #ddd;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
    {{-- JavaScript for AJAX Search --}}
    <script>
        $(document).ready(function() {
            $('input[id^="search"]').on("keyup", function() {
                let query = $(this).val().trim();
                let resultsBox = $(this).attr('id') === "search-mobile" ? $("#search-results-mobile") : $(
                    "#search-results-desktop");

                if (query.length > 2) { // لا تبدأ البحث إلا بعد كتابة 3 أحرف على الأقل
                    $.ajax({
                        url: "/search",
                        method: "GET",
                        data: {
                            query: query
                        },
                        success: function(data) {
                            resultsBox.html(""); // مسح النتائج القديمة

                            if (data.length > 0) {
                                data.forEach(product => {
                                    let imageUrl = (product.images.length > 0) ?
                                        `/storage/products/${product.images[0].image}` :
                                        "/default.jpg";

                                    let item = `
                                                    <div class="result-item p-2 border-b hover:bg-gray-200 cursor-pointer flex items-center">
                                                        <a href="/category/${product.category_id}" class="flex items-center space-x-3 w-full">
                                                            <img src="${imageUrl}" class="w-12 h-12 object-cover rounded-lg border" alt="${product.name}">
                                                            <span class="text-sm font-medium mx-2">${product.name}</span>
                                                        </a>
                                                    </div>
                                                `;
                                    resultsBox.append(item);
                                });
                                resultsBox.fadeIn();
                            } else {
                                resultsBox.html(
                                        '<p class="p-2 text-gray-500">لا توجد نتائج</p>')
                                    .fadeIn();
                            }
                        },
                        error: function() {
                            console.error("حدث خطأ أثناء البحث.");
                        }
                    });
                } else {
                    resultsBox.fadeOut();
                }
            });

            // إخفاء نتائج البحث عند النقر خارجها
            $(document).on("click", function(e) {
                if (!$(e.target).closest('input[id^="search"], div[id^="search-results"]').length) {
                    $('div[id^="search-results"]').fadeOut();
                }
            });
        });
    </script>

</body>

</html>
