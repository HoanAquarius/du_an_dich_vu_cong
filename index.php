<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Cổng dịch vụ công quốc gia</title>
    <link rel="stylesheet" href="chatbot.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Tùy chỉnh thanh cuộn */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Background Hero Section giả lập như hình ảnh */
        .hero-bg {
            background: linear-gradient(135deg, #f0f7ff 0%, #e0edff 100%);
            position: relative;
        }
        .hero-bg::after {
            content: '';
            position: absolute;
            right: 0;
            bottom: 0;
            width: 50%;
            height: 100%;
            background-image: url('https://www.transparenttextures.com/patterns/cubes.png'); /* Pattern giả lập mờ */
            opacity: 0.1;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-gray-800 h-screen flex flex-col overflow-hidden">

        <header class="bg-[#004a99] h-16 flex items-center justify-between px-6 text-white shrink-0">
    <!-- Logo và Tiêu đề -->
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-red-600 text-yellow-300 rounded-full flex items-center justify-center font-bold shadow">
                <i class="fa-solid fa-star text-xs"></i>
            </div>
        <div class="flex flex-col">
            <span class="font-bold text-lg leading-tight">Cổng Dịch vụ công Quốc gia</span>
            <span class="text-[10px] opacity-90">Kết nối, cung cấp thông tin và dịch vụ công mọi lúc, mọi nơi</span>
        </div>
    </div>

    <!-- Phía bên phải -->
    <div class="flex items-center gap-6">
        <div class="relative cursor-pointer">
            <i class="fa-regular fa-bell text-white text-xl"></i>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-4 h-4 flex items-center justify-center rounded-full font-bold">3</span>
        </div>
        <div class="flex items-center gap-2 cursor-pointer">
            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-user text-white"></i>
            </div>
            <span class="font-medium text-sm">Nguyễn Văn A</span>
            <i class="fa-solid fa-chevron-down text-[10px] opacity-70"></i>
        </div>
    </div>
</header>

    <div class="flex flex-1 overflow-hidden">
        
        <aside class="w-64 bg-white border-r flex flex-col justify-between shrink-0 overflow-y-auto hidden md:flex">
            <nav class="p-4 space-y-1">
                <a href="#" class="flex items-center gap-3 px-4 py-2.5 bg-blue-50 text-blue-600 font-medium rounded-lg text-sm">
                    <i class="fa-solid fa-house w-5 text-center"></i> Trang chủ
                </a>
                <a href="./thu_tuc/thu_tuc.php" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg text-sm">
                    <i class="fa-solid fa-file-lines w-5 text-center"></i> Thủ tục hành chính
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg text-sm">
                    <i class="fa-solid fa-folder w-5 text-center"></i> Hồ sơ của tôi
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg text-sm">
                    <i class="fa-solid fa-box-archive w-5 text-center"></i> Kho giấy tờ số
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg text-sm">
                    <i class="fa-solid fa-credit-card w-5 text-center"></i> Thanh toán trực tuyến
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg text-sm">
                    <i class="fa-solid fa-circle-info w-5 text-center"></i> Hướng dẫn sử dụng
                </a>
                <div class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg text-sm cursor-pointer">
                    <div class="flex items-center gap-3">
                        <i class="fa-regular fa-bell w-5 text-center"></i> Thông báo
                    </div>
                    <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold">5</span>
                </div>
                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg text-sm">
                    <i class="fa-regular fa-circle-question w-5 text-center"></i> Hỗ trợ
                </a>
            </nav>
            <div class="p-4 border-t">
                <div class="flex items-center gap-3 p-3 border rounded-lg mb-2">
                    <div class="w-10 h-10 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center text-lg">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <div class="font-bold text-sm">Nguyễn Văn A</div>
                        <div class="text-xs text-gray-500">Công dân</div>
                    </div>
                </div>
                <button class="w-full flex items-center gap-2 justify-center py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất
                </button>
            </div>
        </aside>

        <div class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col lg:flex-row gap-6">
                
                <main class="flex-[7] min-w-0 space-y-6">
                    
                    <div class="hero-bg rounded-2xl p-6 sm:p-8 shadow-sm">
                        <div class="relative z-10 w-full max-w-xl">
                            <p class="text-sm font-medium text-gray-600 mb-1 flex items-center gap-2">
                                Xin chào, <span class="font-bold text-gray-800">Nguyễn Văn A</span> <span class="text-lg">👋</span>
                            </p>
                            <h1 class="text-2xl sm:text-3xl font-bold text-blue-900 leading-snug mb-6">
                                Chúng tôi luôn sẵn sàng<br>phục vụ bạn
                            </h1>
                            
                            <div class="flex bg-white rounded-xl shadow-lg shadow-blue-900/5 p-1 mb-4 border border-white">
                                <input type="text" placeholder="Bạn cần tìm thủ tục gì hôm nay?" class="flex-1 bg-transparent px-4 py-2 sm:py-3 text-sm focus:outline-none">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-lg font-medium text-sm transition-colors flex items-center gap-2">
                                    <i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm
                                </button>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-2 text-xs">
                                <span class="text-gray-500 font-medium mr-1">Tìm kiếm phổ biến:</span>
                                <span class="bg-white/80 hover:bg-white text-blue-700 px-3 py-1.5 rounded-full cursor-pointer shadow-sm transition border border-blue-100">Đăng ký khai sinh</span>
                                <span class="bg-white/80 hover:bg-white text-blue-700 px-3 py-1.5 rounded-full cursor-pointer shadow-sm transition border border-blue-100">Đăng ký kết hôn</span>
                                <span class="bg-white/80 hover:bg-white text-blue-700 px-3 py-1.5 rounded-full cursor-pointer shadow-sm transition border border-blue-100">Cấp đổi CCCD</span>
                                <span class="bg-white/80 hover:bg-white text-blue-700 px-3 py-1.5 rounded-full cursor-pointer shadow-sm transition border border-blue-100">Cấp GPLX</span>
                            </div>
                        </div>
                        <div class="absolute right-0 bottom-0 top-0 w-1/3 bg-no-repeat bg-right-bottom bg-contain opacity-80 pointer-events-none hidden md:block" style="background-image: url('data:image/svg+xml;utf8,<svg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22><path fill=%22%233b82f6%22 d=%22M41.7,-64.5C54.1,-55.9,64,-42.6,71.2,-28C78.4,-13.4,83,2.5,79.5,17C76.1,31.5,64.7,44.7,51.8,55.1C38.9,65.5,24.6,73.1,8.9,76.5C-6.8,79.9,-23.9,79.1,-38.3,71.7C-52.7,64.3,-64.4,50.3,-71.4,34.7C-78.4,19.1,-80.7,1.8,-76.3,-14.2C-71.9,-30.2,-60.7,-44.8,-47.5,-53.6C-34.3,-62.3,-19,-65.2,-3.3,-60.8C12.4,-56.4,29.3,-73.1,41.7,-64.5Z%22 transform=%22translate(100 100) scale(1.2)%22 opacity=%220.1%22/></svg>');"></div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-[17px] font-bold text-gray-800">Dịch vụ công trực tuyến</h2>
                            <a href="#" class="text-sm font-medium text-blue-600 hover:underline flex items-center gap-1">Xem tất cả <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                            <div class="bg-gray-50 rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-blue-50 border border-transparent hover:border-blue-100 transition group">
                                <div class="w-12 h-12 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-id-card"></i>
                                </div>
                                <h3 class="font-semibold text-gray-800 text-sm mb-1 leading-tight">Cấp đổi<br>CCCD</h3>
                                <p class="text-[10px] text-gray-400">432.104 Hồ sơ đã tiếp nhận</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-pink-50 border border-transparent hover:border-pink-100 transition group">
                                <div class="w-12 h-12 bg-pink-100 text-pink-500 rounded-full flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-ring"></i>
                                </div>
                                <h3 class="font-semibold text-gray-800 text-sm mb-1 leading-tight">Đăng ký<br>cư trú</h3>
                                <p class="text-[10px] text-gray-400">128.657 Hồ sơ đã tiếp nhận</p>
                            </div>
                            <a href="./thu_tuc/dang_ky_khai_sinh.php">
                            <div class="bg-green-50/50 rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-green-50 border border-transparent hover:border-green-200 transition group relative overflow-hidden h-full">
                                <div class="absolute top-0 right-0 bg-green-500 text-white text-[8px] font-bold px-2 py-0.5 rounded-bl-lg">HOT</div>
                                <div class="w-12 h-12 bg-green-100 text-green-500 rounded-full flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-baby"></i>
                                </div>
                                <h3 class="font-semibold text-gray-800 text-sm mb-1 leading-tight">Đăng ký<br>khai sinh</h3>
                                <p class="text-[10px] text-gray-400">312.029 Hồ sơ đã tiếp nhận</p>
                            </div></a>
                            
                            <div class="bg-gray-50 rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-orange-50 border border-transparent hover:border-orange-100 transition group">
                                <div class="w-12 h-12 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-car"></i>
                                </div>
                                <h3 class="font-semibold text-gray-800 text-sm mb-1 leading-tight">Cấp Hộ chiếu <br>phổ thông</h3>
                                <p class="text-[10px] text-gray-400">267.982 Hồ sơ đã tiếp nhận</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-purple-50 border border-transparent hover:border-purple-100 transition group hidden sm:flex">
                                <div class="w-12 h-12 bg-purple-100 text-purple-500 rounded-full flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-house-chimney"></i>
                                </div>
                                <h3 class="font-semibold text-gray-800 text-sm mb-1 leading-tight">Đăng ký<br>kết hôn</h3>
                                <p class="text-[10px] text-gray-400">89.341 Hồ sơ đã tiếp nhận</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-8">Quy trình 5 bước thực hiện dịch vụ công</h3>
                
                <div class="flex items-start w-full relative">
                    <div class="relative flex flex-col items-center flex-1">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center z-10 shadow-md">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        </div>
                        <div class="absolute top-6 left-[50%] w-full border-t-2 border-dashed border-blue-200 z-0"></div>
                        <span class="text-xs font-semibold text-gray-700 mt-3 text-center">Chọn thủ tục</span>
                    </div>
                    <div class="relative flex flex-col items-center flex-1">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center z-10 shadow-md">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <div class="absolute top-6 left-[50%] w-full border-t-2 border-dashed border-blue-200 z-0"></div>
                        <span class="text-xs font-semibold text-gray-700 mt-3 text-center">Nhập thông tin</span>
                    </div>
                    <div class="relative flex flex-col items-center flex-1">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center z-10 shadow-md">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        </div>
                        <div class="absolute top-6 left-[50%] w-full border-t-2 border-dashed border-blue-200 z-0"></div>
                        <span class="text-xs font-semibold text-gray-700 mt-3 text-center">Đính kèm hồ sơ</span>
                    </div>
                    <div class="relative flex flex-col items-center flex-1">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center z-10 shadow-md">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <div class="absolute top-6 left-[50%] w-full border-t-2 border-dashed border-blue-200 z-0"></div>
                        <span class="text-xs font-semibold text-gray-700 mt-3 text-center">Thanh toán</span>
                    </div>
                    <div class="relative flex flex-col items-center flex-1">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center z-10 shadow-md">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 mt-3 text-center">Nhận kết quả</span>
                    </div>
                </div>
            </div>

                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 flex items-center justify-between border border-blue-200">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white text-xl">
                                <i class="fa-solid fa-shield-cat"></i> </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm">Đăng nhập tài khoản định danh điện tử</h3>
                                <p class="text-xs text-gray-600">để sử dụng đầy đủ tiện ích</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button class="px-5 py-2 bg-white border border-blue-600 text-blue-700 font-medium text-sm rounded-lg hover:bg-blue-50">Đăng nhập</button>
                            <button class="px-5 py-2 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700">Đăng ký</button>
                        </div>
                    </div>

                </main>

                <aside class="flex-[3] min-w-[300px] space-y-6">
                    
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-purple-100 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-100 to-white rounded-full blur-2xl -mr-10 -mt-10"></div>
                        
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h2 class="text-[16px] font-bold text-purple-900 flex items-center gap-2 mb-1">
                                        Trợ lý pháp lý AI
                                        <span class="bg-blue-600 text-white text-[9px] px-1.5 py-0.5 rounded font-bold">Beta</span>
                                    </h2>
                                    <p class="text-xs text-gray-600 font-medium">Tôi có thể hỗ trợ gì cho bạn<br>về thủ tục hành chính?</p>
                                </div>
                                <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-600 text-2xl shadow-sm transform group-hover:rotate-6 transition-transform">
                                    <img src="./thu_tuc/chatbot1.png" alt="">
                                </div>
                            </div>
                            
                            <div class="space-y-2 mb-4">
                                <button class="w-full text-left bg-white border border-gray-200 hover:border-purple-300 hover:text-purple-700 px-3 py-2.5 rounded-xl text-xs font-medium text-gray-600 flex items-center gap-2 transition">
                                    <i class="fa-regular fa-clipboard text-purple-500"></i> Thủ tục nào phù hợp với tôi?
                                </button>
                                <button class="w-full text-left bg-white border border-gray-200 hover:border-purple-300 hover:text-purple-700 px-3 py-2.5 rounded-xl text-xs font-medium text-gray-600 flex items-center gap-2 transition">
                                    <i class="fa-solid fa-magnifying-glass text-purple-500"></i> Hồ sơ cần chuẩn bị những gì?
                                </button>
                                <button class="w-full text-left bg-white border border-gray-200 hover:border-purple-300 hover:text-purple-700 px-3 py-2.5 rounded-xl text-xs font-medium text-gray-600 flex items-center gap-2 transition">
                                    <i class="fa-regular fa-clock text-purple-500"></i> Thời gian xử lý là bao lâu?
                                </button>
                            </div>
                            
                            <button
    id="openChatbot"
    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2.5 rounded-xl text-sm flex items-center justify-center gap-2 transition shadow-md shadow-purple-200">

    <i class="fa-regular fa-comments"></i>

    Chat với trợ lý AI

</button>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-[15px] font-bold text-gray-800">Thông báo</h2>
                            <a href="#" class="text-[11px] font-medium text-blue-600 hover:underline flex items-center gap-1">Xem tất cả <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div class="space-y-4">
                            <div class="flex gap-3 items-start relative">
                                <div class="absolute -right-1 top-0 w-2 h-2 bg-red-500 rounded-full"></div>
                                <div class="w-8 h-8 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center shrink-0 text-sm">
                                    <i class="fa-solid fa-bullhorn"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-xs text-gray-800">Bảo trì hệ thống Cổng DVCQG</h4>
                                    <p class="text-[11px] text-gray-500 leading-snug mt-0.5">Hệ thống sẽ được bảo trì từ 22:00 ngày 25/05/2024</p>
                                    <p class="text-[10px] text-gray-400 mt-1">2 giờ trước</p>
                                </div>
                            </div>
                            <div class="flex gap-3 items-start">
                                <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center shrink-0 text-sm">
                                    <i class="fa-regular fa-comment-dots"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-xs text-gray-800">Cập nhật chính sách mới</h4>
                                    <p class="text-[11px] text-gray-500 leading-snug mt-0.5">Quy định mới về cấp đổi CCCD có hiệu lực từ 01/06/2024</p>
                                    <p class="text-[10px] text-gray-400 mt-1">1 ngày trước</p>
                                </div>
                            </div>
                            <div class="flex gap-3 items-start">
                                <div class="w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center shrink-0 text-sm">
                                    <i class="fa-solid fa-book"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-xs text-gray-800">Hướng dẫn sử dụng</h4>
                                    <p class="text-[11px] text-gray-500 leading-snug mt-0.5">Cập nhật tài liệu hướng dẫn sử dụng hệ thống</p>
                                    <p class="text-[10px] text-gray-400 mt-1">2 ngày trước</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-[15px] font-bold text-gray-800">Tiện ích nhanh</h2>
                            <a href="#" class="text-[11px] font-medium text-blue-600 hover:underline flex items-center gap-1">Xem tất cả <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="flex flex-col items-center justify-center text-center gap-2 cursor-pointer group">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                                <span class="text-[10px] font-medium text-gray-600">Tra cứu hồ sơ</span>
                            </div>
                            <div class="flex flex-col items-center justify-center text-center gap-2 cursor-pointer group">
                                <div class="w-10 h-10 bg-green-50 text-green-600 rounded-full flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition-colors">
                                    <i class="fa-regular fa-credit-card"></i>
                                </div>
                                <span class="text-[10px] font-medium text-gray-600 leading-tight">Thanh toán<br>trực tuyến</span>
                            </div>
                            <div class="flex flex-col items-center justify-center text-center gap-2 cursor-pointer group">
                                <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                </div>
                                <span class="text-[10px] font-medium text-gray-600 leading-tight">Lịch sử<br>giao dịch</span>
                            </div>
                            <div class="flex flex-col items-center justify-center text-center gap-2 cursor-pointer group mt-2">
                                <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-colors">
                                    <i class="fa-regular fa-star"></i>
                                </div>
                                <span class="text-[10px] font-medium text-gray-600">Đánh giá dịch vụ</span>
                            </div>
                            <div class="flex flex-col items-center justify-center text-center gap-2 cursor-pointer group mt-2">
                                <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center group-hover:bg-teal-600 group-hover:text-white transition-colors">
                                    <i class="fa-regular fa-circle-question"></i>
                                </div>
                                <span class="text-[10px] font-medium text-gray-600 leading-tight">Câu hỏi<br>thường gặp</span>
                            </div>
                            <div class="flex flex-col items-center justify-center text-center gap-2 cursor-pointer group mt-2">
                                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                    <i class="fa-solid fa-mobile-screen"></i>
                                </div>
                                <span class="text-[10px] font-medium text-gray-600 leading-tight">Tải ứng dụng<br>di động</span>
                            </div>
                        </div>
                    </div>

                </aside>
            </div>
        </div>
    </div>
    <script src="chatbot.js"></script>
</body>
</html>