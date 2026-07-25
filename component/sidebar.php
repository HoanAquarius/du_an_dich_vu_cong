<?php
// Xử lý đường dẫn tương đối và menu đang active
$base = isset($base_path) ? $base_path : './';
$active = isset($active_page) ? $active_page : '';
?>
<aside class="w-64 bg-white border-r flex flex-col justify-between shrink-0 overflow-y-auto hidden md:flex">
    <nav class="p-4 space-y-1">
        <a href="<?php echo $base; ?>index.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm <?php echo ($active == 'home') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-100'; ?>">
            <i class="fa-solid fa-house w-5 text-center"></i> Trang chủ
        </a>
        <a href="<?php echo $base; ?>thu_tuc/thu_tuc.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm <?php echo ($active == 'thu_tuc') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-100'; ?>">
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
        <a href="<?php echo $base; ?>camera_ai.php" class="flex items-center justify-between px-4 py-2.5 rounded-lg text-sm <?php echo ($active == 'camera_ai') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-100'; ?>">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-robot w-5 text-center"></i> Camera AI kiểm tra
            </div>
            <span class="bg-green-500 text-white text-[10px] px-1.5 py-0.5 rounded font-bold">AI</span>
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