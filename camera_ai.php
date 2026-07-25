<?php
// Cấu hình đường dẫn và menu đang active cho sidebar
$base_path = './'; 
$active_page = 'camera_ai';

// Khởi tạo các biến để xử lý trạng thái form
$is_submitted = false;
$uploaded_files = [];
$ai_results = [
    'errors' => [],
    'warnings' => [],
    'passed' => [], // Bổ sung mảng lưu các tiêu chí đạt
    'passed_criteria' => 0,
    'confidence' => 0
];

// Thư mục lưu file tạm (Nhớ cấp quyền write cho thư mục này nếu chạy trên server thật)
$upload_dir = 'uploads/';
if (!file_exists($upload_dir)) {
    @mkdir($upload_dir, 0777, true);
}

// Xử lý khi người dùng bấm nút Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['documents'])) {
    $is_submitted = true;
    $files = $_FILES['documents'];
    $total_files = count($files['name']);
    
    // Xử lý lưu từng file
    for ($i = 0; $i < $total_files; $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $tmp_name = $files['tmp_name'][$i];
            $name = basename($files['name'][$i]);
            // Đổi tên file để tránh trùng lặp
            $target_file = $upload_dir . time() . '_' . $name;
            
            if (@move_uploaded_file($tmp_name, $target_file)) {
                $uploaded_files[] = [
                    'original_name' => $name,
                    'path' => $target_file
                ];
            }
        }
    }
    
    // Giả lập logic của AI Backend trả về sau khi quét file
    if (count($uploaded_files) > 0) {
        $ai_results['passed_criteria'] = count($uploaded_files) * 3 + 2; 
        $ai_results['confidence'] = rand(78, 96); 
        
        // --- GIẢ LẬP CÁC LỖI & CẢNH BÁO ---
        $ai_results['errors'][] = [
            'title' => 'Phát hiện trang bị mờ',
            'desc' => 'Một trong số các file bạn tải lên bị mờ, hệ thống OCR không thể trích xuất chính xác thông tin. Vui lòng kiểm tra lại.'
        ];
        
        if (count($uploaded_files) > 1) {
            $ai_results['errors'][] = [
                'title' => 'Thông tin không thống nhất',
                'desc' => 'Họ tên trên các tài liệu không khớp nhau hoặc có dấu hiệu tẩy xóa.'
            ];
        }

        $ai_results['warnings'][] = [
            'title' => 'Chữ ký chưa rõ nét',
            'desc' => 'Vùng chứa chữ ký và con dấu ở trang cuối cùng chưa đủ độ tương phản, cán bộ duyệt có thể yêu cầu xác minh lại.'
        ];

        // --- BỔ SUNG GIẢ LẬP CÁC TIÊU CHÍ ĐẠT YÊU CẦU ---
        $ai_results['passed'][] = [
            'title' => 'Định dạng file hợp lệ',
            'desc' => 'Tất cả các file tải lên đều đúng định dạng cho phép và không bị hỏng dữ liệu.'
        ];
        $ai_results['passed'][] = [
            'title' => 'Hình ảnh đủ 4 góc',
            'desc' => 'Giấy tờ nằm trọn trong khung hình, không bị cắt xén làm mất thông tin quan trọng.'
        ];
        $ai_results['passed'][] = [
            'title' => 'Thời hạn giấy tờ hợp lệ',
            'desc' => 'Các loại giấy tờ định danh được hệ thống nhận diện đều còn trong thời hạn sử dụng.'
        ];

    } else {
        $ai_results['errors'][] = [
            'title' => 'Thiếu tài liệu',
            'desc' => 'Hệ thống không nhận được file nào hợp lệ. Vui lòng chọn lại.'
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camera AI Kiểm Tra - Cổng Dịch vụ công Quốc gia</title>
    <!-- Tích hợp Tailwind CSS & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#f8fafc] text-gray-800 font-sans h-screen flex overflow-hidden">

    <!-- Sidebar Trái -->
    <?php include 'component/sidebar.php'; ?>

    <!-- Khối Nội Dung Chính Bên Phải -->
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <!-- Header -->
        <?php include 'component/header.php'; ?>

        <!-- Khu vực hiển thị -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8">
            
            <!-- Tiêu đề trang -->
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-2xl shadow-sm">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 leading-tight">Camera AI kiểm tra giấy tờ trước khi nộp</h1>
                        <p class="text-sm text-gray-500 mt-1">AI sẽ phân tích và cảnh báo các lỗi có nguy cơ khiến hồ sơ bị trả lại.</p>
                    </div>
                </div>
                <button class="px-4 py-2 bg-white border border-gray-300 text-blue-600 hover:bg-gray-50 rounded-lg flex items-center gap-2 text-sm font-medium transition shadow-sm">
                    <i class="fa-regular fa-circle-question"></i> Hướng dẫn sử dụng
                </button>
            </div>

            <!-- Bố cục lưới 2 cột -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Cột Trái: Upload Form (Chiếm 5 cột) -->
                <div class="lg:col-span-5 bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                    <h2 class="text-lg font-bold text-gray-900 mb-1">1. Tải giấy tờ lên để kiểm tra</h2>
                    <p class="text-sm text-gray-500 mb-5">Bạn có thể chọn tải lên nhiều file cùng lúc (Hỗ trợ Ảnh & PDF)</p>

                    <!-- Form xử lý PHP -->
                    <form action="" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1">
                        
                        <!-- Khu vực Kéo thả / Chọn file -->
                        <div class="border-2 border-dashed border-blue-300 bg-blue-50/50 hover:bg-blue-50 rounded-xl p-6 mb-4 flex flex-col items-center justify-center relative transition cursor-pointer group">
                            <!-- Input file ẩn bao phủ toàn bộ khu vực -->
                            <input type="file" name="documents[]" id="file-input" multiple accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" <?php echo !$is_submitted ? 'required' : ''; ?>>
                            
                            <i class="fa-solid fa-cloud-arrow-up text-4xl text-blue-500 mb-3 group-hover:scale-110 transition-transform duration-300"></i>
                            <span class="font-bold text-gray-700 text-sm mb-1 text-center">Bấm vào đây hoặc kéo thả file</span>
                            <span class="text-xs text-gray-500 text-center">Hỗ trợ định dạng: JPG, PNG, PDF</span>
                        </div>

                        <!-- Trạng thái số lượng file (Bị ẩn mặc định, JS sẽ mở lên) -->
                        <div id="file-preview-zone" class="<?php echo ($is_submitted && count($uploaded_files) > 0) ? '' : 'hidden'; ?> mb-4">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 flex items-center gap-3">
                                <i class="fa-solid fa-file-circle-check text-green-600 text-xl"></i>
                                <div class="flex flex-col">
                                    <span id="file-count-text" class="text-sm font-bold text-green-700">
                                        <?php echo $is_submitted ? 'Hệ thống đang lưu ' . count($uploaded_files) . ' file' : 'Đã chọn 0 file'; ?>
                                    </span>
                                    <span class="text-xs text-green-600">Dữ liệu sẵn sàng để AI phân tích</span>
                                </div>
                            </div>
                        </div>

                        <!-- LƯỚI HIỂN THỊ PREVIEW ẢNH -->
                        <div id="image-preview-container" class="grid grid-cols-2 gap-3 mb-6">
                            <?php 
                            // Render ảnh từ server giữ lại sau khi bấm Submit
                            if ($is_submitted && count($uploaded_files) > 0) {
                                foreach ($uploaded_files as $file) {
                                    $ext = strtolower(pathinfo($file['path'], PATHINFO_EXTENSION));
                                    echo '<div class="relative rounded-lg overflow-hidden border border-gray-200 shadow-sm aspect-[4/3] bg-gray-100">';
                                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                        echo '<img src="' . htmlspecialchars($file['path']) . '" class="w-full h-full object-contain" alt="Tài liệu">';
                                    } else {
                                        echo '<div class="w-full h-full flex flex-col items-center justify-center p-2"><i class="fa-solid fa-file-pdf text-3xl text-red-500 mb-2"></i><span class="text-xs text-gray-500 text-center truncate w-full">'.htmlspecialchars($file['original_name']).'</span></div>';
                                    }
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>

                        <!-- Nút Bắt đầu phân tích AI -->
                        <button type="submit" class="w-full mt-auto py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center gap-2 font-bold text-base transition shadow-sm mb-4">
                            <i class="fa-solid fa-wand-magic-sparkles text-yellow-300"></i> Bắt đầu kiểm tra AI
                        </button>

                        <!-- Cam kết bảo mật -->
                        <div class="text-center text-xs text-gray-500 flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-lock text-gray-400"></i> Hình ảnh của bạn được mã hóa và xóa ngay sau khi kiểm tra.
                        </div>
                    </form>
                </div>

                <!-- Cột Phải: Hiển thị kết quả AI (Chiếm 7 cột) -->
                <div class="lg:col-span-7 flex flex-col gap-6">
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 min-h-[500px]">
                        <?php if (!$is_submitted): ?>
                            <!-- Trạng thái trống khi chưa Submit -->
                            <div class="h-full flex flex-col items-center justify-center text-gray-400 mt-20">
                                <i class="fa-solid fa-microchip text-6xl mb-4 opacity-50"></i>
                                <h3 class="text-lg font-medium text-gray-600">Chưa có dữ liệu kiểm tra</h3>
                                <p class="text-sm text-gray-400 text-center mt-2 max-w-sm">
                                    Vui lòng tải giấy tờ lên ở cột bên trái và bấm "Bắt đầu kiểm tra AI" để xem kết quả phân tích.
                                </p>
                            </div>
                        <?php else: ?>
                            <!-- Trạng thái đã Submit & Có kết quả -->
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-lg font-bold text-gray-900">2. Kết quả kiểm tra từ hệ thống AI</h2>
                                
                                <?php if (count($ai_results['errors']) > 0): ?>
                                    <div class="bg-red-50 px-3 py-1.5 rounded-lg border border-red-100 flex items-center gap-2 shadow-sm">
                                        <i class="fa-regular fa-circle-xmark text-red-600 font-bold"></i>
                                        <div class="flex flex-col">
                                            <span class="text-red-600 font-bold text-sm leading-tight">Chưa đạt</span>
                                            <span class="text-[10px] text-red-500">Hồ sơ có nguy cơ bị trả lại</span>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="bg-green-50 px-3 py-1.5 rounded-lg border border-green-100 flex items-center gap-2 shadow-sm">
                                        <i class="fa-regular fa-circle-check text-green-600 font-bold"></i>
                                        <div class="flex flex-col">
                                            <span class="text-green-600 font-bold text-sm leading-tight">Đạt yêu cầu</span>
                                            <span class="text-[10px] text-green-500">Hồ sơ đủ điều kiện nộp</span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- 4 Hộp Thống Kê Tổng Quan -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                                <!-- Lỗi -->
                                <div class="border border-red-200 bg-red-50/30 rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm">
                                    <div class="flex items-center gap-2 text-red-600 mb-1">
                                        <i class="fa-regular fa-circle-xmark text-xl"></i>
                                        <span class="text-2xl font-bold"><?php echo count($ai_results['errors']); ?></span>
                                    </div>
                                    <span class="text-sm text-gray-700 font-medium">Lỗi</span>
                                </div>
                                <!-- Cảnh báo -->
                                <div class="border border-orange-200 bg-orange-50/30 rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm">
                                    <div class="flex items-center gap-2 text-orange-500 mb-1">
                                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                                        <span class="text-2xl font-bold"><?php echo count($ai_results['warnings']); ?></span>
                                    </div>
                                    <span class="text-sm text-gray-700 font-medium">Cảnh báo</span>
                                </div>
                                <!-- Đạt -->
                                <div class="border border-green-200 bg-green-50/30 rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm">
                                    <div class="flex items-center gap-2 text-green-600 mb-1">
                                        <i class="fa-regular fa-circle-check text-xl"></i>
                                        <span class="text-2xl font-bold"><?php echo count($ai_results['passed']); ?></span>
                                    </div>
                                    <span class="text-sm text-gray-700 font-medium">Tiêu chí đạt</span>
                                </div>
                                <!-- Độ tin cậy -->
                                <div class="border border-blue-200 bg-blue-50 rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm">
                                    <div class="flex items-center gap-2 text-blue-600 mb-1">
                                        <i class="fa-solid fa-shield-halved text-xl"></i>
                                        <span class="text-2xl font-bold"><?php echo $ai_results['confidence']; ?>%</span>
                                    </div>
                                    <span class="text-sm text-gray-700 font-medium">Độ tin cậy OCR</span>
                                </div>
                            </div>

                            <!-- Danh sách chi tiết -->
                            <div>
                                <h3 class="text-base font-bold text-gray-900 mb-4">Chi tiết kết quả phân tích</h3>
                                <div class="space-y-3">
                                    
                                    <!-- Vòng lặp hiển thị Lỗi (Đỏ) -->
                                    <?php foreach ($ai_results['errors'] as $error): ?>
                                    <div class="border border-red-200 bg-white hover:bg-red-50/50 transition rounded-lg p-4 flex gap-4">
                                        <div class="mt-0.5 text-red-600">
                                            <i class="fa-solid fa-circle-xmark text-lg"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-bold text-gray-800 text-sm mb-1"><?php echo htmlspecialchars($error['title']); ?></div>
                                            <div class="text-xs text-gray-600 leading-relaxed">
                                                <?php echo htmlspecialchars($error['desc']); ?>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="bg-red-100 text-red-700 text-[11px] px-2.5 py-1 rounded font-semibold whitespace-nowrap">Lỗi</span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>

                                    <!-- Vòng lặp hiển thị Cảnh báo (Cam) -->
                                    <?php foreach ($ai_results['warnings'] as $warning): ?>
                                    <div class="border border-orange-200 bg-white hover:bg-orange-50/50 transition rounded-lg p-4 flex gap-4">
                                        <div class="mt-0.5 text-orange-500">
                                            <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-bold text-gray-800 text-sm mb-1"><?php echo htmlspecialchars($warning['title']); ?></div>
                                            <div class="text-xs text-gray-600 leading-relaxed">
                                                <?php echo htmlspecialchars($warning['desc']); ?>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="bg-orange-100 text-orange-700 text-[11px] px-2.5 py-1 rounded font-semibold whitespace-nowrap">Cảnh báo</span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>

                                    <!-- Vòng lặp hiển thị Tiêu chí Đạt (Xanh lá) -->
                                    <?php foreach ($ai_results['passed'] as $pass): ?>
                                    <div class="border border-green-200 bg-white hover:bg-green-50/50 transition rounded-lg p-4 flex gap-4">
                                        <div class="mt-0.5 text-green-600">
                                            <i class="fa-solid fa-circle-check text-lg"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-bold text-gray-800 text-sm mb-1"><?php echo htmlspecialchars($pass['title']); ?></div>
                                            <div class="text-xs text-gray-600 leading-relaxed">
                                                <?php echo htmlspecialchars($pass['desc']); ?>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="bg-green-100 text-green-700 text-[11px] px-2.5 py-1 rounded font-semibold whitespace-nowrap">Đạt</span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        <?php endif; ?>
                    </div> <!-- End Results Card -->

                </div> <!-- End Cột Phải -->
            </div> <!-- End Grid -->

            <?php if ($is_submitted && count($ai_results['errors']) > 0): ?>
            <!-- Khối Cảnh báo và Đề xuất AI hiện ra khi có lỗi -->
            <div class="mt-6 bg-red-50 border border-red-200 rounded-xl p-6 flex flex-col xl:flex-row gap-6 items-start xl:items-center justify-between shadow-sm">
                <!-- Bên trái: Alert Box -->
                <div class="flex gap-4 xl:w-1/3">
                    <div class="w-12 h-12 bg-red-600 text-white rounded-full flex items-center justify-center text-xl shrink-0 shadow-sm">
                        <i class="fa-solid fa-exclamation"></i>
                    </div>
                    <div>
                        <h3 class="text-red-700 font-bold text-lg mb-1">Hồ sơ có nguy cơ bị yêu cầu bổ sung</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">Dựa trên kết quả kiểm tra, hệ thống AI đánh giá hồ sơ của bạn có thể bị từ chối do các lỗi ở trên.</p>
                    </div>
                </div>

                <!-- Đường kẻ mờ phân cách -->
                <div class="hidden xl:block w-px h-20 bg-red-200 shrink-0"></div>

                <!-- Ở giữa: Đề xuất -->
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-gray-900 mb-2">Đề xuất của AI</h4>
                    <ul class="text-sm text-gray-600 space-y-1.5 list-disc list-inside marker:text-red-500">
                        <li>Đảm bảo ảnh chụp/scan đủ ánh sáng, không bị mờ nhòe.</li>
                        <li>Kiểm tra lại tính đồng nhất của họ tên trên các văn bản.</li>
                        <li>Vùng chữ ký và dấu mộc cần hiển thị rõ ràng trên mặt giấy.</li>
                    </ul>
                </div>

                <!-- Bên phải: Nút action -->
                <div class="shrink-0 mt-2 xl:mt-0 w-full xl:w-auto flex flex-col gap-2">
                    <a href="camera_ai.php" class="w-full xl:w-auto px-6 py-2.5 border-2 border-blue-600 text-blue-700 bg-white hover:bg-blue-50 rounded-lg flex items-center justify-center gap-2 text-sm font-bold transition shadow-sm">
                        <i class="fa-solid fa-arrow-rotate-right"></i> Tải lại giấy tờ khác
                    </a>
                </div>
            </div>
            <?php endif; ?>

        </main>
    </div>

    <!-- Script Javascript xử lý Preview Ảnh trực tiếp khi vừa chọn (Client-side) -->
    <script>
        document.getElementById('file-input').addEventListener('change', function(e) {
            const files = e.target.files;
            const previewZone = document.getElementById('file-preview-zone');
            const countText = document.getElementById('file-count-text');
            const imagePreviewContainer = document.getElementById('image-preview-container');
            
            // Xóa các ảnh cũ (do PHP render hoặc lần chọn trước) để hiển thị ảnh mới chọn
            imagePreviewContainer.innerHTML = '';

            if (files.length > 0) {
                countText.textContent = `Đã chọn ${files.length} file (Hợp lệ)`;
                previewZone.classList.remove('hidden');

                // Lặp qua từng file để tạo HTML preview
                Array.from(files).forEach(file => {
                    // Tạo khung chứa ảnh
                    const colDiv = document.createElement('div');
                    colDiv.className = 'relative rounded-lg overflow-hidden border border-gray-200 shadow-sm aspect-[4/3] bg-gray-100';

                    if (file.type.startsWith('image/')) {
                        // Nếu là ảnh: Dùng URL.createObjectURL để đọc file trực tiếp trên trình duyệt
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.className = 'w-full h-full object-contain';
                        
                        // Giải phóng bộ nhớ sau khi ảnh đã load xong
                        img.onload = () => URL.revokeObjectURL(img.src);
                        colDiv.appendChild(img);
                    } else if (file.type === 'application/pdf') {
                        // Nếu là PDF: Hiển thị icon
                        colDiv.innerHTML = `<div class="w-full h-full flex flex-col items-center justify-center p-2">
                                                <i class="fa-solid fa-file-pdf text-3xl text-red-500 mb-2"></i>
                                                <span class="text-xs text-gray-500 text-center truncate w-full">${file.name}</span>
                                            </div>`;
                    }
                    
                    // Nhét khung vừa tạo vào Container
                    imagePreviewContainer.appendChild(colDiv);
                });

            } else {
                previewZone.classList.add('hidden');
            }
        });
    </script>
</body>
</html>