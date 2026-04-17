<?php
session_start();

if ($_SERVER['REQUEST_URI'] !== '/' && !isset($_GET['pageSize']) && !isset($_GET['page']) && !isset($_GET['minPrice']) && !isset($_GET['maxPrice']) && !isset($_GET['cart-holder']) && !isset($_GET['page-action']) && !isset($_GET['account']) && !isset($_GET['info']) && !isset($_GET['order']) && !isset($_GET['orderId'])) {
    include './404-Page/index.php';
    die();
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spoce Book Store</title>
    <link rel="icon" type="image/png" href="../media/logo/human_book.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Nhúng các thư viện Css -->
    <link rel="stylesheet" href="public/css/reset.css">
    <link rel="stylesheet" href="public/css/animation.css">
    <link rel="stylesheet" href="public/css/toast.css">
    <link rel="stylesheet" href="public/css/spinner.css">
    <link rel="stylesheet" href="public/css/base.css">

    <link rel="stylesheet" href="public/css/style.css">
    <!-- <link rel="stylesheet" href="public/css/responsive.css">git rebase --abort
 -->
    <!-- Nhúng các thư viện Js -->
    <script type="module" src="public/js/auth/authMain.js" defer></script>
    <script type="module" src="public/js/book/bookMain.js" defer></script>
    <script type="module" src="public/js/cart/cartMain.js" defer></script>
    <script type="module" src="public/js/filter/filterMain.js" defer></script>
    <script type="module" src="public/js/slideshow.js" defer></script>
    <script type="module" src="public/js/footer.js" defer></script>
    <script type="module" src="public/js/book/showBook.js" defer></script>
    <!-- responsive -->
    <link rel="stylesheet" href="public/css/responsive.css">

    <style>
        /* 1. IMPORT FONT */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Mountains+of+Christmas:wght@700&display=swap');
        /* Font tiêu đề Giáng sinh */

        #home-view {
            /* BẢNG MÀU CHUNG */
            --red-xmas: #d32f2f;
            --green-xmas: #1b5e20;
            --gold-xmas: #fbc02d;
            --cream-xmas: #fff8e1;
            --blue-midnight: #0f172a;
            --white: #ffffff;
            --text-main: #1f2937;

            /* CẤU HÌNH CHUNG */
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            max-width: 1280px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
            background: radial-gradient(circle at center, #ffffff 0%, #fff1f2 100%);
        }

        /* Reset */
        #home-view * {
            box-sizing: border-box;
        }

        #home-view a {
            text-decoration: none;
            transition: 0.3s;
        }

        #home-view img {
            max-width: 100%;
            display: block;
        }

        #home-view .section-margin {
            margin-bottom: 70px;
        }

        /* TIÊU ĐỀ CHUNG ĐƯỢC CÁCH ĐIỆU */
        #home-view .section-title-modern {
            font-family: 'Inter', sans-serif;
            /* Hoặc 'Mountains of Christmas' nếu muốn nghệ thuật hơn */
            font-size: 2.5rem;
            font-weight: 800;
            position: relative;
            display: inline-block;
            margin-bottom: 30px;
        }

        /* =================================================
       PHẦN 1: HERO BANNER - PHONG CÁCH "TRUYỀN THỐNG RỰC RỠ"
       (Đỏ, Xanh, Vàng Gold - Sang trọng)
       ================================================= */
        #home-view .hero-banner {
            background: linear-gradient(135deg, #db0101ff 40%, #15803d 60%);
            /* Gradient Đỏ - Xanh */
            border-radius: 20px;
            padding: 50px;
            color: var(--white);
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            border: 6px double #fbc02d;
            /* Viền đôi màu vàng kim */
        }

        /* Hiệu ứng tuyết rơi nền */
        /* #home-view .hero-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.2) 2px, transparent 2px);
            background-size: 30px 30px;
            opacity: 0.5;
        } */

        #home-view .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 40px;
            position: relative;
            z-index: 2;
        }

        #home-view .badge-hero {
            background: var(--gold-xmas);
            color: #854d0e;
            padding: 5px 15px;
            font-weight: 800;
            border-radius: 30px;
            text-transform: uppercase;
            box-shadow: 0 0 15px var(--gold-xmas);
        }

        #home-view .hero-title {
            font-size: 4rem;
            font-weight: 900;
            margin: 15px 0;
            text-shadow: 2px 2px 0 #000;
        }

        #home-view .hero-desc {
            font-size: 3rem;
            margin-bottom: 30px;
            color: #fef2f2;
        }

        @keyframes wiggle {

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(-3deg);
            }

            75% {
                transform: rotate(3deg);
            }
        }

        #home-view .btn-white {
            background: var(--white);
            color: var(--red-xmas);
            font-weight: bold;
            padding: 12px 30px;
            border-radius: 30px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            animation: wiggle 0.8s ease-in-out infinite;
            box-shadow: 0 8px 15px rgba(255, 255, 255, 0.5);
            scale: 1.1;
        }

        #home-view .btn-white:hover {
            background: #fff;
            color: var(--red-xmas);
        }

        #home-view .btn-outline {
            border: 2px solid var(--white);
            color: var(--white);
            background: transparent;
            font-weight: bold;
            padding: 12px 30px;
            border-radius: 30px;
            margin-left: 10px;
            cursor: pointer;
        }

        #home-view .btn-outline:hover {
            border-color: var(--gold-xmas);
            box-shadow: 0 0 10px var(--gold-xmas);
        }

        #home-view .hero-image img {
            max-height: 600px;
            margin: 0 auto;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.5));
            animation: float 4s ease-in-out infinite;
            object-fit: contain;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        /* =================================================
       PHẦN 2: USP BAR - PHONG CÁCH "BĂNG TUYẾT" (ICY)
       (Trắng, Xanh dương nhạt, Sạch sẽ)
       ================================================= */
        #home-view .usp-bar {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            background: #f0f9ff;
            /* Nền xanh băng cực nhạt */
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 60px;
            border: 1px solid #bae6fd;
        }

        #home-view .usp-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        #home-view .usp-icon {
            width: 80px;
            height: 80px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #009de6ff;
            font-size: 2rem;
            box-shadow: 0 4px 6px rgba(14, 165, 233, 0.2);
        }

        #home-view .usp-text h4 {
            color: #0c4a6e;
            font-weight: 700;
            margin: 0;
            font-size: 2rem;
        }

        #home-view .usp-text p {
            color: #64748b;
            font-size: 1.5rem;
            margin: 0;
        }

        /* =================================================
       PHẦN 3: SÁCH MỚI - PHONG CÁCH "BÁNH QUY GỪNG" (COZY)
       (Màu kem, Nâu, Viền nét đứt, Ấm áp)
       ================================================= */
        #home-view .featured-section {
            background-color: #fffbeb;
            /* Màu kem ấm */
            padding: 40px;
            border-radius: 20px;
            border: 3px dashed #d97706;
            /* Viền nét đứt màu cam nâu */
            position: relative;
        }

        /* Trang trí góc */
        #home-view .featured-section::after {
            content: '🍪';
            position: absolute;
            top: -20px;
            right: 20px;
            font-size: 40px;
        }

        #home-view .section-header-cozy h2 {
            color: #92400e;
            /* Màu nâu gỗ */
            border-bottom: 4px solid #f59e0b;
        }

        #home-view .grid-products {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }

        /* Card sách kiểu Cozy */
        #home-view .product-card-modern {
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            transition: 0.3s;
            border: 1px solid #fde68a;
            /* Viền vàng nhạt */
            box-shadow: 0 4px 0 #fcd34d;
            /* Bóng đổ cứng tạo cảm giác khối */
        }

        #home-view .product-card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 0 #f59e0b;
        }

        #home-view .card-img {
            height: 200px;
            padding: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #fff;
        }

        #home-view .card-img img {
            max-height: 100%;
            object-fit: contain;
        }

        /* Nút action ẩn */
        #home-view .card-actions {
            position: absolute;
            inset: 0;
            background: rgba(251, 191, 36, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            opacity: 0;
            transition: 0.3s;
        }

        #home-view .product-card-modern:hover .card-actions {
            opacity: 1;
        }

        #home-view .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: #fff;
            color: #d97706;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        #home-view .action-btn:hover {
            background: #d97706;
            color: #fff;
        }

        #home-view .card-info {
            padding: 15px;
        }

        #home-view .card-title {
            font-weight: 700;
            color: #5f2300ff;
            margin: 5px 0;
            font-size: 2rem;
            min-height: 40px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        #home-view .price-now {
            color: #dc2626;
            font-weight: 800;
            font-size: 1.1rem;
        }

        #home-view .badge-sale {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #ef4444;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: bold;
        }

        /* =================================================
       PHẦN 4: MID BANNER - PHONG CÁCH "ĐÊM THÁNH" (MIDNIGHT)
       (Xanh đêm, Sao vàng, Huyền bí)
       ================================================= */
        #home-view .mid-banner {
            background: linear-gradient(to bottom, #0f172a, #1e293b);
            border-radius: 20px;
            padding: 60px;
            color: white;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            margin-bottom: 70px;
            border: 1px solid #334155;
        }

        /* Sao lấp lánh giả lập */
        #home-view .mid-banner-bg {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(#ffffff 1px, transparent 1px);
            background-size: 50px 50px;
            opacity: 0.3;
        }

        #home-view .mid-content {
            flex: 1;
            z-index: 2;
        }

        #home-view .mid-content h2 {
            font-family: 'Inter', serif;
            font-size: 2.5rem;
            color: #fcd34d;
            text-shadow: 0 0 10px #fcd34d;
            margin-bottom: 15px;
        }

        #home-view .mid-content button {
            background: #fcd34d;
            color: #0f172a;
            font-weight: bold;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 0 15px rgba(253, 211, 77, 0.5);
        }

        #home-view .mid-img {
            flex: 1;
            display: flex;
            justify-content: flex-end;
            z-index: 2;
        }

        #home-view .mid-img img {
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.2);
            transform: rotate(-3deg);
            border: 3px solid #334155;
        }

        /* =================================================
       PHẦN 5: SALE & BEST SELLER - PHONG CÁCH "KẸO NGỌT" (CANDY CANE)
       (Sọc Đỏ Trắng, Vui nhộn)
       ================================================= */
        #home-view .split-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 70px;
        }

        /* Tiêu đề kiểu kẹo ngọt */
        #home-view .candy-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: #ba0505ff;
            background: repeating-linear-gradient(45deg, transparent, transparent 10px, #fecaca 10px, #fecaca 20px);
            display: inline-block;
            padding: 5px 15px;
            border-radius: 15px;
            margin-bottom: 20px;
        }

        #home-view .list-small-card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        #home-view .card-small {
            display: flex;
            padding: 10px;
            background: white;
            border: 2px solid #fee2e2;
            border-radius: 15px;
            transition: 0.3s;
            cursor: pointer;
        }

        #home-view .card-small:hover {
            border-color: #ef4444;
            box-shadow: 5px 5px 0 #fecaca;
            /* Bóng đổ kiểu pop-art */
            transform: translate(-2px, -2px);
        }

        #home-view .card-small img {
            width: 80px;
            border-radius: 8px;
            object-fit: cover;
        }

        #home-view .small-info {
            margin-left: 15px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        #home-view .small-info h4 {
            font-size: 0.9rem;
            font-weight: 700;
            margin: 0 0 5px;
            color: #333;
        }

        #home-view .small-info span {
            color: #dc2626;
            font-weight: 700;
        }

        /* =================================================
       PHẦN 6: TÁC GIẢ - PHONG CÁCH "QUẢ CHÂU LUNG LINH" (ORNAMENTS)
       (Treo lơ lửng, đung đưa, lấp lánh)
       ================================================= */
        #home-view .author-section {
            text-align: center;
            margin-bottom: 80px;
            /* Thêm hình nền dây treo trang trí (nếu muốn) */
            position: relative;
            padding-top: 40px;
        }

        #home-view .wreath-title {
            color: #15803d;
            font-family: 'Mountains of Christmas', cursive, sans-serif;
            /* Font nghệ thuật */
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 50px;
            text-shadow: 2px 2px 0px #fcd34d;
            /* Bóng chữ vàng */
        }

        #home-view .author-list {
            display: flex;
            justify-content: center;
            gap: 60px;
            flex-wrap: wrap;
        }

        #home-view .author-item {
            cursor: pointer;
            position: relative;
            z-index: 1;
            /* Animation đung đưa mặc định */
            transform-origin: top center;
            animation: swing-mild 3s ease-in-out infinite alternate;
        }

        /* Dây treo quả châu (Vẽ bằng CSS) */
        #home-view .author-item::before {
            content: '';
            position: absolute;
            top: -30px;
            /* Độ dài dây */
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 100px;
            background: #f59e0b;
            /* Dây màu vàng */
            z-index: -1;
        }

        /* Cái nơ/móc treo (Vẽ bằng CSS hoặc Emoji) */
        #home-view .author-item::after {
            content: '🎀';
            /* Dùng Emoji nơ cho xinh */
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 24px;
            z-index: 2;
            filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.2));
        }

        #home-view .author-img-box {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            overflow: hidden;
            /* Viền quả châu sang chảnh */
            border: 4px solid #fcd34d;
            background: white;
            /* Bóng đổ tạo khối cầu 3D */
            box-shadow:
                inset 0 0 20px rgba(0, 0, 0, 0.2),
                /* Bóng trong */
                0 10px 20px rgba(0, 0, 0, 0.3);
            /* Bóng ngoài */
            margin: 0 auto 15px;
            transition: 0.3s;
            position: relative;
        }

        /* Hiệu ứng bóng kính (Glass reflection) trên quả châu */
        #home-view .author-img-box::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 20px;
            width: 30px;
            height: 15px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            transform: rotate(-45deg);
            z-index: 2;
        }

        /* Hover vào: Dừng đung đưa, phát sáng mạnh */
        #home-view .author-item:hover {
            animation-play-state: paused;
            /* Dừng lắc */
            transform: scale(1.1);
            /* Phóng to */
            z-index: 10;
        }

        #home-view .author-item:hover .author-img-box {
            border-color: #ef4444;
            /* Đổi viền đỏ */
            box-shadow: 0 0 25px #ef4444;
            /* Glow đỏ rực */
        }

        #home-view .author-name {
            font-weight: 800;
            color: #1f2937;
            margin: 0;
            font-size: 2rem;
            background: rgba(255, 255, 255, 0.9);
            padding: 2px 10px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Tên tác giả đổi màu khi hover */
        #home-view .author-item:hover .author-name {
            color: #ef4444;
        }

        /* Animation Đung đưa */
        @keyframes swing-mild {
            0% {
                transform: rotate(3deg);
            }

            100% {
                transform: rotate(-3deg);
            }
        }

        /* Tạo độ trễ để các quả châu không lắc cùng lúc (Tự nhiên hơn) */
        #home-view .author-item:nth-child(odd) {
            animation-duration: 3.5s;
            animation-delay: 0.5s;
        }

        #home-view .author-item:nth-child(even) {
            animation-duration: 4s;
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            #home-view .grid-products {
                grid-template-columns: repeat(3, 1fr);
            }

            #home-view .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            #home-view .usp-bar {
                grid-template-columns: 1fr 1fr;
            }

            #home-view .split-section {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            #home-view .grid-products {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }

            #home-view .card-img {
                height: 150px;
            }

            #home-view .mid-banner {
                flex-direction: column-reverse;
                text-align: center;
                padding: 30px;
            }

            #home-view .mid-content {
                padding: 0;
                margin-top: 20px;
            }

            #home-view .hero-title {
                font-size: 2.5rem;
            }
        }
    </style>

