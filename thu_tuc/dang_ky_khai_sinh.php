<?php
// =========================================================================
// KHỐI PHP: GIẢ LẬP XỬ LÝ BACKEND (Sẽ thay bằng truy vấn Database sau này)
// =========================================================================

// 1. Giả lập kết nối Database và lấy "Ví số" của user đang đăng nhập (Tính năng 2)
// Trong thực tế: SELECT extracted_data FROM digital_wallets WHERE user_id = 1
$mockDigitalWallet = [
    "requester_name" => "NGUYỄN VĂN A",
    "requester_dob" => "01/01/1990",
    "requester_address" => "Số 123, Phố Kim Mã, Phường Kim Mã, Quận Ba Đình, TP. Hà Nội",
    "requester_id_type" => "Căn cước công dân",
    "requester_id_number" => "001090123456",
    "requester_ethnicity" => "Kinh",
    "requester_nationality" => "Việt Nam"
];

// Chuyển mảng PHP thành chuỗi JSON để truyền xuống JavaScript
$walletJsonData = json_encode($mockDigitalWallet);

// 2. Giữ chỗ cho xử lý Submit Form (Tính năng 3: Tiền kiểm & Lưu DB)
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nhận dữ liệu
    $req_name = $_POST['req_name'] ?? '';
    $req_dob = $_POST['req_dob'] ?? '';
    
    // Tại đây sau này sẽ code: 
    // - Upload file minh chứng và kiểm tra ảnh mờ (AI)
    // - Insert vào bảng applications
    $message = "Đã nhận hồ sơ của: " . htmlspecialchars($req_name) . " (Tính năng lưu DB sẽ code sau).";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cổng dịch vụ công quốc gia</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Tùy chỉnh thanh cuộn cho gọn gàng */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans h-screen flex flex-col overflow-hidden">

    <!-- Header (Giữ nguyên) -->
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

    <!-- Main Layout -->
    <div class="flex flex-1 overflow-hidden">
        
        <!-- Left Sidebar (Giữ nguyên) -->
        <aside class="w-64 bg-white border-r flex flex-col justify-between shrink-0 overflow-y-auto">
            <nav class="p-4 space-y-1">
                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg text-sm">
                    <i class="fa-solid fa-house w-5 text-center"></i> Trang chủ
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-2.5 bg-blue-50 text-blue-600 font-medium rounded-lg text-sm">
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

        <!-- Main Content (Center) -->
        <main class="flex-[6] min-w-0 bg-white border-r p-4 lg:p-6 overflow-y-auto">
            <!-- Breadcrumb -->
            <div class="text-xs text-gray-500 mb-4 flex items-center gap-2">
                <span>Trang chủ</span> <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span>Thủ tục hành chính</span> <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-gray-800">Đăng ký khai sinh cho trẻ em</span>
            </div>

            <!-- Title -->
            <h1 class="text-2xl font-bold mb-1">Đăng ký khai sinh cho trẻ em</h1>
            <p class="text-sm text-gray-500 mb-4">UBND Quận Ba Đình, Hà Nội</p>

            <!-- Báo cáo kết quả Submit (Hiển thị khi POST) -->
            <?php if(!empty($message)): ?>
                <div class="bg-blue-50 border border-blue-200 text-blue-800 p-3 rounded-lg mb-4 text-sm font-medium">
                    <i class="fa-solid fa-circle-info mr-2"></i> <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Alert -->
            <div class="bg-green-50 border border-green-100 text-green-800 p-3 rounded-lg flex items-center justify-between mb-8 text-sm">
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-circle-check"></i>
                    <span>Hệ thống đã tự động điền 85% thông tin từ Kho giấy tờ số của bạn.</span>
                </div>
                <a href="#" class="text-blue-600 hover:underline whitespace-nowrap ml-2">Xem chi tiết</a>
            </div>

            <!-- BẮT ĐẦU FORM: Đã thêm thẻ <form>, thuộc tính name và id -->
            <form action="" method="POST" enctype="multipart/form-data">
                
                <!-- Vùng nền giả lập mặt bàn đặt tờ giấy A4 -->
                <div class="bg-gray-200 p-2 md:p-4 rounded-xl border border-gray-300 mb-8 shadow-inner overflow-x-auto">
                    <!-- Vùng chứa form giả lập tờ giấy A4 -->
                    <div class="w-full max-w-[850px] min-w-[500px] mx-auto bg-white p-6 md:p-10 shadow-xl text-black text-[14px] md:text-[15px] leading-relaxed" style="font-family: 'Times New Roman', Times, serif;">
                        
                        <!-- Tiêu đề Quốc hiệu -->
                        <div class="text-center mb-8">
                            <h2 class="font-bold text-[16px] md:text-lg uppercase tracking-wide">Cộng hòa xã hội chủ nghĩa Việt Nam</h2>
                            <h3 class="font-bold text-[15px] md:text-base underline underline-offset-4 mb-8">Độc lập - Tự do - Hạnh phúc</h3>
                            <h1 class="font-bold text-xl md:text-2xl uppercase mt-8 mb-6">Tờ khai đăng ký khai sinh</h1>
                        </div>

                        <!-- Các trường thông tin -->
                        <div class="space-y-4">
                            
                            <!-- Kính gửi -->
                            <div class="flex items-end gap-2 ml-8 md:ml-16">
                                <span class="whitespace-nowrap">Kính gửi: <sup>(1)</sup></span>
                                <input type="text" name="kinh_gui" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>

                            <!-- Người yêu cầu -->
                            <div class="flex items-end gap-2 mt-6">
                                <span class="whitespace-nowrap font-bold">Họ, chữ đệm, tên người yêu cầu:</span>
                                <input type="text" id="req_name" name="req_name" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold">
                            </div>
                            
                            <div class="flex items-end gap-2">
                                <span class="whitespace-nowrap">Ngày, tháng, năm sinh:</span>
                                <input type="text" id="req_dob" name="req_dob" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>
                            
                            <div class="flex flex-col gap-2">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap">Nơi cư trú: <sup>(2)</sup></span>
                                    <!-- Để đơn giản, gộp địa chỉ vào input dài -->
                                    <input type="text" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                </div>
                                <input type="text" id="req_address" name="req_address" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>
                            
                            <div class="flex flex-col gap-2 mt-1">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap">Giấy tờ tùy thân: <sup>(3)</sup></span>
                                    <input type="text" id="req_id_type" name="req_id_type" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="Loại giấy tờ...">
                                </div>
                                <input type="text" id="req_id_number" name="req_id_number" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="Số chứng thực...">
                            </div>
                            
                            <div class="flex items-end gap-2 mt-1">
                                <span class="whitespace-nowrap">Quan hệ với người được khai sinh:</span>
                                <input type="text" name="relationship" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>

                            <!-- Đề nghị cơ quan -->
                            <div class="mt-6 mb-2 font-bold">
                                Đề nghị cơ quan đăng ký khai sinh cho người dưới đây:
                            </div>

                            <!-- Trẻ em được khai sinh -->
                            <div class="flex items-end gap-2">
                                <span class="whitespace-nowrap font-bold">Họ, chữ đệm, tên:</span>
                                <input type="text" name="child_name" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold">
                            </div>
                            
                            <div class="flex flex-wrap md:flex-nowrap items-end gap-2">
                                <span class="whitespace-nowrap">Ngày, tháng, năm sinh:</span>
                                <input type="text" name="child_dob" class="flex-1 min-w-[80px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                <span class="whitespace-nowrap">ghi bằng chữ:</span>
                                <input type="text" name="child_dob_words" class="flex-[2] min-w-[100px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>
                            
                            <div class="flex flex-wrap md:flex-nowrap items-end gap-2">
                                <span class="whitespace-nowrap">Giới tính:</span>
                                <input type="text" name="child_gender" class="w-16 border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                <span class="whitespace-nowrap">Dân tộc:</span>
                                <input type="text" name="child_ethnicity" class="flex-1 min-w-[60px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                <span class="whitespace-nowrap">Quốc tịch:</span>
                                <input type="text" name="child_nationality" class="flex-1 min-w-[60px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>
                            
                            <div class="flex flex-col gap-2 mt-1">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap">Nơi sinh: <sup>(4)</sup></span>
                                    <input type="text" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                </div>
                                <input type="text" name="child_birth_place" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>
                            
                            <div class="flex flex-col gap-2 mt-1">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap">Quê quán:</span>
                                    <input type="text" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                </div>
                                <input type="text" name="child_hometown" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>

                            <!-- Thông tin mẹ -->
                            <div class="flex items-end gap-2 mt-6">
                                <span class="whitespace-nowrap font-bold">Họ, chữ đệm, tên người mẹ:</span>
                                <input type="text" name="mother_name" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold">
                            </div>
                            
                            <div class="flex flex-wrap md:flex-nowrap items-end gap-2">
                                <span class="whitespace-nowrap">Năm sinh: <sup>(5)</sup></span>
                                <input type="text" name="mother_yob" class="flex-1 min-w-[60px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                <span class="whitespace-nowrap">Dân tộc: <sup>(2)</sup></span>
                                <input type="text" name="mother_ethnicity" class="flex-1 min-w-[60px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                <span class="whitespace-nowrap">Quốc tịch: <sup>(2)</sup></span>
                                <input type="text" name="mother_nationality" class="flex-1 min-w-[60px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>
                            
                            <div class="flex flex-col gap-2 mt-1">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap">Nơi cư trú: <sup>(2)</sup></span>
                                    <input type="text" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                </div>
                                <input type="text" name="mother_address" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons (Đã sửa nút Tiếp tục thành type="submit") -->
                <div class="flex items-center justify-between border-t pt-6 mb-4 font-sans">
                    <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm font-medium">Quay lại</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium flex items-center gap-2">
                        Tiếp tục <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </form>
            <!-- KẾT THÚC FORM -->
            
            <div class="text-xs text-gray-500 flex items-center gap-1 font-sans">
                <i class="fa-solid fa-lock text-[10px]"></i> Thông tin của bạn được bảo mật và chỉ sử dụng cho mục đích giải quyết thủ tục hành chính.
            </div>

        </main>

        <!-- Right Sidebar (AI Assistant) - Giữ nguyên không can thiệp -->
        <aside class="flex-[3.3] min-w-[320px] bg-white flex flex-col shrink-0">
            <!-- (Giữ nguyên toàn bộ code Right Sidebar của bạn ở đây...) -->
            <!-- AI Header -->
            <div class="flex items-center justify-between bg-gradient-to-r from-purple-50 to-white p-5 m-4 rounded-2xl border border-purple-100 shadow-sm relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-purple-300/40 rounded-full blur-2xl"></div>
                <div class="flex items-center gap-3.5 relative z-10">
                    <div class="w-11 h-11 bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl flex items-center justify-center text-white shrink-0 shadow-[0_4px_12px_rgba(147,51,234,0.3)]">
                        <i class="fa-solid fa-shield-halved text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-purple-900 font-extrabold flex items-center gap-2 text-[15px]">
                            TRỢ LÝ PHÁP LÝ AI 
                            <span class="bg-purple-200/80 text-purple-700 text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wider border border-purple-300/50">Beta</span>
                        </h2>
                        <p class="text-xs text-purple-600/90 mt-0.5 font-medium">Hỗ trợ giải đáp và sinh checklist hồ sơ</p>
                    </div>
                </div>
                <!-- Ảnh Chatbot - đảm bảo đường dẫn ảnh đúng trên máy bạn -->
                <img src="chatbot1.png" alt="Chatbot" class="w-16 h-16 object-contain relative z-10 drop-shadow-lg transform hover:scale-105 transition-transform duration-300">
            </div>

            <!-- Tabs -->
            <div class="flex border-b px-4 text-sm font-medium">
                <div class="px-4 py-3 text-gray-500 cursor-pointer hover:text-gray-800">Hỏi đáp pháp lý</div>
                <div class="px-4 py-3 text-purple-700 border-b-2 border-purple-600 cursor-pointer">Sinh checklist hồ sơ</div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-4 bg-gray-50/50">
                <p class="text-sm text-gray-600 mb-4">Dựa trên thủ tục "Đăng ký khai sinh cho trẻ em" và thông tin của bạn, danh sách hồ sơ cần chuẩn bị như sau:</p>
                
                <!-- Table -->
                <div class="bg-white border rounded-lg overflow-hidden mb-6">
                    <div class="px-4 py-3 bg-gray-50 border-b font-semibold text-sm">Danh sách hồ sơ cần chuẩn bị</div>
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 bg-white border-b">
                            <tr>
                                <th class="px-4 py-2 font-medium">STT</th>
                                <th class="px-4 py-2 font-medium">Tên giấy tờ</th>
                                <th class="px-4 py-2 font-medium">Bắt buộc</th>
                                <th class="px-4 py-2 font-medium">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y text-gray-700 text-xs">
                            <tr>
                                <td class="px-4 py-3 text-center">1</td>
                                <td class="px-4 py-3">Tờ khai đăng ký khai sinh (theo mẫu)</td>
                                <td class="px-4 py-3 text-green-600"><i class="fa-solid fa-check mr-1"></i>Bắt buộc</td>
                                <td class="px-4 py-3 text-red-500"><i class="fa-regular fa-circle-xmark mr-1"></i>Chưa có</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-center">2</td>
                                <td class="px-4 py-3">Giấy chứng sinh (bản chính)</td>
                                <td class="px-4 py-3 text-green-600"><i class="fa-solid fa-check mr-1"></i>Bắt buộc</td>
                                <td class="px-4 py-3 text-green-600"><i class="fa-regular fa-circle-check mr-1"></i>Đã có</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-center">3</td>
                                <td class="px-4 py-3">CCCD/CMND của người đi khai sinh<br><span class="text-gray-400">(bản sao)</span></td>
                                <td class="px-4 py-3 text-green-600"><i class="fa-solid fa-check mr-1"></i>Bắt buộc</td>
                                <td class="px-4 py-3 text-green-600"><i class="fa-regular fa-circle-check mr-1"></i>Đã có</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-center">4</td>
                                <td class="px-4 py-3">Sổ hộ khẩu/Sổ tạm trú (bản sao)</td>
                                <td class="px-4 py-3 text-gray-400">—</td>
                                <td class="px-4 py-3 text-red-500"><i class="fa-regular fa-circle-xmark mr-1"></i>Chưa có</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-center">5</td>
                                <td class="px-4 py-3">Giấy ủy quyền (nếu có)</td>
                                <td class="px-4 py-3 text-gray-400">—</td>
                                <td class="px-4 py-3 text-gray-400">Không áp dụng</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Legal Basis -->
                <div class="mb-6">
                    <h3 class="font-semibold text-sm mb-2">Cơ sở pháp lý</h3>
                    <ul class="list-disc pl-5 text-xs text-gray-600 space-y-1">
                        <li>Luật Hộ tịch 2014, Điều 15</li>
                        <li>Nghị định 123/2015/NĐ-CP, Điều 28</li>
                        <li>Thông tư 04/2020/TT-BTP, Điều 7</li>
                    </ul>
                </div>

                <!-- FAQ Tags -->
                <div class="mb-4">
                    <h3 class="font-semibold text-sm mb-3">Câu hỏi thường gặp</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Thời hạn đăng ký khai sinh là bao lâu?</span>
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Không có giấy chứng sinh thì làm sao?</span>
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Có thể ủy quyền cho người khác đi khai sinh không?</span>
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Lệ phí đăng ký khai sinh là bao nhiêu?</span>
                    </div>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="p-4 border-t bg-white shrink-0">
                <div class="relative">
                    <input type="text" placeholder="Bạn cần giải đáp thắc mắc gì?" class="w-full border border-gray-300 rounded-full py-2.5 pl-4 pr-12 text-sm focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                    <button class="absolute right-1.5 top-1.5 w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center hover:bg-purple-700">
                        <i class="fa-regular fa-paper-plane text-xs"></i>
                    </button>
                </div>
            </div>
        </aside>

    </div>
    
    <!-- ========================================================================= -->
    <!-- SCRIPT AUTO-FILL: Nhận dữ liệu JSON từ PHP và đổ vào form               -->
    <!-- ========================================================================= -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Nhận dữ liệu JSON do PHP render ra
        const walletData = <?php echo $walletJsonData; ?>;
        
        if (walletData) {
            // Map các key trong JSON với ID của thẻ input trong HTML
            const fillMap = {
                'req_name': walletData.requester_name,
                'req_dob': walletData.requester_dob,
                'req_address': walletData.requester_address,
                'req_id_type': walletData.requester_id_type,
                'req_id_number': walletData.requester_id_number
            };

            for (const [inputId, value] of Object.entries(fillMap)) {
                const inputElement = document.getElementById(inputId);
                if (inputElement && value) {
                    inputElement.value = value;
                    // Bôi màu nền xanh nhạt để User nhận biết AI đã tự động điền
                    inputElement.style.backgroundColor = '#f0fdf4'; 
                    inputElement.style.fontWeight = 'bold';
                    inputElement.style.color = '#166534';
                }
            }
            console.log("Hệ thống đã Auto-fill thành công!");
        }
    });
    </script>
</body>
</html>