<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نجاح الطلب</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-lg p-8 text-center max-w-md">
        <div class="flex justify-center mb-4">
            <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M21 12A9 9 0 1112 3a9 9 0 019 9z"/>
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-800 mb-2">تم تأكيد طلبك بنجاح!</h1>
        <p class="text-gray-600 mb-4">شكرًا لك على طلبك. سنقوم بمعالجته قريبًا وسنتواصل معك عند الشحن.</p>

        <a href="{{ route('/')}}" class="bg-blue-600 hover:bg-blue-700 text-white  px-4 py-2 rounded-lg text-lg transition">  الصفحة الرئيسية</a>
        <a href="{{ route('MyOrders')}}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-lg transition">عرض طلباتي</a>
    </div>

</body>
</html>