</head>

<body>
    <!-- Thông báo -->
    <div id="toast"></div>


    <!-- Nội dung Web -->
    <div class="topbar">
        <div class="topbar__container container d-flex just-content-spbt">
            <div class="topbar__contact d-flex">
                <div class="topbar__contact-item margin-right-medium">
                    <i class="topbar__contact-icon fa-solid fa-phone-volume"></i>
                    <span class="topbar__contact-text">0388.853.835</span>
                </div>
                <div class="topbar__contact-item margin-right-medium">
                    <i class="topbar__contact-icon fa-solid fa-envelope-open-text"></i>
                    <span class="topbar__contact-text">spoce_bookstore@gmail.com</span>
                </div>
                <div class="topbar__contact-item margin-right-medium">
                    <i class="topbar__contact-icon fa-solid fa-location-dot "></i>
                    <span class="topbar__contact-text">273 An Dương Vương, P2, Q5, TP.HCM</span>
                </div>
            </div>
            <div class="topbar__auth d-flex ">
                <div class="topbar__auth-btn topbar__auth-btn--login margin-right-medium"
                    onclick="showFormUser('login')">
                    <i class="fa-solid fa-street-view"></i>
                    <span id="login-form">Đăng&nbsp;nhập</span>
                </div>
                <div class="topbar__auth-btn topbar__auth-btn--register margin-right-medium "
                    onclick="showFormUser('register')">
                    <i class="fa-solid fa-user-pen"></i>
                    <span id="register-form">Đăng&nbsp;ký</span>
                </div>
            </div>
        </div>

        <div class="topbart__cart-action">
            <span class="topbar__cart-holder" onclick="viewCart(null)">
                <i class="fa-solid fa-cart-shopping topbar__cart-icon"></i>
                <span class="topbar__count-holder">
                    <span class="topbar__count">0</span>
                </span>
            </span>

            <div class="topbar__cart-detail-holder"></div>
        </div>
    </div>

    <header class="header">
        <div class="header__container container d-flex align-items-center just-content-spbt">
            <a href="/" class="header__logo">
                <img src="../media/logo/public_logo.png" alt="Logo Web">
            </a>

            <div class="header__search">
                <!-- Icon mở search mobile -->
                <div class="header__search-icon" onclick="toggleSearch()">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <!-- Lớp phủ trắng toàn màn hình -->

                <div class="header__search-wrapper mobile" id="searchWrapper" style="display: none;">
                    <input class="header__search-input" type="text" placeholder="Tìm kiếm sản phẩm">
                    <button class="header__search-btn">Tìm kiếm</button>
                </div>

                <!-- Khung ô tìm kiếm -->
                <div class="header__search-wrapper desktop">
                    <input class="header__search-input" type="text" placeholder="Tìm kiếm sản phẩm">
                    <button class="header__search-btn">Tìm kiếm</button>
                </div>
            </div>


            <div class="header__support d-flex just-content-spbt align-items-center">
                <i class="header__support-icon fa-solid fa-blender-phone"></i>
                <div class="header__support-info">
                    <span>Hỗ trợ khách hàng</span>
                    <div></div>
                    <span>0388 853 835</span>
                </div>
            </div>

            <div class="result-search">
                <div class="result-search__wrapper">
                    <div class="result-search__wrapper-title"></div>
                    <div class="result-search__list">

                    </div>

                    <i class="result-search__close fa-solid fa-xmark" id="close-result-search"></i>
                </div>
            </div>
        </div>

        <div class="menu-container">
            <div class="menu">
                <?php
                require_once __DIR__ . '/app/config.php';

                $category_model = new app_models_TheLoai();
                $categoryList = $category_model->getAllCategories();

                echo '<a class="menu-item" data-id="home-view">Trang chủ</a>';

                echo '<a class="menu-item" data-id="all-category">Tất cả</a>';

                // Hiển thị link Trang quản lý nếu người dùng đã đăng nhập và có phân quyền
                if (!empty($_SESSION['role']['data'])) {
                    echo '<a class="menu-item menu-item--admin" href="/admin/" style="color:#ffd700;font-weight:600;">Trang quản lý</a>';
                }
                foreach ($categoryList as $row) {
                    if ($row['trangThai'] == 'Hoạt động') {
                        echo '<a class="menu-item" data-id="' . $row['maTheLoai'] . '">' . $row['tenTheLoai'] . '</a>';
                    }
                }
                ?>
            </div>
        </div>
    </header>

    <div class="main">
        <div class="main__container container d-flex just-content-spbt">
            <section class="content">
                <div class="slider" style="height: 500px; object-fit: cover;">
                    <button class="slider__btn slider__btn--left">&#10094;</button>
                    <div class="slide-container">
                        <div class="slide"><img class="slider__image" src="../media/banner/xmasBanner2.png"
                                alt="Banner">
                        </div>
                        <div class="slide"><img class="slider__image" src="../media/banner/xmas3.png" alt="Banner">
                        </div>
                        <div class="slide"><img class="slider__image" src="../media/banner/xmasBanner6.png"
                                alt="Banner">
                        </div>
                        <div class="slide"><img class="slider__image" src="../media/banner/banner9.png" alt="Banner">
                        </div>
                    </div>
                    <button class="slider__btn slider__btn--right">&#10095;</button>
                    <div class="slide-position-btn-container">
                        <button class="position-btn" data-id="0"><span></span></button>
                        <button class="position-btn" data-id="1"><span></span></button>
                        <button class="position-btn" data-id="2"><span></span></button>
                        <button class="position-btn" data-id="3"><span></span></button>
                    </div>
                </div>
                <div class="banner d-flex just-content-spbt">
                    <img class="banner__image" src="../media/banner/xmasBanner3.png" alt="">
                    <img class="banner__image" src="../media/banner/banner_3.png" alt="">
                </div>
            </section>
        </div>
    </div>

    <main class="body">
        <!-- ==================== VIEW TRANG CHỦ (HIỂN THỊ MẶC ĐỊNH) ==================== -->

        <div id="home-view" class="container-custom">

            <section class="section-margin">
                <div class="hero-banner">
                    <div class="hero-content">
                        <div>
                            <span class="badge-hero">Best Choice of December 2025</span>
                            <h1 class="hero-title">Khám Phá Thế Giới Phục Sinh <br><span
                                    style="color: var(--yellow);">Qua Trang
                                    Sách</span></h1>
                            <p class="hero-desc">
                                Câu chuyện kể về cuộc phiêu lưu trong đêm giáng sinh của ông già Ebezener Scrooger.
                            </p>
                            <div class="hero-btns">
                                <button class="btn btn-white">Mua Ngay</button>
                                <button class="btn btn-outline" onclick="showDetailProduct(80)">Xem Chi Tiết</button>
                            </div>
                        </div>
                        <div class="hero-image">
                            <img src="../media/banner/xmasBook.png" alt="Hero Book">
                        </div>
                    </div>
                    <div class="bg-circle bg-c-1"></div>
                    <div class="bg-circle bg-c-2"></div>
                </div>
            </section>

            <section class="usp-bar">
                <div class="usp-item">
                    <div class="usp-icon bg-red-light"><i class="fa-solid fa-truck-fast"></i></div>
                    <div class="usp-text">
                        <h4>Miễn Phí Ship</h4>
                        <p>Đơn hàng > 200k</p>
                    </div>
                </div>
                <div class="usp-item">
                    <div class="usp-icon bg-blue-light"><i class="fa-solid fa-rotate-left"></i></div>
                    <div class="usp-text">
                        <h4>Đổi Trả 7 Ngày</h4>
                        <p>Thủ tục đơn giản</p>
                    </div>
                </div>
                <div class="usp-item">
                    <div class="usp-icon bg-green-light"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="usp-text">
                        <h4>Thanh Toán An Toàn</h4>
                        <p>Bảo mật 100%</p>
                    </div>
                </div>
                <div class="usp-item">
                    <div class="usp-icon bg-yellow-light"><i class="fa-solid fa-headset"></i></div>
                    <div class="usp-text">
                        <h4>Hỗ Trợ 24/7</h4>
                        <p>Hotline: 0912345678</p>
                    </div>
                </div>
            </section>

            <section class="section-margin featured-section">
                <div class="section-header section-header-cozy">
                    <h2 class="section-title-modern">Sách Mới & Nổi Bật 🍪</h2>
                </div>
                <div class="grid-products" id="featured-books-list">
                </div>
            </section>

            <section class="section-margin mid-banner">
                <div class="mid-banner-bg"></div>
                <div class="mid-content">
                    <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 15px;">Tuần Lễ Giáng Sinh</h2>
                    <p style="margin-bottom: 30px; opacity: 0.9;">Giáng sinh là món quà đặc biệt dành cho bạn.</p>
                    <button class="btn" style="background: var(--primary); color: white;">Khám Phá Ngay</button>
                </div>
                <div class="mid-img">
                    <img src="../media/Banner/xmasBanner.png" alt="Ads">
                </div>
            </section>

            <div class="section-margin">
                <section style="margin-bottom: 50px;">
                    <div class="section-header">
                        <h2 class="candy-title">Sách Bán Chạy 🍬</h2>
                    </div>
                    <div class="grid-products" id="sale-books-list">
                        <div class="card-small">
                            <img src="https://placehold.co/100x140" alt="Book">
                            <div class="small-info">
                                <h4 style="font-weight: 700; font-size: 0.9rem; margin-bottom: 5px;">Đắc Nhân Tâm</h4>
                                <p style="font-size: 0.8rem; color: var(--text-light);">Dale Carnegie</p>
                                <p style="color: var(--primary); font-weight: 700; margin-top: 5px;">75.000đ</p>
                            </div>
                        </div>
                        <div class="card-small">
                            <img src="https://placehold.co/100x140" alt="Book">
                            <div class="small-info">
                                <h4 style="font-weight: 700; font-size: 0.9rem; margin-bottom: 5px;">Nhà Giả Kim</h4>
                                <p style="font-size: 0.8rem; color: var(--text-light);">Paulo Coelho</p>
                                <p style="color: var(--primary); font-weight: 700; margin-top: 5px;">69.000đ</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section style="margin-bottom: 50px;">
                    <div class="section-header">
                        <h2 class="candy-title">Sale Cuối Năm 🍭</h2>
                    </div>
                    <div class="grid-products" id="discount-books-list">
                        <div class="card-small">
                            <div style="position: relative;">
                                <span
                                    style="position: absolute; top:0; left:0; background: var(--primary); color: white; font-size: 0.6rem; padding: 2px 5px; border-radius: 4px;">-50%</span>
                                <img src="https://placehold.co/100x140" alt="Book">
                            </div>
                            <div class="small-info">
                                <h4 style="font-weight: 700; font-size: 0.9rem; margin-bottom: 5px;">Tuổi Trẻ Đáng
                                    Giá...</h4>
                                <p style="font-size: 0.8rem; color: var(--text-light);">Rosie Nguyễn</p>
                                <div style="margin-top: 5px;">
                                    <span style="color: var(--primary); font-weight: 700;">40.000đ</span>
                                    <span
                                        style="text-decoration: line-through; font-size: 0.7rem; color: #999;">80.000đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <section class="section-margin">
                <h2 class="wreath-title" style="text-align: center;">
                    Tác Giả Tiêu Biểu 🎄</h2>
                <div class="author-list">
                    <div class="author-item">
                        <div class="author-img-box">
                            <img src="../media/Banner/author.png" alt="TG">
                        </div>
                        <h4 class="author-name">Nguyễn Nhật Ánh</h4>
                        <p style="font-size: 1.4rem; color: var(--text-light);">7 Tác phẩm</p>
                    </div>
                    <div class="author-item">
                        <div class="author-img-box">
                            <img src="../media/Banner/author2.png" alt="TG">
                        </div>
                        <h4 class="author-name">Gào</h4>
                        <p style="font-size: 1.4rem; color: var(--text-light);">7 Tác phẩm</p>
                    </div>
                    <div class="author-item">
                        <div class="author-img-box">
                            <img src="../media/Banner/author3.png" alt="TG">
                        </div>
                        <h4 class="author-name">Minh Nhật</h4>
                        <p style="font-size: 1.4rem; color: var(--text-light);">7 Tác phẩm</p>
                    </div>
                </div>
            </section>

        </div>

        <div id="product-list-view" class="hiden-item">
            <div class="body__container container d-flex just-content-spbt">
                <!--  -->
                <button class="filter-toggle" onclick="toggleFilter()">
                    <i class="fa fa-bars"></i> Lọc
                </button>

                <!-- Overlay nền mờ -->
                <div class="overlay-filter" onclick="toggleFilter()"></div>
                <!--  -->
                <section class="book-filter">
                    <div class="book-filter__title pd-filter">Lọc sản phẩm</div>
                    <div class="book-filter__list">

                        <script>
                            function showFilter(element) {
                                const contentFilter = element.closest(".filter-group").querySelector(".filter-group__content");

                                contentFilter.classList.toggle("hide-item");

                                element.classList.toggle("fa-minus");
                                element.classList.toggle("fa-plus");
                            }
                        </script>

                        <!-- Bộ lọc Giá -->
                        <div class="filter-group pd-filter">
                            <div class="filter-group__header d-flex just-content-spbt">
                                <p class="filter-group__title">Giá</p>
                                <i class="filter-group__toggle fa-solid fa-minus" onclick="showFilter(this)"></i>
                            </div>
                            <div class="filter-group__content list-price-content">
                                <div class="filter-group__inputs">
                                    <div class="input-wraper">
                                        <input type="text" class="filter-group__input" value="0">
                                    </div>
                                    <div class="input-wraper">
                                        <input type="text" class="filter-group__input" value="500,000">
                                    </div>
                                </div>
                                <div class="filter-group__range">
                                    <div id="price-slider"></div>
                                    <p><span id="min-price">0</span>đ - <span id="max-price">500,000</span>đ</p>
                                </div>
                            </div>
                        </div>

                        <!-- Bộ lọc tác giả -->
                        <div class="filter-group pd-filter">
                            <div class="filter-group__header d-flex just-content-spbt">
                                <p class="filter-group__title">Tác giả</p>
                                <i class="filter-group__toggle fa-solid fa-minus" onclick="showFilter(this)"></i>
                            </div>
                            <!-- Ô nhập tìm kiếm tác giả -->
                            <!-- <div class="filter-group__search">
                            <input type="text" class="filter-group__search-input" placeholder="Nhập tên tác giả . . .">
                        </div> -->

                            <div class="filter-group__content list-author-content">
                                <?php
                                // include_once 'app/config.php';
                                require_once __DIR__ . '/app/config.php';

                                $author_model = new app_models_TacGia();
                                $authors = $author_model->getAllAuthors();
                                $total_authors = is_array($authors) ? count($authors) : 0;
                                $limit = 5;

                                foreach ($authors as $index => $author) {
                                    $author_id = $author['maTacGia'];
                                    $author_name = $author['tenTacGia'];
                                    $hidden_class = $index >= $limit ? 'hide-item' : '';

                                    echo "
                                    <div class=\"filter-group__option $hidden_class\">
                                        <input type=\"checkbox\" class=\"filter-group__checkbox\" value=\"$author_id\"> $author_name
                                    </div>";
                                }

                                if ($total_authors > $limit) {
                                    echo '
                                    <div class="show-list-author show-more margin-top-small">
                                        <a href="#">Hiển thị tất cả (' . $total_authors . ')</a>
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>';
                                }
                                ?>
                            </div>
                        </div>

                        <!-- Bộ lọc nhà xuất bản -->
                        <div class="filter-group pd-filter">
                            <div class="filter-group__header d-flex just-content-spbt">
                                <p class="filter-group__title">Nhà xuất bản</p>
                                <i class="filter-group__toggle fa-solid fa-minus" onclick="showFilter(this)"></i>
                            </div>
                            <!-- Ô nhập tìm kiếm tác giả -->
                            <!-- <div class="filter-group__search">
                            <input type="text" class="filter-group__search-input" placeholder="Nhập nhà xuất bản . . .">
                        </div> -->

                            <div class="filter-group__content list-publisher-content">
                                <?php
                                include_once 'app/config.php';

                                $publisher_model = new app_models_NhaXuatBan();
                                $publishers = $publisher_model->getAllPublishers();

                                $total_publisher = count($publishers);
                                $limit = 5;

                                foreach ($publishers as $index => $publisher) {
                                    $publisher_id = $publisher['maNXB'];
                                    $publisher_name = $publisher['tenNXB'];
                                    $hidden_class = $index >= $limit ? 'hide-item' : '';

                                    echo "
                                    <div class=\"filter-group__option $hidden_class\">
                                        <input type=\"checkbox\" class=\"filter-group__checkbox\" value=\"$publisher_id\"> $publisher_name
                                    </div>";
                                }

                                if ($total_authors > $limit) {
                                    echo '
                                    <div class="show-list-publisher show-more margin-top-small">
                                        <a href="#">Hiển thị tất cả (' . $total_publisher . ')</a>
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>';
                                }
                                ?>
                            </div>
                        </div>

                        <!-- Bộ lọc loại bìa -->
                        <div class="filter-group pd-filter">
                            <div class="filter-group__header d-flex just-content-spbt">
                                <p class="filter-group__title">Loại bìa</p>
                                <i class="filter-group__toggle fa-solid fa-minus" onclick="showFilter(this)"></i>
                            </div>
                            <!-- Ô nhập tìm kiếm tác giả -->
                            <!-- <div class="filter-group__search">
                            <input type="text" class="filter-group__search-input" placeholder="Nhập tên bìa . . .">
                        </div> -->

                            <div class="filter-group__content list-cover-content">
                                <?php
                                // include_once 'app/config.php';
                                require_once __DIR__ . '/app/config.php';

                                $cover_model = new app_models_LoaiBia();
                                $covers = $cover_model->getAllCovers();

                                $total_cover = count($covers);
                                $limit = 5;

                                foreach ($covers as $index => $cover) {
                                    $cover_id = $cover['maLoaiBia'];
                                    $cover_name = $cover['tenLoaiBia'];
                                    $hidden_class = $index >= $limit ? 'hide-item' : '';

                                    echo "
                                            <div class=\"filter-group__option $hidden_class\">
                                                <input type=\"checkbox\" class=\"filter-group__checkbox\" value=\"$cover_id\"> $cover_name
                                            </div>";
                                }

                                if ($total_authors > $limit) {
                                    echo '
                                            <div class="show-list-cover show-more margin-top-small">
                                                <a href="#">Hiển thị tất cả (' . $total_cover . ')</a>
                                                <i class="fa-solid fa-chevron-down"></i>
                                            </div>';
                                }
                                ?>
                            </div>

                        </div>

                    </div>
                </section>

                <section class="book-category">
                    <h2 class="book-category__title">Danh sách sản phẩm</h2>

                    <div class="book-category__sort d-flex">

                        <!-- <div class="book-category__sort-item">
                        <label for="type-category">Thể loại: </label>
                        <select name="" id="type-category">
                            <option value="all-category" selected>Tất cả</option>
                            <?php
                            // include_once 'app/config.php';
                            require_once __DIR__ . '/app/config.php';

                            $category_model = new app_models_TheLoai();

                            $categories = $category_model->getAllCategories();

                            if (!$categories) {
                                // Xử lý trường hợp chưa có sách nào
                            } else {
                                foreach ($categories as $cate) {
                                    $id_cate = $cate['maTheLoai'];
                                    $name_cate = $cate['tenTheLoai'];
                                    echo "
                                            <option value=\"$id_cate\">$name_cate</option>
                                        ";
                                }
                            }
                            ?>
                        </select>
                    </div> -->



                        <div class="book-category__sort-item">
                            <label for="sort-combobox">Sắp xếp theo: </label>
                            <select name="" id="sort-combobox">
                                <option value="base" selected>Mặc định</option>
                                <option value="desc">Giá giảm dần</option>
                                <option value="asc">Giá tăng dần</option>
                            </select>
                        </div>

                        <div class="book-category__sort-item">
                            <label for="page-show-by">Hiển thị theo: </label>
                            <select name="" id="page-show-by">
                                <!-- <option value="10" selected>Mặc định</option>
                            <option value="15">15 sản phẩm</option>
                            <option value="20">20 sản phẩm</option>
                            <option value="25">25 sản phẩm</option> -->
                            </select>
                        </div>

                        <!-- <div class="book-category__button d-flex">
                        <div class="btn sort-btn" onclick="filterBookList()">Lọc sách</div>
                        <div class="btn reset-btn" onclick="resetFilterBook()">Đặt lại</div>
                    </div> -->
                    </div>

                    <div class="book-category__list" id="book-list">
                        <!-- Hiển thị danh sách sản phẩm -->
                    </div>

                    <!-- Nút phân trang -->
                    <div id="pagination" class="pagination"></div>

                </section>
            </div>
        </div>
    </main>


    <div class="scroll-to-top-container">
        <span class="scroll-to-top-btn">
            <i class="fa-solid fa-up-long"></i>
        </span>
    </div>

    <div class="show-cart hide-item">
        <div class="show-cart__container">
            <div class="show-cart__title">
                GIỎ HÀNG
            </div>

            <div class="show-cart__cart">

            </div>


            <div class="show-cart__checkout">
                <div class="show-cart__checkout-info">
                    <div class="show-cart__checkout-title">Thông tin đơn hàng</div>
                    <div class="show-cart__totalprice">Tổng số tiền: <span>220.000đ</span></div>
                    <span>Bạn có thể nhập mã giảm giá ở trang thanh toán.</span>
                    <p><a href="#" class="show-cart__continue-buy-btn"><i class="fa fa-reply"></i> Tiếp tục mua hàng</a>
                    </p>
                </div>
                <div class="show-cart__checkoutbox">
                    <button class="show-cart__to-checkout-btn"><i class="fa-regular fa-circle-check"></i> Thanh
                        toán</button>
                    <button><i class="fa-solid fa-circle-xmark"></i> Xóa tất cả</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin cá nhân khách hàng -->
    <div class="self-infomation hide-item">
        <div class="info__container">
            <div class="info__title">THÔNG TIN TÀI KHOẢN</div>
            <div class="info-content d-flex">
                <div class="left-container">
                    <ul>
                        <li class="information">Tài khoản & Bảo mật</li>
                        <li class="address">Địa chỉ</li>
                    </ul>
                </div>
                <div class="right-container">

                </div>
            </div>
        </div>
    </div>

    <!-- Lịch sử mua hàng của khách hàng -->
    <div class="order-history hide-item">

    </div>

    <div class="checkout"></div>




    <div class="footer-info hide-item">
        <div class="footer-info__container">

        </div>
    </div>

    <footer class="footer">
        <div class="footer__container container">
            <div class="footer__top">
                <div class="footer__top-container">
                    <div class="footer__top-item">
                        <h3>VỀ CÔNG TY</h3>
                        <ul>
                            <li class="footer__aboutus">Giới thiệu công ty</li>
                            <li class="footer__contact">Liên hệ</li>
                            <li class="footer__warranty">Chính sách đổi trả</li>
                            <li class="footer__security">Chính sách bảo mật</li>
                        </ul>
                    </div>
                    <div class="footer__top-item">
                        <h3>TRỢ GIÚP</h3>
                        <ul>
                            <li class="footer__howto">Hướng dẫn mua hàng</li>
                            <li class="footer__shipment">Chính sách vận chuyển</li>
                            <li class="footer__payment">Chính sách thanh toán</li>
                        </ul>
                    </div>
                    <div class="footer__top-item">
                        <h3>CHẤP NHẬN THANH TOÁN</h3>
                        <div class="footer__payment-image-container">
                            <img src="public/images/footer_logo_payment_1.png" alt="payment1">
                            <img src="public/images/footer_logo_payment_2.png" alt="payment2">
                            <img src="public/images/footer_logo_payment_3.png" alt="payment3">
                        </div>
                    </div>
                    <div class="footer__top-item">
                        <h3>ĐỐI TÁC VẬN CHUYỂN</h3>
                        <div class="footer__payment-image-container-two">
                            <img src="public/images/footer_logo_shipment_2.png" alt="shipment2">
                            <img src="public/images/footer_logo_shipment_3.png" alt="shipment3">
                        </div>
                    </div>
                    <div class="footer__top-item">
                        <h3>ĐỐI TÁC BÁN HÀNG</h3>
                        <div class="footer__payment-image-container-three">
                            <img src="public/images/footer_logo_seller_1.png" alt="seller1">
                            <img src="public/images/footer_logo_seller_2.png" alt="seller2">
                            <img src="public/images/footer_logo_seller_3.png" alt="seller3">
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer__bottom">
                <div class="footer__bottom-container">
                    <div>
                        <img src="public/images/footer_logobct.png" alt="logobct">
                    </div>
                    <div>
                        <h3>CÔNG TY CỔ PHẦN THƯƠNG MẠI DỊCH VỤ SPOCE GROUP</h3>
                        <p>Địa chỉ: 273 An Dương Vương, P2, Q5, TP.HCM MST:0303615027 do Sở Kế Hoạch Và Đầu Tư Tp.HCM
                            cấp ngày 10/03/2011 Tel: 0388.853.835 Email: spoce_bookstore@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <!-- Hiển thị thông tin chi tiết sách -->
    <div class="show-detail-product"></div>

    <!-- Hiển thị bảng nhập thông tin đăng nhập hoặc đăng xuất -->
    <div class="auth"></div>

    <!-- Hiển thị hỏi Yes/No -->
    <div class="confirmation-dialog"></div>


    <!-- Hiển thị thông tin thêm địa chỉ mới -->

    <!-- Spinner chờ trong khi lấy dữ liệu từ Server -->
    <div class="loading-overlay" id="loading-overlay">
        <div class="spinner"></div>
    </div>


