<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إيهاب</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white">
    <nav class="flex justify-between items-center p-6 bg-gray-800 shadow-lg">
        <h1 class="text-2xl font-bold">Eihab Adel</h1>
        <ul class="flex space-x-6">
            <li><a href="#home" class="hover:text-yellow-400">الرئيسية</a></li>
            <li><a href="#projects" class="hover:text-yellow-400">المشاريع</a></li>
            <li><a href="#contact" class="hover:text-yellow-400">تواصل معي</a></li>
        </ul>
    </nav>

    <header id="home" class="flex flex-col items-center text-center py-20 px-6">
        <img src="{{ asset('storage/users/Eihab.jpg') }}" alt="Eihab Adel"
            class="rounded-full w-40 h-40 shadow-lg mb-6">
        <h2 class="text-4xl font-bold">تحويل الرؤية إلى واقع بالكود والتصميم</h2>
        <p class="mt-4 text-gray-400 max-w-xl">أنا مطور ويب متخصص في إنشاء تطبيقات ويب حديثة باستخدام PHP و Laravel.</p>
        <div class="mt-6">
            <a href="https://wa.me/201119842314" target="_blank" class="px-6 py-3 bg-yellow-500 text-black font-bold rounded-lg hover:bg-yellow-400 flex items-center gap-2">
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" class="w-6 h-6">
                تواصل معي
            </a>
        </div>
    </header>

    <section id="projects" class="py-20 px-6">
        <h3 class="text-3xl font-bold text-center mb-10">مشاريعي</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <img src="https://via.placeholder.com/300" alt="Project 1" class="rounded-lg mb-4">
                <h4 class="text-xl font-bold">مشروع 1</h4>
                <p class="text-gray-400">وصف بسيط عن المشروع</p>
            </div>
            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <img src="https://via.placeholder.com/300" alt="Project 2" class="rounded-lg mb-4">
                <h4 class="text-xl font-bold">مشروع 2</h4>
                <p class="text-gray-400">وصف بسيط عن المشروع</p>
            </div>
            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <img src="https://via.placeholder.com/300" alt="Project 3" class="rounded-lg mb-4">
                <h4 class="text-xl font-bold">مشروع 3</h4>
                <p class="text-gray-400">وصف بسيط عن المشروع</p>
            </div>
        </div>
    </section>

    <footer id="contact" class="text-center py-10 bg-gray-800">
        <h3 class="text-2xl font-bold">تواصل معي</h3>
        <p class="text-gray-400" style="direction: rtl;">يمكنك التواصل معي عبر البريد الإلكتروني: <span
                class="text-yellow-400">eihab2342@gmail.com</span></p>
        <p class="text-gray-400" style="direction: rtl;">يمكنك التواصل معي عبر الواتساب: <span
                class="text-yellow-400">+201119842314</span></p>
    </footer>
</body>

</html>
