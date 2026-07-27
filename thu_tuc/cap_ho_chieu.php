<?php
// Gọi file kết nối Database từ thư mục config
require_once __DIR__ . '/../config/database.php';

// Giả lập lấy "Ví số" của user đang đăng nhập
$mockDigitalWallet = [
    "requester_name" => "NGUYỄN VĂN A",
    "requester_dob" => "01/01/1990",
    "requester_gender" => "Nam",
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
    $req_name = $_POST['req_name'] ?? '';
    
    // Tạo dữ liệu hồ sơ
    $mockMaso = "HSO-" . date('Y') . "-" . rand(10000, 99999);
    $thu_tuc = "Cấp Hộ chiếu phổ thông";
    $target_time_mysql = date('Y-m-d H:i:s', strtotime('+8 days')); // Cấp hộ chiếu thường hẹn 8 ngày làm việc

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
            "target_time" => date('Y-m-d\TH:i:s', strtotime('+8 days'))
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
    <title>Cấp Hộ chiếu phổ thông - Cổng dịch vụ công quốc gia</title>
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
                <span class="text-gray-800">Cấp Hộ chiếu phổ thông</span>
            </div>
 
            <h1 class="text-2xl font-bold mb-1">Cấp Hộ chiếu phổ thông ở trong nước</h1>
            <p class="text-sm text-gray-500 mb-4">Phòng Quản lý xuất nhập cảnh Công an Tỉnh Đắk Lắk</p>
 
            <?php if(!empty($message)): ?>
                <div class="bg-blue-50 border border-blue-200 text-blue-800 p-3 rounded-lg mb-4 text-sm font-medium">
                    <i class="fa-solid fa-circle-info mr-2"></i> <?php echo $message; ?>
                </div>
            <?php endif; ?>
 
            <div class="bg-green-50 border border-green-100 text-green-800 p-3 rounded-lg flex items-center justify-between mb-8 text-sm">
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-circle-check"></i>
                    <span>Hệ thống đã tự động điền thông tin từ Kho dữ liệu quốc gia về dân cư. Các trường tô màu xanh là dữ liệu tự điền.</span>
                </div>
                <a href="#" class="text-blue-600 hover:underline whitespace-nowrap ml-2">Xem chi tiết</a>
            </div>
 
            <form action="" method="POST" enctype="multipart/form-data">
                 
                <div class="bg-gray-200 p-2 md:p-4 rounded-xl border border-gray-300 mb-8 shadow-inner overflow-x-auto">
                    <div class="w-full max-w-[850px] min-w-[500px] mx-auto bg-white p-6 md:p-10 shadow-xl text-black text-[14px] md:text-[15px] leading-relaxed" style="font-family: 'Times New Roman', Times, serif;">
                         
                        <div class="text-center mb-8 relative">
                            <!-- Khung ảnh thẻ mô phỏng -->
                            <div class="absolute left-0 top-0 w-24 h-32 border-2 border-dashed border-gray-400 flex items-center justify-center text-xs text-gray-400 text-center flex-col">
                                <i class="fa-regular fa-image text-2xl mb-1"></i>
                                Ảnh 4x6<br>nền trắng
                            </div>
                            
                            <h2 class="font-bold text-[16px] md:text-lg uppercase tracking-wide">Cộng hòa xã hội chủ nghĩa Việt Nam</h2>
                            <h3 class="font-bold text-[15px] md:text-base underline underline-offset-4 mb-8">Độc lập - Tự do - Hạnh phúc</h3>
                            <h1 class="font-bold text-xl md:text-2xl uppercase mt-8 mb-6">Tờ khai đề nghị cấp hộ chiếu</h1>
                            <p class="italic text-sm">(Dùng cho công dân Việt Nam đề nghị cấp hộ chiếu phổ thông ở trong nước)</p>
                        </div>
 
                        <div class="space-y-4 mt-6">
                            <div class="flex items-end gap-2 ml-8 md:ml-16">
                                <span class="whitespace-nowrap">Kính gửi: <sup>(1)</sup></span>
                                <input type="text" id="kinh_gui" name="kinh_gui" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="VD: Cục Quản lý xuất nhập cảnh / Phòng QLXNC Công an tỉnh...">
                            </div>
 
                            <div class="flex items-end gap-2 mt-6">
                                <span class="whitespace-nowrap font-bold">1. Họ và tên:</span>
                                <input type="text" id="req_name" name="req_name" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold uppercase" placeholder="VD: NGUYỄN VĂN A">
                            </div>
                             
                            <div class="flex flex-wrap md:flex-nowrap items-end gap-2">
                                <span class="whitespace-nowrap">2. Ngày sinh:</span>
                                <input type="text" id="req_dob" name="req_dob" class="flex-1 min-w-[80px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="VD: 01/01/1990">
                                <span class="whitespace-nowrap">3. Giới tính:</span>
                                <input type="text" id="req_gender" name="req_gender" class="w-24 border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 text-center" placeholder="Nam/Nữ">
                            </div>
                             
                            <div class="flex items-end gap-2 mt-1">
                                <span class="whitespace-nowrap">4. Số định danh cá nhân/CCCD/CMND:</span>
                                <input type="text" id="req_id_number" name="req_id_number" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold tracking-widest" placeholder="Nhập số CCCD/CMND 12 số">
                            </div>

                            <div class="flex flex-wrap md:flex-nowrap items-end gap-2">
                                <span class="whitespace-nowrap">Cấp ngày:</span>
                                <input type="text" id="req_id_issued_date" name="req_id_issued_date" class="flex-1 min-w-[80px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                                <span class="whitespace-nowrap">Nơi cấp:</span>
                                <input type="text" id="req_id_issued_by" name="req_id_issued_by" class="flex-[2] min-w-[120px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>
                             
                            <div class="flex flex-col gap-2 mt-1">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap">5. Nơi thường trú:</span>
                                    <input type="text" id="req_thuong_tru_line1" name="req_thuong_tru_line1" maxlength="65" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="Nhập nơi thường trú...">
                                </div>
                                <input type="text" id="req_thuong_tru_line2" name="req_thuong_tru_line2" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>

                            <div class="flex flex-col gap-2 mt-1">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap">6. Nơi ở hiện tại:</span>
                                    <input type="text" id="req_hien_tai_line1" name="req_hien_tai_line1" maxlength="65" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="Nhập địa chỉ nơi ở thực tế hiện tại...">
                                </div>
                                <input type="text" id="req_hien_tai_line2" name="req_hien_tai_line2" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>

                            <div class="flex flex-wrap md:flex-nowrap items-end gap-2 mt-1">
                                <span class="whitespace-nowrap">7. Điện thoại:</span>
                                <input type="text" id="req_phone" name="req_phone" class="flex-1 min-w-[80px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="VD: 098xxxxxxx">
                            </div>
                             
                            <div class="flex flex-col gap-2 mt-1">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap font-bold">8. Nội dung đề nghị: <sup>(2)</sup></span>
                                    <input type="text" id="noi_dung_de_nghi_line1" name="noi_dung_de_nghi_line1" maxlength="50" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold" placeholder="VD: Cấp hộ chiếu lần đầu / Cấp lại do hết hạn...">
                                </div>
                                <input type="text" id="noi_dung_de_nghi_line2" name="noi_dung_de_nghi_line2" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold">
                            </div>

                            <div class="flex flex-col gap-2 mt-1">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap">9. Hộ chiếu phổ thông được cấp gần nhất (nếu có): số</span>
                                    <input type="text" name="ho_chieu_cu" class="w-32 border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 text-center" placeholder="Nhập số HC">
                                    <span class="whitespace-nowrap">cấp ngày</span>
                                    <input type="text" name="ngay_cap_hc_cu" class="flex-1 border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 text-center" placeholder="dd/mm/yyyy">
                                </div>
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
                <i class="fa-solid fa-lock text-[10px]"></i> Hệ thống liên thông với Cục Quản lý xuất nhập cảnh - Bộ Công an.
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
                <p class="text-sm text-gray-600 mb-4">Dựa trên thủ tục "Cấp Hộ chiếu phổ thông" và tài khoản của bạn, hồ sơ cần chuẩn bị bao gồm:</p>
                 
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
                                <td class="px-4 py-3">Tờ khai đề nghị cấp hộ chiếu (TK01)</td>
                                <td class="px-4 py-3 text-green-600"><i class="fa-solid fa-check mr-1"></i>Bắt buộc</td>
                                <td class="px-4 py-3 text-red-500"><i class="fa-regular fa-circle-xmark mr-1"></i>Đang nhập</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-center">2</td>
                                <td class="px-4 py-3">Ảnh chân dung 4x6 nền trắng (file định dạng .jpg/.jpeg)</td>
                                <td class="px-4 py-3 text-green-600"><i class="fa-solid fa-check mr-1"></i>Bắt buộc</td>
                                <td class="px-4 py-3 text-red-500"><i class="fa-regular fa-circle-xmark mr-1"></i>Chưa tải lên</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-center">3</td>
                                <td class="px-4 py-3">Hộ chiếu phổ thông cấp lần gần nhất (nếu cấp lại)</td>
                                <td class="px-4 py-3 text-gray-400">—</td>
                                <td class="px-4 py-3 text-gray-400">Nếu đã từng cấp</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-center">4</td>
                                <td class="px-4 py-3">Đơn báo mất hộ chiếu (nếu làm lại do mất)</td>
                                <td class="px-4 py-3 text-gray-400">—</td>
                                <td class="px-4 py-3 text-gray-400">Không áp dụng</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
 
                <div class="mb-6">
                    <h3 class="font-semibold text-sm mb-3">Thông tin thủ tục</h3>
                    <ul class="text-xs text-gray-600 space-y-2 mb-3">
                        <li class="flex items-center"><span class="mr-2 text-sm">⏱</span>Thời gian giải quyết: 08 ngày làm việc</li>
                        <li class="flex items-center"><span class="mr-2 text-sm">💰</span>Lệ phí: 200.000đ (cấp mới) / 400.000đ (cấp lại)</li>
                        <li class="flex items-center"><span class="mr-2 text-sm">📍</span>Cơ quan tiếp nhận: Cục QLXNC / Phòng QLXNC Công an tỉnh</li>
                        <li class="flex items-center"><span class="mr-2 text-sm">🚚</span>Nhận kết quả: Trực tiếp hoặc qua dịch vụ bưu chính</li>
                    </ul>
                    <button class="w-full py-2 bg-purple-50 text-purple-700 border border-purple-200 rounded-lg text-xs font-semibold hover:bg-purple-100 transition-colors flex items-center justify-center gap-1.5">
                        Xem hướng dẫn chi tiết <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
 
                <div class="mb-4">
                    <h3 class="font-semibold text-sm mb-3">Câu hỏi thường gặp</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Tiêu chuẩn ảnh thẻ làm hộ chiếu?</span>
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Trẻ em dưới 14 tuổi làm HC thế nào?</span>
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Phân biệt hộ chiếu gắn chip và không chip?</span>
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Có làm hộ chiếu lấy ngay được không?</span>
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

        // Áp dụng auto-jump cho form HC
        setupAutoJump('req_thuong_tru_line1', 'req_thuong_tru_line2', 64);
        setupAutoJump('req_hien_tai_line1', 'req_hien_tai_line2', 64);
        setupAutoJump('noi_dung_de_nghi_line1', 'noi_dung_de_nghi_line2', 49);

        // THỰC HIỆN AUTO-FILL TỪ VÍ ĐIỆN TỬ
        if (walletData) {
            let kinhGuiValue = "Phòng Quản lý xuất nhập cảnh Công an tỉnh";
            if (walletData.requester_address) {
                const addressParts = walletData.requester_address.split(',');
                if (addressParts.length > 0) {
                    // Lấy Tỉnh/Thành phố cuối cùng trong địa chỉ để điền Kính gửi
                    const tinhThanh = addressParts[addressParts.length - 1].trim();
                    kinhGuiValue = "Phòng Quản lý xuất nhập cảnh Công an " + tinhThanh;
                }
            }
            
            // Xử lý chia dòng Thường trú
            const [addrLine1, addrLine2] = splitTextForTwoLines(walletData.requester_address, 65);
 
            const fillMap = {
                'kinh_gui': kinhGuiValue,
                'req_name': walletData.requester_name,
                'req_dob': walletData.requester_dob,
                'req_gender': walletData.requester_gender,
                'req_id_number': walletData.requester_id_number,
                'req_id_issued_date': walletData.requester_id_issued_date,
                'req_id_issued_by': walletData.requester_id_issued_by,
                'req_phone': walletData.phone_number,
                
                // Điền nơi thường trú. Bỏ trống nơi ở hiện tại.
                'req_thuong_tru_line1': addrLine1,
                'req_thuong_tru_line2': addrLine2
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