</body>
<script src="public/js/spinner.js"></script>
<script>
    function toggleFilter() {
        const filter = document.querySelector('.book-filter');
        const overlay = document.querySelector('.overlay-filter');
        filter.classList.toggle('active');
        overlay.classList.toggle('active');
    }
    // search sách mobile
    function toggleSearch() {
        const wrapper = document.getElementById('searchWrapper');
        wrapper.classList.toggle('active');
        document.addEventListener('click', function (e) {
            const wrapper = document.getElementById('searchWrapper');
            const icon = document.querySelector('.header__search-icon');

            if (!wrapper.contains(e.target) && !icon.contains(e.target)) {
                wrapper.classList.remove('active');
            }
        });
    }
    // function hiển thị sách theo breakpoint
    function updateSelectOptionsForBreakpoint() {
        console.log("Current width:", window.innerWidth);
        const select = document.getElementById('page-show-by');

        if (window.innerWidth <= 768) {
            // Mobile
            select.innerHTML = `
            <option value="12" selected>Mặc định</option>
            <option value="16">16 sản phẩm</option>
            <option value="20">20 sản phẩm</option>
            <option value="24">24 sản phẩm</option>
        `;
        } else if (window.innerWidth <= 1024) {
            // Tablet
            select.innerHTML = `
            <option value="12" selected>Mặc định</option>
            <option value="15">15 sản phẩm</option>
            <option value="18">18 sản phẩm</option>
            <option value="24">24 sản phẩm</option>
        `;
        } else {
            // Desktop
            select.innerHTML = `
            <option value="10" selected>Mặc định</option>
            <option value="15">15 sản phẩm</option>
            <option value="20">20 sản phẩm</option>
            <option value="25">25 sản phẩm</option>
        `;
        }
    }


    window.addEventListener('resize', updateSelectOptionsForBreakpoint);
    window.addEventListener('DOMContentLoaded', updateSelectOptionsForBreakpoint);
</script>

</html>