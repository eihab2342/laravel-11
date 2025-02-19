<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-md p-4">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('/') }}" class="text-3xl font-bold text-gray-800">YAZAN</a>

            <!-- Search Bar -->
            <div class="hidden md:flex flex-1 mx-6 max-w-lg">
                <input type="text" placeholder="ابحث عن منتج..."
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Navigation Links -->
            <ul class="hidden md:flex space-x-8 space-x-reverse text-gray-600 font-medium">
                {{-- <li><a href="{{ route('user') }}" class="hover:text-blue-600 transition">الرئيسية</a></li> --}}
                <li><a href="#" class="hover:text-blue-600 transition">المتجر</a></li>
                <li><a href="#" class="hover:text-blue-600 transition">العروض</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-blue-600 transition">اتصل بنا</a></li>
            </ul>

            <!-- Icons in large screens-->
            <div class="hidden md:flex items-center space-x-6 space-x-reverse">
                <button class="relative">
                    <i data-lucide="bell" class="w-6 h-6 text-gray-600"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1">3</span>
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

                <button id="search-toggle">
                    <i data-lucide="search" class="w-6 h-6 text-gray-600"></i>
                </button>
                <button class="text-gray-600" id="menu-toggle">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Search Bar -->
        <div id="mobile-search" class="hidden md:hidden p-4 bg-white">
            <input type="text" placeholder="ابحث عن منتج..."
                class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
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
        <button>
            <i data-lucide="bell" class="w-6 h-6 text-gray-600"></i>
        </button>
        <a href="{{ route('profile.edit') }}">
            <img class="w-7 h-7 rounded-full" id="avatarPreview"
                src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/MAINLOGO.jpg') }}">
        </a>
    </div>

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

        document.addEventListener("DOMContentLoaded", function() {
            function updateCartCount() {
                fetch("{{ route('cart.count') }}")
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("cart-count-lg").textContent = data.count;
                        document.getElementById("cart-count").textContent = data.count;
                    })
                    .catch(error => console.error("Error fetching cart count:", error));
            }

            // تحديث العدد كل 5 ثوانٍ
            setInterval(updateCartCount, 1500);

            updateCartCount();
            // });

            document.addEventListener("click", function(event) {
                // ✅ حذف المنتج
                if (event.target.classList.contains("delete-btn")) {
                    let productId = event.target.dataset.productId;
                    let cartItem = event.target.closest(".rounded-lg");

                    fetch("{{ route('remove-from-cart') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                product_id: productId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                cartItem.style.opacity = "0";
                                setTimeout(() => {
                                    cartItem.remove();
                                    updateCartCount(); // ✅ تحديث العدد بعد الحذف
                                }, 300);
                            }
                        })
                        .catch(() => alert("❌ حدث خطأ أثناء حذف المنتج!"));
                }

                // ✅ إضافة المنتج
                if (event.target.classList.contains("add-to-cart-btn")) {
                    // تحديث العدد لجميع الأيقونات
                    document.querySelectorAll(".cart-count, .cart-count-sm").forEach(el => {
                        el.textContent = parseInt(el.textContent) + 1;
                    });

                    setTimeout(updateCartCount, 500); // تحديث حقيقي بعد نصف ثانية
                }
            });
        });
    </script>
    <script src="{{ asset('js/custom.js') }}"></script>

</body>

</html>
