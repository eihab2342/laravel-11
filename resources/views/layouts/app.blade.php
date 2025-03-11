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
    {{-- Cairo Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- swiper --}}
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }

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

<body class="font-sans antialiased flex flex-col min-h-screen">
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
        <main class="flex-grow">
            {{ $slot }}
        </main>
        <!-- الفوتر الثابت في الأسفل -->
        @include('layouts.footer')
    </div>






    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setupImageGallery();
            setupCart();
        });

        // ✅ إعداد معرض الصور
        function setupImageGallery() {
            const mainImage = document.getElementById("mainImage");
            const thumbnails = document.querySelectorAll(".thumbnail");
            const modalImage = document.getElementById("modalImage");
            const imageModal = document.getElementById("imageModal");
            const closeModalButton = document.querySelector("#imageModal button");

            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener("click", function() {
                    mainImage.src = this.src;
                });
            });

            mainImage.addEventListener("click", function() {
                modalImage.src = this.src;
                imageModal.classList.remove("hidden");
            });

            closeModalButton.addEventListener("click", function() {
                imageModal.classList.add("hidden");
            });
        }

        // ✅ إعداد عربة التسوق
        function setupCart() {
            updateCartCount();
            restoreCartCountFromStorage();
            setupCartEventListeners();
        }

        // ✅ تحديث عدد المنتجات في عربة التسوق
        function updateCartCount() {
            fetch("{{ route('cart.count') }}")
                .then(response => response.json())
                .then(data => {
                    let count = parseInt(data.count) || 0;
                    localStorage.setItem("cartCount", count);
                    updateCartDisplay(count);
                })
                .catch(error => console.error("❌ خطأ في تحديث عدد المنتجات:", error));
        }

        // ✅ استعادة عدد المنتجات المحفوظ بعد إعادة تحميل الصفحة
        function restoreCartCountFromStorage() {
            let savedCount = localStorage.getItem("cartCount") || 0;
            updateCartDisplay(savedCount);
        }

        // ✅ تحديث عرض العدد في الأيقونات المختلفة
        function updateCartDisplay(count) {
            document.querySelectorAll("#cart-count-lg, #cart-count").forEach(el => {
                el.textContent = count;
            });
        }

        // ✅ إضافة الأحداث لعربة التسوق
        function setupCartEventListeners() {
            document.addEventListener("click", function(event) {
                let target = event.target;

                if (target.classList.contains("delete-btn")) {
                    removeFromCart(target);
                } else if (target.classList.contains("add-to-cart-btn")) {
                    addToCart(target);
                } else if (target.classList.contains("shopping-cart-icon")) {
                    handleCartIconClick(target);
                }
            });
        }

        // ✅ حذف منتج من العربة
        async function removeFromCart(button) {
            let productId = button.dataset.productId;
            let cartItem = button.closest(".rounded-lg");

            try {
                let response = await fetch("{{ route('remove-from-cart') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                });

                let data = await response.json();
                if (data.success) {
                    cartItem.style.opacity = "0";
                    setTimeout(() => {
                        cartItem.remove();
                        updateCartCount();
                    }, 300);
                } else {
                    showToast("❌ فشل في حذف المنتج!", "error");
                }
            } catch (error) {
                showToast("❌ حدث خطأ أثناء حذف المنتج!", "error");
            }
        }

        // ✅ إضافة منتج إلى العربة
        let isAddingToCart = false;

        async function addToCart(button) {
            if (isAddingToCart) return;
            isAddingToCart = true;

            let productId = button.dataset.productId;
            let productType = button.dataset.type;

            try {
                let response = await fetch("/add-to-cart", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").getAttribute(
                            "content")
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        type: productType
                    })
                });

                let data = await response.json();
                if (data.success) {
                    let newCount = parseInt(localStorage.getItem("cartCount") || 0) + 1;
                    localStorage.setItem("cartCount", newCount);
                    updateCartDisplay(newCount); // ✅ تحديث العرض فورًا
                    showToast(data.message, "success");
                } else {
                    showToast("خطأ: " + data.message, "error");
                }
            } catch (error) {
                showToast("❌ حدث خطأ أثناء إضافة المنتج!", "error");
            }

            isAddingToCart = false;
        }

        // ✅ التعامل مع النقر على أيقونة العربة
        async function handleCartIconClick(icon) {
            await addToCart(icon);
        }

        // ✅ إنشاء وإدارة التوست (إشعارات تنبيهية)
        function showToast(message, type) {
            let toast = document.querySelector(".toast") || createToast();
            toast.textContent = message;
            toast.className = "toast " + type;
            toast.style.display = "block";

            setTimeout(() => {
                toast.style.opacity = "1";
                setTimeout(() => {
                    toast.style.opacity = "0";
                    setTimeout(() => {
                        toast.style.display = "none";
                    }, 500);
                }, 2000);
            }, 100);
        }

        function createToast() {
            const toast = document.createElement("div");
            toast.classList.add("toast");
            toast.style.display = "none";
            document.body.appendChild(toast);
            return toast;
        }

        // ✅ تهيئة عربة التسوق عند تحميل الصفحة
        document.addEventListener("DOMContentLoaded", setupCart);
    </script>







    {{-- <script>
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
            function updateCartCount() {
                fetch("{{ route('cart.count') }}")
                    .then(response => response.json())
                    .then(data => {
                        let count = parseInt(data.count) || 0; // تأكد أن القيمة عدد صحيح
                        console.log("Cart Count:", count); // تحقق من البيانات في الكونسول

                        // حفظ العدد في localStorage للحفاظ عليه بعد التحديث
                        localStorage.setItem("cartCount", count);

                        document.getElementById("cart-count-lg").textContent = count;
                        document.getElementById("cart-count").textContent = count;
                    })
                    .catch(error => console.error("Error fetching cart count:", error));
            }

            // تعيين العدد المحفوظ عند تحميل الصفحة
            let savedCount = localStorage.getItem("cartCount") || 0;
            document.getElementById("cart-count-lg").textContent = savedCount;
            document.getElementById("cart-count").textContent = savedCount;

            // استدعاء الوظيفة عند تحميل الصفحة
            updateCartCount();
        });



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


        document.addEventListener("DOMContentLoaded", function() {
            const cartIcons = document.querySelectorAll('.shopping-cart-icon');
            let toast = document.querySelector('.toast') || createToast();

            cartIcons.forEach(icon => {
                icon.addEventListener('click', function() {
                    const productId = icon.getAttribute('data-product-id');
                    const productType = icon.getAttribute('data-type');
                    fetch('/add-to-cart', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                type: productType,
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('خطأ في الاستجابة من السيرفر');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                updateCartCount(data.cart_count);
                                // updateCartCount();
                                showToast(data.message, 'success');
                            } else {
                                showToast('خطأ: ' + data.message, 'error');
                            }
                        })
                        .catch(error => {
                            showToast('حدث خطأ: ' + error.message, 'error');
                            console.error('Error:', error);
                        });
                });
            });

            function createToast() {
                const toast = document.createElement('div');
                toast.classList.add('toast');
                document.body.appendChild(toast);
                return toast;
            }

            function showToast(message, type) {
                toast.textContent = message;
                toast.className = 'toast ' + type;
                toast.style.display = 'block';
                setTimeout(() => {
                    toast.style.opacity = '1';
                    setTimeout(() => {
                        toast.style.opacity = '0';
                        setTimeout(() => {
                            toast.style.display = 'none';
                        }, 500);
                    }, 2000);
                }, 100);
            }


        });
    </script> --}}

</body>

</html>



{{-- // function updateCartCount() {
            //     fetch('/cart-count')
            //         .then(response => response.json())
            //         .then(data => {
            //             document.querySelectorAll('.cart-count').forEach(el => {
            //                 el.innerText = data.count;
            //             });
            //         })
            //         .catch(error => console.error('Error:', error));
            // }


            // function updateCartCount(count) {
            //     let cartCount = document.querySelector('#cart-count');
            //     if (cartCount) {
            //         cartCount.textContent = count;
            //     }
            // } --}}
