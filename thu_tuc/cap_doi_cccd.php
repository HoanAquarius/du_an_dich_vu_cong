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
    "father_hometown" => "Xã Hoằng Kim, Huyện Hoằng Hóa, Tỉnh Thanh Hóa",
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
    $thu_tuc = "Cấp đổi thẻ Căn cước công dân";
    $target_time_mysql = date('Y-m-d H:i:s', strtotime('+15 days')); // Cấp đổi CCCD thường hẹn 15 ngày

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
            "target_time" => date('Y-m-d\TH:i:s', strtotime('+15 days'))
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
    <title>Cấp đổi thẻ Căn cước công dân - Cổng dịch vụ công quốc gia</title>
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
                <span class="text-gray-800">Cấp đổi thẻ Căn cước công dân</span>
            </div>
 
            <h1 class="text-2xl font-bold mb-1">Cấp đổi thẻ Căn cước công dân</h1>
            <p class="text-sm text-gray-500 mb-4">Công an TP. Buôn Ma Thuột</p>
 
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
                         
                        <div class="text-center mb-8">
                            <h2 class="font-bold text-[16px] md:text-lg uppercase tracking-wide">Cộng hòa xã hội chủ nghĩa Việt Nam</h2>
                            <h3 class="font-bold text-[15px] md:text-base underline underline-offset-4 mb-8">Độc lập - Tự do - Hạnh phúc</h3>
                            <h1 class="font-bold text-xl md:text-2xl uppercase mt-8 mb-6">Tờ khai Căn cước công dân</h1>
                            <p class="italic text-sm">(Dùng cho cấp, đổi, cấp lại thẻ CCCD/Thẻ Căn cước)</p>
                        </div>
 
                        <div class="space-y-4 mt-6">
                            <div class="flex items-end gap-2 ml-8 md:ml-16">
                                <span class="whitespace-nowrap">Kính gửi: <sup>(1)</sup></span>
                                <input type="text" id="kinh_gui" name="kinh_gui" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="VD: Đội CSQLHC về TTXH Công an TP. Buôn Ma Thuột">
                            </div>
 
                            <div class="flex items-end gap-2 mt-6">
                                <span class="whitespace-nowrap font-bold">1. Họ, chữ đệm và tên:</span>
                                <input type="text" id="req_name" name="req_name" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold uppercase" placeholder="VD: NGUYỄN VĂN A">
                            </div>
                             
                            <div class="flex flex-wrap md:flex-nowrap items-end gap-2">
                                <span class="whitespace-nowrap">2. Ngày, tháng, năm sinh:</span>
                                <input type="text" id="req_dob" name="req_dob" class="flex-1 min-w-[80px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="VD: 01/01/1990">
                                <span class="whitespace-nowrap">3. Giới tính:</span>
                                <input type="text" id="req_gender" name="req_gender" class="w-24 border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 text-center" placeholder="Nam/Nữ">
                            </div>

                            <div class="flex flex-wrap md:flex-nowrap items-end gap-2">
                                <span class="whitespace-nowrap">4. Dân tộc:</span>
                                <input type="text" name="req_ethnicity" value="Kinh" class="bg-autofill flex-1 min-w-[60px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" title="Đã điền tự động">
                                <span class="whitespace-nowrap">5. Tôn giáo:</span>
                                <input type="text" name="req_religion" value="Không" class="flex-1 min-w-[60px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>
                             
                            <div class="flex items-end gap-2 mt-1">
                                <span class="whitespace-nowrap">6. Số định danh cá nhân / CCCD cũ:</span>
                                <input type="text" id="req_id_number" name="req_id_number" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold tracking-widest" placeholder="Nhập số CCCD/CMND cũ">
                            </div>

                            <div class="flex flex-col gap-2 mt-1">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap">7. Quê quán:</span>
                                    <input type="text" id="req_hometown_line1" name="req_hometown_line1" maxlength="65" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="Nhập quê quán...">
                                </div>
                                <input type="text" id="req_hometown_line2" name="req_hometown_line2" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>
                             
                            <div class="flex flex-col gap-2 mt-1">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap">8. Nơi thường trú:</span>
                                    <input type="text" id="req_thuong_tru_line1" name="req_thuong_tru_line1" maxlength="65" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="Nhập nơi thường trú hiện tại...">
                                </div>
                                <input type="text" id="req_thuong_tru_line2" name="req_thuong_tru_line2" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>

                            <div class="flex flex-col gap-2 mt-1">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap">9. Nơi ở hiện tại:</span>
                                    <input type="text" id="req_hien_tai_line1" name="req_hien_tai_line1" maxlength="65" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="Nhập địa chỉ nơi ở thực tế hiện tại...">
                                </div>
                                <input type="text" id="req_hien_tai_line2" name="req_hien_tai_line2" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1">
                            </div>

                            <div class="flex flex-wrap md:flex-nowrap items-end gap-2 mt-1">
                                <span class="whitespace-nowrap">10. Số điện thoại liên hệ:</span>
                                <input type="text" id="req_phone" name="req_phone" class="flex-1 min-w-[80px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="VD: 098xxxxxxx">
                            </div>
                             
                            <div class="flex flex-col gap-2 mt-1">
                                <div class="flex items-end gap-2">
                                    <span class="whitespace-nowrap font-bold">11. Yêu cầu của công dân: <sup>(2)</sup></span>
                                    <input type="text" id="noi_dung_de_nghi_line1" name="noi_dung_de_nghi_line1" maxlength="50" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold" placeholder="VD: Đổi từ CMND 9 số sang CCCD gắn chip / Cấp lại do mất...">
                                </div>
                                <input type="text" id="noi_dung_de_nghi_line2" name="noi_dung_de_nghi_line2" class="w-full border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1 font-bold">
                            </div>

                            <div class="flex items-end gap-2 mt-1">
                                <span class="whitespace-nowrap">Chuyển phát thẻ CCCD đến địa chỉ (nếu có):</span>
                                <input type="text" name="chuyen_phat" class="flex-1 min-w-[50px] border-b-[1.5px] border-dotted border-black bg-transparent focus:outline-none focus:border-blue-600 px-1" placeholder="Điền địa chỉ nhận thẻ qua bưu điện...">
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
                <i class="fa-solid fa-lock text-[10px]"></i> Hệ thống sẽ tạo lịch hẹn chụp ảnh và lấy vân tay tại trụ sở Công an sau khi hồ sơ được duyệt.
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
                <p class="text-sm text-gray-600 mb-4">Dựa trên thủ tục "Cấp đổi thẻ CCCD" và tài khoản của bạn, hồ sơ cần chuẩn bị bao gồm:</p>
                 
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
                                <td class="px-4 py-3">Tờ khai Căn cước công dân (CC01)</td>
                                <td class="px-4 py-3 text-green-600"><i class="fa-solid fa-check mr-1"></i>Bắt buộc</td>
                                <td class="px-4 py-3 text-red-500"><i class="fa-regular fa-circle-xmark mr-1"></i>Đang nhập</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-center">2</td>
                                <td class="px-4 py-3">CMND/CCCD cũ đang sử dụng (để thu hồi/cắt góc)</td>
                                <td class="px-4 py-3 text-gray-400">—</td>
                                <td class="px-4 py-3 text-gray-400">Mang theo khi lên trụ sở</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
 
                <div class="mb-6">
                    <h3 class="font-semibold text-sm mb-3">Thông tin thủ tục</h3>
                    <ul class="text-xs text-gray-600 space-y-2 mb-3">
                        <li class="flex items-center"><span class="mr-2 text-sm">⏱</span>Thời gian giải quyết: 07 - 15 ngày làm việc</li>
                        <li class="flex items-center"><span class="mr-2 text-sm">💰</span>Lệ phí: 30.000đ (đổi) / 50.000đ (cấp lại do mất)</li>
                        <li class="flex items-center"><span class="mr-2 text-sm">📍</span>Cơ quan tiếp nhận: Công an cấp huyện / Phòng CSQLHC</li>
                        <li class="flex items-center"><span class="mr-2 text-sm">📸</span>Lưu ý: Công dân cần đến trực tiếp để chụp ảnh, thu thập vân tay, mống mắt.</li>
                    </ul>
                    <button class="w-full py-2 bg-purple-50 text-purple-700 border border-purple-200 rounded-lg text-xs font-semibold hover:bg-purple-100 transition-colors flex items-center justify-center gap-1.5">
                        Xem hướng dẫn chi tiết <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
 
                <div class="mb-4">
                    <h3 class="font-semibold text-sm mb-3">Câu hỏi thường gặp</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Chụp ảnh có được trang điểm không?</span>
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Làm CCCD ở nơi tạm trú được không?</span>
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Mất CCCD làm lại thủ tục thế nào?</span>
                        <span class="bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-700 text-xs px-3 py-1.5 rounded-full">Nhận thẻ qua bưu điện mất bao lâu?</span>
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

        // Áp dụng auto-jump cho form CC01
        setupAutoJump('req_hometown_line1', 'req_hometown_line2', 64);
        setupAutoJump('req_thuong_tru_line1', 'req_thuong_tru_line2', 64);
        setupAutoJump('req_hien_tai_line1', 'req_hien_tai_line2', 64);
        setupAutoJump('noi_dung_de_nghi_line1', 'noi_dung_de_nghi_line2', 49);

        // THỰC HIỆN AUTO-FILL TỪ VÍ ĐIỆN TỬ
        if (walletData) {
            let kinhGuiValue = "";
            if (walletData.requester_address) {
                const addressParts = walletData.requester_address.split(',');
                if (addressParts.length >= 3) {
                    // Cơ quan cấp đổi thẻ CCCD thường ở Công an cấp huyện trở lên
                    const quanHuyen = addressParts[addressParts.length - 2].trim();
                    kinhGuiValue = "Đội CSQLHC về TTXH Công an " + quanHuyen;
                }
            }
            
            // Xử lý chia dòng cho Quê quán và Thường trú
            const [htLine1, htLine2] = splitTextForTwoLines(walletData.father_hometown, 65);
            const [addrLine1, addrLine2] = splitTextForTwoLines(walletData.requester_address, 65);
 
            const fillMap = {
                'kinh_gui': kinhGuiValue,
                'req_name': walletData.requester_name,
                'req_dob': walletData.requester_dob,
                'req_gender': walletData.requester_gender,
                'req_id_number': walletData.requester_id_number,
                'req_phone': walletData.phone_number,
                
                // Điền quê quán từ dữ liệu
                'req_hometown_line1': htLine1,
                'req_hometown_line2': htLine2,

                // Điền nơi thường trú theo CSDL hiện tại. Bỏ trống nơi ở hiện tại.
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