<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'YAZAN') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    {{-- swiper --}}
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
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
    </style>


</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
        @include('layouts.footer')
    </div>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const mainImage = document.getElementById('mainImage'); // الصورة الكبيرة
            const thumbnails = document.querySelectorAll('.thumbnail'); // كل الصور المصغرة
            const modalImage = document.getElementById('modalImage'); // الصورة داخل المودال
            const imageModal = document.getElementById('imageModal'); // نافذة المودال

            // عند الضغط على صورة صغيرة، قم بتغيير الصورة الكبيرة
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    mainImage.src = this.src;
                });
            });

            // عند الضغط على الصورة الكبيرة، افتحها في المودال
            mainImage.addEventListener('click', function() {
                modalImage.src = this.src;
                imageModal.classList.remove('hidden');
            });

            // إغلاق المودال عند الضغط على الزر
            function closeModal() {
                imageModal.classList.add('hidden');
            }

            // إضافة الحدث إلى زر الإغلاق
            document.querySelector('#imageModal button').addEventListener('click', closeModal);
        });
        document.addEventListener("DOMContentLoaded", function() {
            // تحديد كل أيقونات عربة التسوق
            const cartIcons = document.querySelectorAll('.shopping-cart-icon');

            // إنشاء عنصر التوست إن لم يكن موجودًا
            let toast = document.querySelector('.toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.classList.add('toast');
                document.body.appendChild(toast);
            }

            cartIcons.forEach(icon => {
                icon.addEventListener('click', function() {
                    const productId = icon.getAttribute('data-product-id');

                    // إرسال الطلب إلى السيرفر باستخدام AJAX
                    fetch('/add-to-cart', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                product_id: productId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // تحديث عدد المنتجات في أيقونة السلة
                                let cartCount = document.querySelector('#cart-count');
                                if (cartCount) {
                                    cartCount.textContent = parseInt(cartCount.textContent) + 1;
                                }

                                // إظهار رسالة النجاح
                                showToast('تم إضافة المنتج إلى السلة!', 'success');
                            } else {
                                showToast('حدث خطأ أثناء إضافة المنتج.', 'error');
                            }
                        })
                        .catch(error => {
                            showToast('حدث خطأ في الاتصال.', 'error');
                            console.error('Error:', error);
                        });
                });
            });

            // دالة عرض التوست مع أنيميشن
            function showToast(message, type) {
                toast.textContent = message;
                toast.className = 'toast ' + type; // ضبط الكلاس
                toast.style.display = 'block';
                setTimeout(() => {
                    toast.style.opacity = '1';
                    setTimeout(() => {
                        toast.style.opacity = '0';
                        setTimeout(() => {
                            toast.style.display = 'none';
                        }, 500);
                    }, 3000);
                }, 100);
            }
        });
    </script>



</body>

</html>
