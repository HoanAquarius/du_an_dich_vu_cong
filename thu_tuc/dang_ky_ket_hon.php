<?php
// Gọi file kết nối Database từ thư mục config
require_once __DIR__ . '/../config/database.php';

// Giả lập lấy "Ví số" của user đang đăng nhập
$mockDigitalWallet = [
    "requester_name" => "NGUYỄN VĂN A",
    "requester_dob" => "01/01/1990",
    "requester_gender" => "Nam", // Quan trọng để quyết định điền vào bên Nam hay Nữ
    "requester_address" => "Số 1, Đường Hà Huy Tập, Phường Tân An, TP. Buôn Ma Thuột, Tỉnh Đắk Lắk",
    "requester_id_type" => "Căn cước công dân",
    "requester_id_number" => "001090123456",
    "requester_id_issued_by" => "Cục Cảnh sát QLHC về TTXH",
    "requester_id_issued_date" => "15/10/2021",
    "phone_number" => "0898383246"
];
$walletJsonData = json_encode($mockDigitalWallet);
 
$message = '';
$realtimeDataJson = 'null';

// Xử lý Submit Form và Lưu Database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy tên người đại diện nộp hồ sơ (có thể là nam hoặc nữ)
    $req_name = $_POST['husband_name'] ?? ($_POST['wife_name'] ?? 'Công dân');
    
    // Tạo dữ liệu hồ sơ
    $mockMaso = "HSO-" . date('Y') . "-" . rand(10000, 99999);
    $thu_tuc = "Đăng ký kết hôn";
    $target_time_mysql = date('Y-m-d H:i:s', strtotime('+3 days')); // Kết hôn thường xử lý trong 1-3 ngày làm việc

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
            "target_time" => date('Y-m-d\TH:i:s', strtotime('+3 days'))
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
    <title>Đăng ký kết hôn - Cổng dịch vụ công quốc gia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .bg-autofill {
            background-color: #f0fdf4 !important; 
            font-weight: bold !important;
            color: #166534 !important; 
            border-bottom-color: #22c55e !important;
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-gray-800 h-screen flex flex-col overflow-hidden">
 
    <?php include '../component/header.php'; ?>

    <div class="flex flex-1 overflow-hidden">
        
        <?php 
            $base_path = '../';
            $active_page = 'thu_tuc';
            include '../component/sidebar.php'; 
        ?>
 
        <main class="flex-[6] min-w-0 bg-white border-r p-4 lg:p-6 overflow-y-auto">
            <div class="text-xs text-gray-500 mb-4 flex items-center gap-2">
                <span>Trang chủ</span> <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span>Thủ tục hành chính</span> <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-gray-800">Đăng ký kết hôn</span>
            </div>
 
            <h1 class="text-2xl font-bold mb-1">Đăng ký kết hôn</h1>
            <p class="text-sm text-gray-500 mb-4">UBND Phường Tân An, TP. Buôn Ma Thuột</p>
 
            <?php if(!empty($message)): ?>
                <div class="bg-blue-50 border border-blue-200 text-blue-800 p-3 rounded-lg mb-4 text-sm font-medium">
                    <i class="fa-solid fa-circle-info mr-2"></i> <?php echo $message; ?>
                </div>
            <?php endif; ?>
 
            <div class="bg-green-50 border border-green-100 text-green-800 p-3 rounded-lg flex items-center justify-between mb-8 text-sm">
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-circle-check text-lg"></i>
                    <span>Hệ thống nhận diện bạn là <strong id="user_gender_display">Nam/Nữ</strong>. Dữ liệu của bạn đã được điền tự động vào phần tương ứng. Vui lòng bổ sung thông tin của người còn lại.</span>
                </div>
            </div>
 
            <form action="" method="POST" enctype="multipart/form-data">
                 
                <div class="bg-gray-200 p-2 md:p-4 rounded-xl border border-gray-300 mb-8 shadow-inner overflow-x-auto">
                    <div class="w-full max-w-[850px] min-w-[500px] mx-auto bg-white p-6 md:p-10 shadow-xl text-black text-[14px] md:text-[15px] leading-relaxed" style="font-family: 'Times New Roman', Times, serif;">
                         
                        <div class="text-center mb-8 relative">
                            <!-- Nơi dán ảnh (theo một số mẫu địa phương) -->
                            <div class="absolute right-0 top-0 w-20 h-28 border-2 border-dashed border-gray-400 flex items-center justify-center text-[10px] text-gray-400 text-center flex-col">
                                <i class="fa-regular fa-image text-xl mb-1"></i>
                                Ảnh Nam<br>(nếu có)
                            </div>
                            <div class="absolute right-24 top-0 w-20 h-28 border-2 border-dashed border-gray-400 flex items-center justify-center text-[10px] text-gray-400 text-center flex-col">
                                <i class="fa-regular fa-image text-xl mb-1"></i>
                                Ảnh Nữ<br>(nếu có)
                            </div>

                            <h2 class="font-bold text-[16px] md:text-lg uppercase tracking-wide">Cộng hòa xã hội chủ nghĩa Việt Nam</h2>
                            <h3 class="font-bold text-[15px] md:text-base underline underline-offset-4 mb-8">Độc lập - Tự do - Hạnh phúc</h3>
                            <h1 class="font-bold text-xl md:text-2xl uppercase mt-12 mb-6">Tờ khai đăng ký kết hôn</h1>
                        </div>
 
                        <div class="space-y-6 mt-6">
                            <div class="flex items-end gap-2 ml-8 md:ml-16">
                                <span class="whitespace-nowrap">Kính gửi: <sup>(1)</sup></span>
                                <input type="text" id="kinh_gui" name="kinh_gui" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold" placeholder="VD: UBND Phường Tân An, TP. Buôn Ma Thuột">
                            </div>
 
                            <!-- CHIA 2 CỘT NAM VÀ NỮ -->
                            <div class="flex flex-col md:flex-row border border-black mt-6 relative">
                                <!-- Cột Nam -->
                                <div class="flex-1 border-b md:border-b-0 md:border-r border-black p-4 space-y-4 relative">
                                    <div class="absolute top-0 left-0 bg-blue-100 text-blue-800 font-bold px-3 py-1 text-xs rounded-br-lg border-b border-r border-blue-200">THÔNG TIN BÊN NAM</div>
                                    
                                    <div class="pt-6 flex flex-col gap-1">
                                        <span class="whitespace-nowrap font-bold">Họ, chữ đệm, tên:</span>
                                        <input type="text" id="husband_name" name="husband_name" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold uppercase text-center" placeholder="VD: NGUYỄN VĂN A">
                                    </div>
                                    
                                    <div class="flex flex-col gap-1">
                                        <span class="whitespace-nowrap">Ngày, tháng, năm sinh:</span>
                                        <input type="text" id="husband_dob" name="husband_dob" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 text-center" placeholder="dd/mm/yyyy">
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <div class="flex-1 flex flex-col gap-1">
                                            <span class="whitespace-nowrap">Dân tộc:</span>
                                            <input type="text" id="husband_ethnicity" name="husband_ethnicity" value="Kinh" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 text-center">
                                        </div>
                                        <div class="flex-1 flex flex-col gap-1">
                                            <span class="whitespace-nowrap">Quốc tịch:</span>
                                            <input type="text" id="husband_nationality" name="husband_nationality" value="Việt Nam" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 text-center">
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col gap-1">
                                        <span class="whitespace-nowrap">Nơi cư trú:</span>
                                        <input type="text" id="husband_address_line1" name="husband_address_line1" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                        <input type="text" id="husband_address_line2" name="husband_address_line2" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                    </div>

                                    <div class="flex flex-col gap-1">
                                        <span class="whitespace-nowrap">Giấy tờ tùy thân:</span>
                                        <input type="text" id="husband_id_line1" name="husband_id_line1" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="CCCD số...">
                                        <input type="text" id="husband_id_line2" name="husband_id_line2" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                    </div>
                                    
                                    <div class="flex flex-col gap-1">
                                        <span class="whitespace-nowrap">Kết hôn lần thứ:</span>
                                        <input type="text" id="husband_marriage_times" name="husband_marriage_times" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 text-center" placeholder="1, 2...">
                                    </div>
                                </div>

                                <!-- Cột Nữ -->
                                <div class="flex-1 p-4 space-y-4 relative">
                                    <div class="absolute top-0 left-0 bg-pink-100 text-pink-800 font-bold px-3 py-1 text-xs rounded-br-lg border-b border-r border-pink-200">THÔNG TIN BÊN NỮ</div>
                                    
                                    <div class="pt-6 flex flex-col gap-1">
                                        <span class="whitespace-nowrap font-bold">Họ, chữ đệm, tên:</span>
                                        <input type="text" id="wife_name" name="wife_name" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold uppercase text-center" placeholder="VD: TRẦN THỊ B">
                                    </div>
                                    
                                    <div class="flex flex-col gap-1">
                                        <span class="whitespace-nowrap">Ngày, tháng, năm sinh:</span>
                                        <input type="text" id="wife_dob" name="wife_dob" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 text-center" placeholder="dd/mm/yyyy">
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <div class="flex-1 flex flex-col gap-1">
                                            <span class="whitespace-nowrap">Dân tộc:</span>
                                            <input type="text" id="wife_ethnicity" name="wife_ethnicity" value="Kinh" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 text-center">
                                        </div>
                                        <div class="flex-1 flex flex-col gap-1">
                                            <span class="whitespace-nowrap">Quốc tịch:</span>
                                            <input type="text" id="wife_nationality" name="wife_nationality" value="Việt Nam" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 text-center">
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col gap-1">
                                        <span class="whitespace-nowrap">Nơi cư trú:</span>
                                        <input type="text" id="wife_address_line1" name="wife_address_line1" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                        <input type="text" id="wife_address_line2" name="wife_address_line2" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                    </div>

                                    <div class="flex flex-col gap-1">
                                        <span class="whitespace-nowrap">Giấy tờ tùy thân:</span>
                                        <input type="text" id="wife_id_line1" name="wife_id_line1" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="CCCD số...">
                                        <input type="text" id="wife_id_line2" name="wife_id_line2" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                    </div>
                                    
                                    <div class="flex flex-col gap-1">
                                        <span class="whitespace-nowrap">Kết hôn lần thứ:</span>
                                        <input type="text" id="wife_marriage_times" name="wife_marriage_times" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 text-center" placeholder="1, 2...">
                                    </div>
                                </div>
                            </div>
                             
                            <div class="mt-6">
                                <p class="text-justify indent-8">Chúng tôi cam đoan việc kết hôn là hoàn toàn tự nguyện, không vi phạm quy định của Luật Hôn nhân và gia đình Việt Nam; những lời khai trên đây là đúng sự thật và chịu trách nhiệm trước pháp luật.</p>
                                <p class="text-justify indent-8 mt-2">Đề nghị cơ quan đăng ký kết hôn cho chúng tôi.</p>
                            </div>
                        </div>
                    </div>
                </div>
 
                <div class="flex items-center justify-between border-t pt-6 mb-4 font-sans">
                    <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm font-medium">Quay lại</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium flex items-center gap-2">
                        Tiếp tục nộp hồ sơ <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </form>
            <div class="text-xs text-gray-500 flex items-center gap-1 font-sans">
                <i class="fa-solid fa-lock text-[10px]"></i> Hệ thống liên thông với cơ sở dữ liệu Hộ tịch điện tử.
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
                <p class="text-sm text-gray-600 mb-4">Dựa trên thủ tục "Đăng ký kết hôn" và tài khoản của bạn, hồ sơ cần chuẩn bị bao gồm:</p>
                 
                <div class="bg-white border rounded-lg overflow-hidden mb-6">
                    <div class="px-4 py-3 bg-gray-50 border-b font-semibold text-sm">Danh sách hồ sơ cần nộp</div>
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
                                <td class="px-4 py-3">Tờ khai đăng ký kết hôn (Theo mẫu)</td>
                                <td class="px-4 py-3 text-green-600"><i class="fa-solid fa-check mr-1"></i>Bắt buộc</td>
                                <td class="px-4 py-3 text-red-500"><i class="fa-regular fa-circle-xmark mr-1"></i>Đang nhập</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-center">2</td>
                                <td class="px-4 py-3">Giấy xác nhận tình trạng hôn nhân của Bên Nam (hoặc Bên Nữ)</td>
                                <td class="px-4 py-3 text-green-600"><i class="fa-solid fa-check mr-1"></i>Bắt buộc</td>
                                <td class="px-4 py-3 text-red-500"><i class="fa-regular fa-circle-xmark mr-1"></i>Chưa tải lên</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-center">3</td>
                                <td class="px-4 py-3">CMND/CCCD của 2 bên nam nữ</td>
                                <td class="px-4 py-3 text-green-600"><i class="fa-solid fa-check mr-1"></i>Bắt buộc</td>
                                <td class="px-4 py-3 text-green-600"><i class="fa-regular fa-circle-check mr-1"></i>Xác thực qua VNeID</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-center">4</td>
                                <td class="px-4 py-3">Bản án/Quyết định ly hôn của Tòa án (nếu đã từng ly hôn)</td>
                                <td class="px-4 py-3 text-gray-400">—</td>
                                <td class="px-4 py-3 text-gray-400">Nếu có</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
 
                <div class="mb-6">
                    <h3 class="font-semibold text-sm mb-3">Thông tin thủ tục</h3>
                    <ul class="text-xs text-gray-600 space-y-2 mb-3">
                        <li class="flex items-center"><span class="mr-2 text-sm">⏱</span>Thời gian giải quyết: Ngay trong ngày (hoặc tối đa 3 ngày làm việc)</li>
                        <li class="flex items-center"><span class="mr-2 text-sm">💰</span>Lệ phí: Miễn phí (tại nơi cư trú)</li>
                        <li class="flex items-center"><span class="mr-2 text-sm">📍</span>Cơ quan tiếp nhận: UBND cấp xã nơi cư trú của Nam hoặc Nữ</li>
                        <li class="flex items-center"><span class="mr-2 text-sm">👩‍❤️‍👨</span>Lưu ý: Hai bên nam nữ phải có mặt cùng lúc khi nhận Giấy chứng nhận kết hôn.</li>
                    </ul>
                    <button class="w-full py-2 bg-purple-50 text-purple-700 border border-purple-200 rounded-lg text-xs font-semibold hover:bg-purple-100 transition-colors flex items-center justify-center gap-1.5">
                        Xem hướng dẫn chi tiết <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
 
                <div class="mb-4">
                    <h3 class="font-semibold text-sm mb-3">Câu hỏi thường gặp</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Khác tỉnh kết hôn ở đâu?</span>
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Có cần Xin giấy xác nhận độc thân không?</span>
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Nhận giấy kết hôn có cần 2 người đi không?</span>
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Nam 19 tuổi, nữ 18 tuổi kết hôn được chưa?</span>
                    </div>
                </div>
            </div>
 
            <div class="p-4 border-t bg-white shrink-0">
                <div class="relative">
                    <input type="text" placeholder="Bạn cần trợ lý giải đáp thêm điều gì?" class="w-full border border-gray-300 rounded-full py-2.5 pl-4 pr-12 text-sm focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
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
        
        // HÀM 1: Cắt chuỗi dài thành 2 dòng
        function splitTextForTwoLines(text, maxLength) {
            if (!text) return ["", ""];
            if (text.length <= maxLength) return [text, ""];
            
            let splitIndex = text.lastIndexOf(" ", maxLength);
            if (splitIndex === -1) splitIndex = maxLength;
            
            return [text.substring(0, splitIndex).trim(), text.substring(splitIndex).trim()];
        }

        // HÀM 2: Auto-jump con trỏ
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

        // Áp dụng auto-jump cho form 
        setupAutoJump('husband_address_line1', 'husband_address_line2', 30);
        setupAutoJump('husband_id_line1', 'husband_id_line2', 30);
        setupAutoJump('wife_address_line1', 'wife_address_line2', 30);
        setupAutoJump('wife_id_line1', 'wife_id_line2', 30);

        // THỰC HIỆN AUTO-FILL TỪ VÍ ĐIỆN TỬ DỰA TRÊN GIỚI TÍNH
        if (walletData) {
            let kinhGuiValue = "UBND Cấp Xã";
            if (walletData.requester_address) {
                const addressParts = walletData.requester_address.split(',');
                if (addressParts.length >= 3) {
                    const phuongXa = addressParts[addressParts.length - 3].trim();
                    const quanHuyen = addressParts[addressParts.length - 2].trim();
                    kinhGuiValue = "UBND " + phuongXa + ", " + quanHuyen;
                }
            }
            
            // Xử lý chia dòng Thường trú
            const [addrLine1, addrLine2] = splitTextForTwoLines(walletData.requester_address, 30);
            
            // Xử lý Giấy tờ tùy thân
            let idLine1 = "";
            let idLine2 = "";
            if(walletData.requester_id_type && walletData.requester_id_number) {
                idLine1 = `${walletData.requester_id_type} số ${walletData.requester_id_number}`;
                if(walletData.requester_id_issued_by) {
                    idLine2 = `do ${walletData.requester_id_issued_by}`;
                }
            }

            // Điền chung thông tin kính gửi
            document.getElementById('kinh_gui').value = kinhGuiValue;
            document.getElementById('kinh_gui').classList.add('bg-autofill');

            // Xác định điền vào cột Nam hay Nữ
            const isMale = walletData.requester_gender && walletData.requester_gender.toLowerCase() === 'nam';
            
            // Hiển thị ra UI thông báo
            const genderDisplay = document.getElementById('user_gender_display');
            if(genderDisplay) {
                genderDisplay.textContent = isMale ? "Nam" : "Nữ";
            }

            let prefix = isMale ? "husband_" : "wife_";

            const fillMap = {
                [prefix + 'name']: walletData.requester_name,
                [prefix + 'dob']: walletData.requester_dob,
                [prefix + 'address_line1']: addrLine1,
                [prefix + 'address_line2']: addrLine2,
                [prefix + 'id_line1']: idLine1,
                [prefix + 'id_line2']: idLine2,
                [prefix + 'marriage_times']: "1" // Giả định mặc định là lần 1
            };
 
            for (const [inputId, value] of Object.entries(fillMap)) {
                const inputElement = document.getElementById(inputId);
                if (inputElement && value) {
                    inputElement.value = value;
                    inputElement.classList.add('bg-autofill');
                    inputElement.title = 'Đã lấy từ CSDL Quốc gia về Dân cư';
                }
            }
        }

        // Logic Gửi thông báo WebSocket
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