<?php
// Gọi file kết nối Database từ thư mục config
require_once __DIR__ . '/../config/database.php';

// Giả lập lấy "Ví số" của user đang đăng nhập (Tương lai sẽ gọi từ fetch_profile.php)
$mockDigitalWallet = [
    "requester_name" => "NGUYỄN VĂN A",
    "requester_dob" => "01/01/1990",
    "requester_address" => "Số 1, Đường Hà Huy Tập, Phường Tân An, TP. Buôn Ma Thuột, Tỉnh Đắk Lắk",
    "requester_id_type" => "Căn cước công dân",
    "requester_id_number" => "001090123456",
    "requester_id_issued_by" => "Cục Cảnh sát QLHC về TTXH",
    "requester_id_issued_date" => "15/10/2021",
    "father_hometown" => "Xã Hoằng Kim, Huyện Hoằng Hóa, Tỉnh Thanh Hóa" // Dữ liệu giả lập quê bố dùng để tự điền cho con
];
$walletJsonData = json_encode($mockDigitalWallet);
 
$message = '';
$realtimeDataJson = 'null';

// Xử lý Submit Form và Lưu Database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $req_name = $_POST['req_name'] ?? '';
    
    // Tạo dữ liệu hồ sơ
    $mockMaso = "HSO-" . date('Y') . "-" . rand(10000, 99999);
    $thu_tuc = "Đăng ký khai sinh";
    $target_time_mysql = date('Y-m-d H:i:s', strtotime('+24 hours')); 

    try {
        // Sử dụng $pdo đã được khởi tạo từ file database.php
        $sql = "INSERT INTO ho_so (ma_ho_so, thu_tuc, cong_dan, target_time, trang_thai) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$mockMaso, $thu_tuc, htmlspecialchars($req_name), $target_time_mysql, 'cho_xu_ly']);

        $message = "Đã nộp thành công! Mã hồ sơ của bạn là: " . $mockMaso;

        // Chuẩn bị chuỗi JSON để truyền cho WebSockets gửi sang Admin
        $realtimeData = [
            "ma_ho_so" => $mockMaso,
            "thu_tuc" => $thu_tuc,
            "cong_dan" => htmlspecialchars($req_name),
            "target_time" => date('Y-m-d\TH:i:s', strtotime('+24 hours'))
        ];
        $realtimeDataJson = json_encode($realtimeData);

    } catch (PDOException $e) {
        $message = "Lỗi hệ thống khi lưu hồ sơ: " . $e->getMessage();
    }
}
?>
 
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cổng dịch vụ công quốc gia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Style đặc biệt để đánh dấu các trường được tự động điền */
        .bg-autofill {
            background-color: #f0fdf4 !important; /* Xanh lá nhạt */
            font-weight: bold !important;
            color: #166534 !important; /* Xanh lá đậm */
            border-bottom-color: #22c55e !important;
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-gray-800 h-screen flex flex-col overflow-hidden">
 
<header class="bg-[#004a99] h-16 flex items-center justify-between px-6 text-white shrink-0">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-red-600 text-yellow-300 rounded-full flex items-center justify-center font-bold shadow">
            <i class="fa-solid fa-star text-xs"></i>
        </div>
        <div class="flex flex-col">
            <span class="font-bold text-lg leading-tight">Cổng Dịch vụ công Quốc gia</span>
            <span class="text-[10px] opacity-90">Kết nối, cung cấp thông tin và dịch vụ công mọi lúc, mọi nơi</span>
        </div>
    </div>
 
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
                <a href="../index.php" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg text-sm">
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
 
        <main class="flex-[6] min-w-0 bg-white border-r p-4 lg:p-6 overflow-y-auto">
            <div class="text-xs text-gray-500 mb-4 flex items-center gap-2">
                <span>Trang chủ</span> <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span>Thủ tục hành chính</span> <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-gray-800">Đăng ký khai sinh cho trẻ em</span>
            </div>
 
            <h1 class="text-2xl font-bold mb-1">Đăng ký khai sinh cho trẻ em</h1>
            <p class="text-sm text-gray-500 mb-4">UBND Phường Tân An, TP. Buôn Ma Thuột</p>
 
            <?php if(!empty($message)): ?>
                <div class="bg-blue-50 border border-blue-200 text-blue-800 p-3 rounded-lg mb-4 text-sm font-medium">
                    <i class="fa-solid fa-circle-info mr-2"></i> <?php echo $message; ?>
                </div>
            <?php endif; ?>
 
            <div class="bg-green-50 border border-green-100 text-green-800 p-3 rounded-lg flex items-center justify-between mb-8 text-sm">
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-circle-check"></i>
                    <span>Hệ thống đã tự động điền thông tin từ Kho giấy tờ số của bạn. Các trường tô màu xanh là dữ liệu tự điền.</span>
                </div>
                <a href="#" class="text-blue-600 hover:underline whitespace-nowrap ml-2">Xem chi tiết</a>
            </div>
 
            <form action="" method="POST" enctype="multipart/form-data">
                 
                <div class="bg-gray-200 p-2 md:p-4 rounded-xl border border-gray-300 mb-8 shadow-inner overflow-x-auto">
                    <div class="w-full max-w-[850px] min-w-[500px] mx-auto bg-white p-6 md:p-10 shadow-xl text-black text-[14px] md:text-[15px] leading-relaxed" style="font-family: 'Times New Roman', Times, serif;">
                         
                        <div class="text-center mb-8">
                            <h2 class="font-bold text-[16px] md:text-lg uppercase tracking-wide">Cộng hòa xã hội chủ nghĩa Việt Nam</h2>
                            <h3 class="font-bold text-[15px] md:text-base underline underline-offset-4 mb-8">Độc lập - Tự do - Hạnh phúc</h3>
                            <h1 class="font-bold text-xl md:text-2xl uppercase mt-8 mb-6">Tờ khai đăng ký khai sinh</h1>
                        </div>
 
                        <div class="space-y-4">
                            <div class="flex items-end gap-2 ml-8 md:ml-16">
                                <span class="whitespace-nowrap">Kính gửi: <sup>(1)</sup></span>
                                <input type="text" id="kinh_gui" name="kinh_gui" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="VD: UBND Phường Tân An, TP. Buôn Ma Thuột">
                            </div>
 
                            <div class="flex items-end gap-2 mt-6">
                                <span class="whitespace-nowrap font-bold">Họ, chữ đệm, tên người yêu cầu:</span>
                                <input type="text" id="req_name" name="req_name" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold" placeholder="VD: NGUYỄN VĂN A">
                            </div>
                             
                            <div class="flex items-end gap-2">
                                <span class="whitespace-nowrap">Ngày, tháng, năm sinh:</span>
                                <input type="text" id="req_dob" name="req_dob" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="VD: 01/01/1990">
                            </div>
                             
                            <div class="flex flex-col gap-2">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap">Nơi cư trú: <sup>(2)</sup></span>
                                    <input type="text" id="req_address_line1" name="req_address_line1" maxlength="55" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="VD: Số 1, Đường Hà Huy Tập, Phường Tân An...">
                                </div>
                                <input type="text" id="req_address_line2" name="req_address_line2" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>
                             
                            <div class="flex flex-col gap-2 mt-1">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap">Giấy tờ tùy thân: <sup>(3)</sup></span>
                                    <input type="text" id="req_id_line1" name="req_id_line1" maxlength="50" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                </div>
                                <input type="text" id="req_id_line2" name="req_id_line2" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>
                             
                            <div class="flex items-end gap-2 mt-1">
                                <span class="whitespace-nowrap">Quan hệ với người được khai sinh:</span>
                                <input type="text" name="relationship" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="VD: Cha đẻ / Mẹ đẻ / Ông nội...">
                            </div>
 
                            <div class="mt-6 mb-2 font-bold">
                                Đề nghị cơ quan đăng ký khai sinh cho người dưới đây:
                            </div>
 
                            <div class="flex items-end gap-2">
                                <span class="whitespace-nowrap font-bold">Họ, chữ đệm, tên:</span>
                                <input type="text" name="child_name" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold" placeholder="VD: NGUYỄN VĂN B">
                            </div>
                             
                            <div class="flex flex-wrap md:flex-nowrap items-end gap-2">
                                <span class="whitespace-nowrap">Ngày, tháng, năm sinh:</span>
                                <input type="text" name="child_dob" class="flex-1 min-w-[80px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="VD: 15/08/2023">
                                <span class="whitespace-nowrap">ghi bằng chữ:</span>
                                <input type="text" name="child_dob_words" class="flex-[2] min-w-[100px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="VD: Mười lăm tháng tám năm hai không hai ba">
                            </div>
                             
                            <div class="flex flex-wrap md:flex-nowrap items-end gap-2">
                                <span class="whitespace-nowrap">Giới tính:</span>
                                <input type="text" name="child_gender" class="w-16 border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 text-center" placeholder="Nam/Nữ">
                                <span class="whitespace-nowrap">Dân tộc:</span>
                                <!-- Gắn sẵn class bg-autofill cho các trường gán mặc định -->
                                <input type="text" name="child_ethnicity" value="Kinh" class="bg-autofill flex-1 min-w-[60px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" title="Đã điền tự động">
                                <span class="whitespace-nowrap">Quốc tịch:</span>
                                <input type="text" name="child_nationality" value="Việt Nam" class="bg-autofill flex-1 min-w-[60px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" title="Đã điền tự động">
                            </div>
                             
                            <div class="flex flex-col gap-2 mt-1">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap">Nơi sinh: <sup>(4)</sup></span>
                                    <input type="text" id="child_birth_place_line1" name="child_birth_place_line1" maxlength="60" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="VD: Bệnh viện Đa khoa vùng Tây Nguyên, TP. Buôn Ma Thuột...">
                                </div>
                                <input type="text" id="child_birth_place_line2" name="child_birth_place_line2" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>
                             
                            <div class="flex flex-col gap-2 mt-1">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap">Quê quán:</span>
                                    <input type="text" id="child_hometown_line1" name="child_hometown_line1" maxlength="60" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="VD: Xã Hoằng Kim, Huyện Hoằng Hóa, Tỉnh Thanh Hóa...">
                                </div>
                                <input type="text" id="child_hometown_line2" name="child_hometown_line2" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>

                            <div class="flex items-end gap-2 mt-6">
                                <span class="whitespace-nowrap font-bold">Họ, chữ đệm, tên người mẹ:</span>
                                <input type="text" name="mother_name" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold" placeholder="VD: TRẦN THỊ C">
                            </div>
                             
                            <div class="flex flex-wrap md:flex-nowrap items-end gap-2">
                                <span class="whitespace-nowrap">Năm sinh: <sup>(5)</sup></span>
                                <input type="text" name="mother_yob" class="flex-1 min-w-[60px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="VD: 1992">
                                <span class="whitespace-nowrap">Dân tộc: <sup>(2)</sup></span>
                                <input type="text" name="mother_ethnicity" value="Kinh" class="bg-autofill flex-1 min-w-[60px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" title="Đã điền tự động">
                                <span class="whitespace-nowrap">Quốc tịch: <sup>(2)</sup></span>
                                <input type="text" name="mother_nationality" value="Việt Nam" class="bg-autofill flex-1 min-w-[60px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" title="Đã điền tự động">
                            </div>
                             
                            <div class="flex flex-col gap-2 mt-1">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap">Nơi cư trú: <sup>(2)</sup></span>
                                    <input type="text" id="mother_address_line1" maxlength="55" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="VD: Số 1, Đường Hà Huy Tập, Phường Tân An...">
                                </div>
                                <input type="text" id="mother_address_line2" name="mother_address" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>
                        </div>
                    </div>
                </div>
 
                <div class="flex items-center justify-between border-t pt-6 mb-4 font-sans">
                    <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm font-medium">Quay lại</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium flex items-center gap-2">
                        Tiếp tục <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </form>
            <div class="text-xs text-gray-500 flex items-center gap-1 font-sans">
                <i class="fa-solid fa-lock text-[10px]"></i> Thông tin của bạn được bảo mật và chỉ sử dụng cho mục đích giải quyết thủ tục hành chính.
            </div>
        </main>
 
        <aside class="flex-[3.3] min-w-[320px] bg-white flex flex-col shrink-0">
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
                <img src="chatbot1.png" alt="Chatbot" class="w-16 h-16 object-contain relative z-10 drop-shadow-lg transform hover:scale-105 transition-transform duration-300">
            </div>
 
            <div class="flex border-b px-4 text-sm font-medium">
                <div class="px-4 py-3 text-gray-500 cursor-pointer hover:text-gray-800">Hỏi đáp pháp lý</div>
                <div class="px-4 py-3 text-purple-700 border-b-2 border-purple-600 cursor-pointer">Checklist hồ sơ</div>
            </div>
 
            <div class="flex-1 overflow-y-auto p-4 bg-gray-50/50">
                <p class="text-sm text-gray-600 mb-4">Dựa trên thủ tục "Đăng ký khai sinh cho trẻ em" và thông tin của bạn, danh sách hồ sơ cần chuẩn bị như sau:</p>
                 
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
 
                <div class="mb-6">
                    <h3 class="font-semibold text-sm mb-3">Thông tin thủ tục</h3>
                    <ul class="text-xs text-gray-600 space-y-2 mb-3">
                        <li class="flex items-center"><span class="mr-2 text-sm">⏱</span>Thời gian giải quyết: 01 ngày làm việc</li>
                        <li class="flex items-center"><span class="mr-2 text-sm">💰</span>Lệ phí: Miễn phí</li>
                        <li class="flex items-center"><span class="mr-2 text-sm">📍</span>Cơ quan tiếp nhận: UBND cấp xã</li>
                        <li class="flex items-center"><span class="mr-2 text-sm">👤</span>Đối tượng thực hiện: Cha, mẹ hoặc người giám hộ</li>
                        <li class="flex items-center"><span class="mr-2 text-sm">📅</span>Thời hạn đăng ký: Trong 60 ngày kể từ ngày sinh</li>
                    </ul>
                    <button class="w-full py-2 bg-purple-50 text-purple-700 border border-purple-200 rounded-lg text-xs font-semibold hover:bg-purple-100 transition-colors flex items-center justify-center gap-1.5">
                        Chi tiết thông tin thủ tục <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
 
                <div class="mb-4">
                    <h3 class="font-semibold text-sm mb-3">Câu hỏi thường gặp</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Khai sinh khi cha mẹ chưa đăng ký kết hôn?</span>
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Đăng ký khai sinh muộn có bị phạt không?</span>
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Sinh con tại nhà, không có giấy chứng sinh?</span>
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Có cần đưa trẻ đi cùng khi đăng ký?</span>
                    </div>
                </div>
            </div>
 
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
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const walletData = <?php echo $walletJsonData; ?>;
        
        // HÀM 1: Cắt chuỗi dài thành 2 dòng thông minh để không bị tràn
        function splitTextForTwoLines(text, maxLength) {
            if (!text) return ["", ""];
            if (text.length <= maxLength) return [text, ""];
            
            // Tìm khoảng trắng gần nhất với giới hạn để ngắt dòng cho đẹp
            let splitIndex = text.lastIndexOf(" ", maxLength);
            if (splitIndex === -1) splitIndex = maxLength;
            
            return [text.substring(0, splitIndex).trim(), text.substring(splitIndex).trim()];
        }

        // HÀM 2: Tự động nhảy con trỏ xuống dòng 2 khi người dùng gõ tay quá dài
        function setupAutoJump(line1Id, line2Id, maxChars) {
            const line1 = document.getElementById(line1Id);
            const line2 = document.getElementById(line2Id);
            if(line1 && line2) {
                line1.addEventListener('input', function() {
                    if(this.value.length >= maxChars) {
                        line2.focus();
                    }
                });
            }
        }

        // Áp dụng auto-jump cho các trường 2 dòng
        setupAutoJump('req_address_line1', 'req_address_line2', 54);
        setupAutoJump('req_id_line1', 'req_id_line2', 49);
        setupAutoJump('child_birth_place_line1', 'child_birth_place_line2', 59);
        setupAutoJump('child_hometown_line1', 'child_hometown_line2', 59);
        setupAutoJump('mother_address_line1', 'mother_address_line2', 54);

        // THỰC HIỆN AUTO-FILL
        if (walletData) {
            let kinhGuiValue = "";
            if (walletData.requester_address) {
                const addressParts = walletData.requester_address.split(',');
                if (addressParts.length >= 3) {
                    const phuongXa = addressParts[addressParts.length - 3].trim();
                    const quanHuyen = addressParts[addressParts.length - 2].trim();
                    kinhGuiValue = "UBND " + phuongXa + ", " + quanHuyen;
                }
            }
            
            // Xử lý Giấy tờ tùy thân
            let idLine1 = "";
            let idLine2 = "";
            if(walletData.requester_id_type && walletData.requester_id_number) {
                idLine1 = `${walletData.requester_id_type} số ${walletData.requester_id_number}`;
                if(walletData.requester_id_issued_by) {
                    idLine2 = `do ${walletData.requester_id_issued_by}`;
                }
                if(walletData.requester_id_issued_date) {
                    idLine2 += ` cấp ngày ${walletData.requester_id_issued_date}`;
                }
            }

            // Xử lý chia 2 dòng cho Nơi cư trú (Người yêu cầu)
            const [reqAddr1, reqAddr2] = splitTextForTwoLines(walletData.requester_address, 55);
            
            // Xử lý chia 2 dòng Quê quán của Trẻ em (Tự động lấy theo quê Bố)
            const [childHt1, childHt2] = splitTextForTwoLines(walletData.father_hometown, 60);
 
            const fillMap = {
                'kinh_gui': kinhGuiValue,
                'req_name': walletData.requester_name,
                'req_dob': walletData.requester_dob,
                
                // Nơi cư trú người yêu cầu
                'req_address_line1': reqAddr1,
                'req_address_line2': reqAddr2,
                
                // Giấy tờ
                'req_id_line1': idLine1,
                'req_id_line2': idLine2,

                // Quê quán trẻ em (Lấy từ father_hometown)
                'child_hometown_line1': childHt1,
                'child_hometown_line2': childHt2
            };
 
            for (const [inputId, value] of Object.entries(fillMap)) {
                const inputElement = document.getElementById(inputId);
                if (inputElement && value) {
                    inputElement.value = value;
                    inputElement.classList.add('bg-autofill');
                    inputElement.title = 'Đã điền tự động';
                }
            }
        }

        const realtimeData = <?php echo $realtimeDataJson; ?>;
        if (realtimeData !== null) {
            const ws = new WebSocket('ws://localhost:8080/ws');
            ws.onopen = function() {
                ws.send(JSON.stringify(realtimeData));
                setTimeout(() => ws.close(), 1000); 
            };
        }
    });
    </script>
</body>
</html>