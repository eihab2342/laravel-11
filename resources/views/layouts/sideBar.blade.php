<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YAZAN | @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.js"></script>
</head>

<body class="bg-gray-100" style="direction: rtl;">

    <div class="flex h-screen">
        <!-- زر فتح وإغلاق السايد بار -->
        <button id="toggleSidebar" class="p-2 text-3xl bg-slate-100 text-gray-800 fixed top-1 right-5 z-50 rounded">
            ☰
        </button>

        <!-- Sidebar -->
        <div id="sidebar"
            class="w-64 bg-gray-800 text-white p-4 overflow-y-auto transition-transform fixed right-0 top-0 h-full transform translate-x-0">
            <h4 class="text-center text-xl font-semibold ">YAZAN</h4>
            <hr class="my-2">

            <ul>
                <li class="mb-4">
                    <a href="{{ route('dashboard') }}" class="hover:bg-gray-700 p-2 block rounded-lg text-white">الرئيسية</a>
                </li>
                <li class="mb-4">
                    <a href="{{ route('products') }}" class="hover:bg-gray-700 p-2 block rounded-lg text-white">المنتجات</a>
                </li>
                <li class="mb-4">
                    <a href="{{ route('orders') }}" class="hover:bg-gray-700 p-2 block rounded-lg text-white">الطلبات</a>
                </li>
                <li class="mb-4">
                    <a href="#" class="hover:bg-gray-700 p-2 block rounded-lg text-white">المستخدمين</a>
                </li>
                <li class="mb-4">
                    <a href="{{ route('profile.edit') }}" class="hover:bg-gray-700 p-2 block rounded-lg text-white">الإعدادات</a>
                </li>
                <li class="mt-6">
                    <a href="{{ route('logout') }}" class="hover:bg-gray-700 p-2 block rounded-lg text-white"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        تسجيل الخروج
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>

        <!-- Content Area -->
        <div class="flex-1 p-5 overflow-y-auto mr-64"> <!-- أضفنا mr-64 لمنع المحتوى من التداخل مع السايد بار -->
            <div class="bg-white p-4 shadow-md rounded-lg mb-3 text-right">
                <span class="text-gray-700"> أهلا، </span>
                <span class="font-bold"> {{ Auth::user()->name }} </span>

                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse mt-2">
                        <li class="inline-flex items-center">
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-800">
                                <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                                </svg>
                            </a>
                        </li>

                        @php
                            $segments = Request::segments();
                            $url = '';
                        @endphp

                        @foreach ($segments as $index => $segment)
                            @php
                                $url .= '/' . $segment;
                                $isLast = $loop->last;
                                $segmentName = $segment == 'admin' ? ' Dashboard' : ucfirst($segment);
                                $segmentUrl = $segment == 'admin' ? '/dashboard' : $url;
                            @endphp

                            <li class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1 rotate-180" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>

                                @if (!$isLast)
                                    <a href="{{ url($segmentUrl) }}" class="ms-1 text-sm font-semibold hover:text-green-800">
                                        {{ $segmentName }}
                                    </a>
                                @else
                                    <span class="ms-1 text-sm font-bold text-red-500">
                                        {{ $segmentName }}
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            </div>

            @yield('content')
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('sidebar');
        const toggleSidebar = document.getElementById('toggleSidebar');

        toggleSidebar.addEventListener('click', () => {
            if (sidebar.classList.contains('translate-x-0')) {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('translate-x-full');
            } else {
                sidebar.classList.remove('translate-x-full');
                sidebar.classList.add('translate-x-0');
            }
        });
    });
</script>

</body>

</html>
