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

    <?php include 'component/header.php'; ?>

    <div class="flex flex-1 overflow-hidden">
        
        <?php 
            $base_path = './';
            $active_page = 'home';
            include 'component/sidebar.php'; 
        ?>

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

                        <!-- Chèn ô dịch vụ AI này vào trong thẻ grid dịch vụ công trực tuyến của bạn -->
<div onclick="openAiModal()" class="block h-full cursor-pointer">
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50/50 rounded-xl p-4 flex flex-col items-center justify-center text-center hover:from-blue-100 hover:to-indigo-100 border border-blue-200/60 hover:border-blue-300 transition group relative overflow-hidden h-full shadow-sm shadow-blue-500/5">
        <div class="absolute top-0 left-0 bg-blue-600 text-white text-[8px] font-bold px-2 py-0.5 rounded-br-lg tracking-wider">CÔNG NGHỆ AI</div>
        <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform shadow-md shadow-blue-600/20">
            <i class="fa-solid fa-robot"></i>
        </div>
        <h3 class="font-bold text-blue-900 text-sm mb-1 leading-tight">
            Nộp hồ sơ<br>Bằng ảnh chụp
        </h3>
        <p class="text-[10px] text-blue-500 font-medium">Tự động quét & điền form</p>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL HỘP THOẠI AI (Đặt đoạn này ở cuối cùng, ngay trước thẻ đóng </body>) -->
<!-- ========================================================================= -->
<div id="ai-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <!-- Lớp nền mờ -->
    <div onclick="closeAiModal()" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
    
    <!-- Nội dung Modal -->
    <div class="bg-white rounded-2xl w-full max-w-xl mx-4 relative z-10 shadow-2xl flex flex-col max-h-[85vh] transition-all transform scale-95 duration-200" id="modal-content">
        <!-- Header -->
        <div class="px-6 py-4 border-b flex items-center justify-between bg-gray-50 rounded-t-2xl">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-sm">Trợ lý Số hóa Biểu mẫu AI</h3>
                    <p class="text-[11px] text-gray-500">Tự động nhận diện và điền thông tin công dân</p>
                </div>
            </div>
            <button onclick="closeAiModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-200/50 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Khung nội dung có thanh cuộn độc lập -->
        <div class="p-6 overflow-y-auto flex-1">
    <!-- Thẻ input file ẩn dùng chung cho hệ thống -->
    <input type="file" id="file-input" accept="image/*" class="hidden">
    
    <!-- THANH CHUYỂN TAB GIAO DIỆN -->
    <div class="flex border-b border-gray-200 mb-4 text-xs font-semibold">
        <button type="button" onclick="switchAiTab('scan')" id="tab-btn-scan" class="flex-1 py-2 text-blue-600 border-b-2 border-blue-600 text-center transition focus:outline-none">
            <i class="fa-solid fa-camera mr-1"></i> Số hóa biểu mẫu mới
        </button>
        <button type="button" onclick="switchAiTab('saved')" id="tab-btn-saved" class="flex-1 py-2 text-gray-500 hover:text-gray-700 text-center transition focus:outline-none">
            <i class="fa-solid fa-folder-open mr-1"></i> Biểu mẫu đã lưu của tôi
        </button>
    </div>

    <!-- NỘI DUNG TAB 1: Quét ảnh mới -->
    <div id="tab-content-scan" class="space-y-4">
        <div id="upload-zone" class="border-2 border-dashed border-blue-200 bg-blue-50/10 rounded-xl p-8 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50/40 transition">
            <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-xl mx-auto mb-3 border border-blue-100">
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </div>
            <p class="text-sm font-semibold text-gray-700">Chụp hoặc tải ảnh biểu mẫu lên</p>
            <p class="text-xs text-gray-400 mt-1">Hệ thống hỗ trợ ảnh chụp đơn, phôi giấy tờ chưa có sẵn mẫu điện tử</p>
        </div>

        <div id="loading-zone" class="hidden py-12 text-center">
            <div class="inline-block relative w-12 h-12 mb-4">
                <div class="absolute inset-0 rounded-full border-4 border-blue-200 animate-ping opacity-25"></div>
                <div class="absolute inset-0 rounded-full border-4 border-blue-600 border-t-transparent animate-spin"></div>
            </div>
            <p class="text-sm font-semibold text-gray-800">AI đang đọc hiểu cấu trúc biểu mẫu...</p>
            <p class="text-xs text-gray-400 mt-1">Trích xuất các ô trống và tự động điền thông tin của bạn</p>
        </div>
    </div>

    <!-- NỘI DUNG TAB 2: Danh sách các đơn đã lưu để dùng lại nhanh -->
    <div id="tab-content-saved" class="hidden space-y-3">
        <p class="text-xs text-gray-500 mb-2">Chọn một biểu mẫu bạn đã từng số hóa trước đây để điền nhanh không cần chụp ảnh lại:</p>
        
        <div onclick="taiLaiFormCu('Đơn đăng ký học lái xe ô tô')" class="flex items-center justify-between p-3.5 bg-gray-50 hover:bg-blue-50 border border-gray-200 rounded-xl cursor-pointer transition group">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-file-signature text-blue-500 text-base group-hover:scale-110 transition-transform"></i>
                <span class="text-sm font-semibold text-gray-700">Đơn đăng ký học lái xe ô tô</span>
            </div>
            <i class="fa-solid fa-chevron-right text-xs text-gray-400 group-hover:text-blue-500 transition-colors"></i>
        </div>

        <div onclick="taiLaiFormCu('Đơn xin nghỉ phép')" class="flex items-center justify-between p-3.5 bg-gray-50 hover:bg-blue-50 border border-gray-200 rounded-xl cursor-pointer transition group">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-file-prescription text-blue-500 text-base group-hover:scale-110 transition-transform"></i>
                <span class="text-sm font-semibold text-gray-700">Đơn xin nghỉ phép</span>
            </div>
            <i class="fa-solid fa-chevron-right text-xs text-gray-400 group-hover:text-blue-500 transition-colors"></i>
        </div>
    </div>

    <!-- VÙNG CHUNG: Form động sinh ra bởi AI -->
    <div id="dynamic-form-zone" class="hidden space-y-4">
        <div class="bg-amber-50 border border-amber-200/60 rounded-xl p-3 flex gap-2.5 items-start mb-4">
            <i class="fa-solid fa-circle-info text-amber-500 text-sm mt-0.5"></i>
            <p class="text-xs text-amber-800 leading-normal">
                Mẫu đơn dưới đây được tạo tự động bởi AI. Những ô tô đậm màu xanh đã được **tự động điền (Auto-fill)** bằng dữ liệu của công dân **Nguyễn Văn A**. Vui lòng kiểm tra lại trước khi nộp.
            </p>
        </div>
        <h4 id="detected-form-title" class="text-sm font-bold text-blue-900 bg-blue-50/50 px-3 py-2 rounded-lg inline-block"></h4>
        <form id="ai-generated-form" class="grid grid-cols-1 gap-4 pt-2">
            <!-- JavaScript kết xuất input nằm ở phần sau -->
        </form>
    </div>
</div>


        <!-- Footer điều khiển hành động -->
        <div class="px-6 py-3.5 border-t bg-gray-50 flex justify-end gap-2.5 rounded-b-2xl">
            <button onclick="closeAiModal()" class="px-4 py-2 border border-gray-200 bg-white rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 active:scale-95 transition">Hủy bỏ</button>
            <button id="submit-form-btn" disabled class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 shadow-sm opacity-50 cursor-not-allowed active:scale-95 transition">Xác nhận nộp hồ sơ</button>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- JAVASCRIPT XỬ LÝ SỰ KIỆN MODAL VÀ FORM ĐỘNG -->
<!-- ========================================================================= -->
<script>
// Khai báo các phần tử giao diện dùng chung
const aiModal = document.getElementById('ai-modal');
const modalContent = document.getElementById('modal-content');
const uploadZone = document.getElementById('upload-zone');
const loadingZone = document.getElementById('loading-zone');
const dynamicFormZone = document.getElementById('dynamic-form-zone');
const fileInput = document.getElementById('file-input');
const formContainer = document.getElementById('ai-generated-form');
const submitBtn = document.getElementById('submit-form-btn');
const tabContentScan = document.getElementById('tab-content-scan');
const tabContentSaved = document.getElementById('tab-content-saved');

// Hàm mở hộp thoại trợ lý AI
function openAiModal() {
    aiModal.classList.remove('hidden');
    setTimeout(() => modalContent.classList.remove('scale-95'), 10);
    resetModalState();
}

// Hàm đóng hộp thoại trợ lý AI
function closeAiModal() {
    modalContent.classList.add('scale-95');
    setTimeout(() => aiModal.classList.add('hidden'), 150);
}

// Thiết lập lại trạng thái ban đầu cho Modal khi mở lên
function resetModalState() {
    switchAiTab('scan'); 
    uploadZone.classList.remove('hidden');
    loadingZone.classList.add('hidden');
    dynamicFormZone.classList.add('hidden');
    if (fileInput) fileInput.value = '';
    if (formContainer) formContainer.innerHTML = '';
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerText = "Xác nhận nộp hồ sơ";
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

// Sự kiện click vào vùng nét đứt để mở cửa sổ chọn file ảnh của thiết bị
if (uploadZone && fileInput) {
    uploadZone.onclick = (e) => {
        e.preventDefault();
        fileInput.click();
    };
}

// Hàm xử lý chuyển đổi giao diện qua lại giữa các Tab
function switchAiTab(tabName) {
    const btnScan = document.getElementById('tab-btn-scan');
    const btnSaved = document.getElementById('tab-btn-saved');
    
    // Nếu form động đang hiển thị, ẩn tạm đi để người dùng đổi thao tác mẫu
    if (dynamicFormZone) dynamicFormZone.classList.add('hidden');

    if (tabName === 'scan') {
        if (btnScan) btnScan.className = "flex-1 py-2 text-blue-600 border-b-2 border-blue-600 text-center transition focus:outline-none";
        if (btnSaved) btnSaved.className = "flex-1 py-2 text-gray-500 hover:text-gray-700 text-center transition focus:outline-none";
        if (tabContentScan) tabContentScan.classList.remove('hidden');
        if (uploadZone) uploadZone.classList.remove('hidden');
        if (tabContentSaved) tabContentSaved.classList.add('hidden');
    } else {
        if (btnScan) btnScan.className = "flex-1 py-2 text-gray-500 hover:text-gray-700 text-center transition focus:outline-none";
        if (btnSaved) btnSaved.className = "flex-1 py-2 text-blue-600 border-b-2 border-blue-600 text-center transition focus:outline-none";
        if (tabContentScan) tabContentScan.classList.add('hidden');
        if (tabContentSaved) tabContentSaved.classList.remove('hidden');
    }
}

// Lắng nghe sự kiện khi người dùng chọn xong file ảnh từ thiết bị
if (fileInput) {
    fileInput.onchange = async (e) => {
        const file = e.target.files[0]; // Chỉ định lấy tệp tin đầu tiên
        if (!file) return;

        // Ẩn khung chọn ảnh và hiển thị màn hình chờ xoay tròn của AI
        if (uploadZone) uploadZone.classList.add('hidden');
        if (loadingZone) loadingZone.classList.remove('hidden');

        const formData = new FormData();
        formData.append('form_image', file);

        try {
            // Gửi tệp ảnh sang file backend xử lý Google Gemini API
            const response = await fetch('process_ai_form.php', { method: 'POST', body: formData });
            const result = await response.json();

            if (loadingZone) loadingZone.classList.add('hidden');

            if (result.success) {
                renderAiForm(result.data); // Vẽ form động lên màn hình
            } else {
                alert('Có lỗi xảy ra từ máy chủ AI: ' + result.message);
                resetModalState();
            }
        } catch (error) {
            alert('Không thể kết nối đến máy chủ xử lý AI.');
            resetModalState();
        }
    };
}

// Hàm tiếp nhận dữ liệu JSON cấu trúc của đơn và kết xuất thành các ô Input thực tế
function renderAiForm(data) {
    if (tabContentScan) tabContentScan.classList.add('hidden');
    if (tabContentSaved) tabContentSaved.classList.add('hidden');
    if (dynamicFormZone) dynamicFormZone.classList.remove('hidden');
    
    document.getElementById('detected-form-title').innerText = "📄 Loại đơn: " + data.form_name;
    formContainer.innerHTML = ''; // Làm sạch các trường đơn cũ trước khi vẽ
    
    data.fields.forEach(field => {
        const fieldWrapper = document.createElement('div');
        fieldWrapper.className = 'flex flex-col gap-1.5';
        
        // Kiểm tra xem ô này có dữ liệu tự điền (Auto-fill) hay không
        let hasValue = field.suggested_value && field.suggested_value.trim() !== '';
        // Nếu có dữ liệu điền sẵn, nhuộm màu nền xanh để công dân nhận biết hệ thống hỗ trợ
        let inputBgClass = hasValue 
            ? 'bg-blue-50/70 border-blue-200 text-blue-900 font-medium focus:bg-white focus:border-blue-500' 
            : 'bg-gray-50 border-gray-200 text-gray-800 focus:bg-white focus:border-blue-500';

        fieldWrapper.innerHTML = `
            <label class="text-[11px] font-bold text-gray-600 uppercase tracking-wide">${field.label}</label>
            <input type="${field.type}" 
                   name="${field.id}" 
                   value="${field.suggested_value || ''}" 
                   class="w-full ${inputBgClass} border rounded-xl px-3.5 py-2.5 text-sm focus:outline-none shadow-sm transition">
        `;
        formContainer.appendChild(fieldWrapper);
    });

    // Mở khóa kích hoạt nút bấm xác nhận gửi đơn lên cơ quan
    if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

// Hàm giả lập lấy phôi cũ từ hệ thống dữ liệu đã lưu ở Tab 2 không tốn tiền quét lại ảnh
function taiLaiFormCu(tenForm) {
    const mockSavedData = {
        form_name: tenForm,
        fields: [
            { id: "full_name", label: "Họ và tên công dân nộp đơn", type: "text", suggested_value: "Nguyễn Văn A" },
            { id: "dob", label: "Ngày tháng năm sinh", type: "text", suggested_value: "15/10/1995" },
            { id: "identity_number", label: "Số thẻ CCCD/Hộ chiếu", type: "text", suggested_value: "012345678901" },
            { id: "reason", label: "Lý do nộp đơn hoặc nội dung đề xuất", type: "text", suggested_value: "" }
        ]
    };
    renderAiForm(mockSavedData); 
}

// Xử lý sự kiện bấm xác nhận để đóng gói form đẩy ngầm sang MySQL
if (submitBtn) {
    submitBtn.onclick = async () => {
        const formData = new FormData(formContainer);
        const formFieldsData = Object.fromEntries(formData.entries());
        const formName = document.getElementById('detected-form-title').innerText.replace("📄 Loại đơn: ", "");

        submitBtn.disabled = true;
        submitBtn.innerText = "Đang lưu hồ sơ vào Database...";

        try {
            // Đẩy dữ liệu JSON cấu trúc sang file xử lý kết nối MySQL của bạn
            const response = await fetch('save_ai_form.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    form_name: formName,
                    fields_data: formFieldsData
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert('🎉 Chúc mừng! Hồ sơ số hóa bằng AI của bạn đã được lưu vào bảng ho_so_ai thành công.');
                closeAiModal();
                window.location.reload(); // Tải lại trang chủ
            } else {
                alert('Lỗi lưu trữ dữ liệu: ' + result.message);
                submitBtn.disabled = false;
                submitBtn.innerText = "Xác nhận nộp hồ sơ";
            }
        } catch (error) {
            alert('Không thể kết nối đến máy chủ lưu trữ dữ liệu MySQL.');
            submitBtn.disabled = false;
            submitBtn.innerText = "Xác nhận nộp hồ sơ";
        }
    };
}

// Hàm mở hộp thoại trợ lý AI
function openAiModal() {
    aiModal.classList.remove('hidden');
    setTimeout(() => modalContent.classList.remove('scale-95'), 10);
    resetModalState();
}

// Hàm đóng hộp thoại trợ lý AI
function closeAiModal() {
    modalContent.classList.add('scale-95');
    setTimeout(() => aiModal.classList.add('hidden'), 150);
}

// Thiết lập lại trạng thái ban đầu cho Modal khi mở lên
function resetModalState() {
    switchAiTab('scan'); 
    uploadZone.classList.remove('hidden');
    loadingZone.classList.add('hidden');
    dynamicFormZone.classList.add('hidden');
    if (fileInput) fileInput.value = '';
    if (formContainer) formContainer.innerHTML = '';
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerText = "Xác nhận nộp hồ sơ";
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

// Sự kiện click vào vùng nét đứt để mở cửa sổ chọn file ảnh của thiết bị
if (uploadZone && fileInput) {
    uploadZone.onclick = (e) => {
        e.preventDefault();
        fileInput.click();
    };
}

// Hàm xử lý chuyển đổi giao diện qua lại giữa các Tab
function switchAiTab(tabName) {
    const btnScan = document.getElementById('tab-btn-scan');
    const btnSaved = document.getElementById('tab-btn-saved');
    
    // Nếu form động đang hiển thị, ẩn tạm đi để người dùng đổi thao tác mẫu
    if (dynamicFormZone) dynamicFormZone.classList.add('hidden');

    if (tabName === 'scan') {
        if (btnScan) btnScan.className = "flex-1 py-2 text-blue-600 border-b-2 border-blue-600 text-center transition focus:outline-none";
        if (btnSaved) btnSaved.className = "flex-1 py-2 text-gray-500 hover:text-gray-700 text-center transition focus:outline-none";
        if (tabContentScan) tabContentScan.classList.remove('hidden');
        if (uploadZone) uploadZone.classList.remove('hidden');
        if (tabContentSaved) tabContentSaved.classList.add('hidden');
    } else {
        if (btnScan) btnScan.className = "flex-1 py-2 text-gray-500 hover:text-gray-700 text-center transition focus:outline-none";
        if (btnSaved) btnSaved.className = "flex-1 py-2 text-blue-600 border-b-2 border-blue-600 text-center transition focus:outline-none";
        if (tabContentScan) tabContentScan.classList.add('hidden');
        if (tabContentSaved) tabContentSaved.classList.remove('hidden');
    }
}

// Lắng nghe sự kiện khi người dùng chọn xong file ảnh từ thiết bị
if (fileInput) {
    fileInput.onchange = async (e) => {
        const file = e.target.files[0]; // Chỉ định lấy tệp tin đầu tiên
        if (!file) return;

        // Ẩn khung chọn ảnh và hiển thị màn hình chờ xoay tròn của AI
        if (uploadZone) uploadZone.classList.add('hidden');
        if (loadingZone) loadingZone.classList.remove('hidden');

        const formData = new FormData();
        formData.append('form_image', file);

        try {
            // Gửi tệp ảnh sang file backend xử lý Google Gemini API
            const response = await fetch('process_ai_form.php', { method: 'POST', body: formData });
            const result = await response.json();

            if (loadingZone) loadingZone.classList.add('hidden');

            if (result.success) {
                renderAiForm(result.data); // Vẽ form động lên màn hình
            } else {
                alert('Có lỗi xảy ra từ máy chủ AI: ' + result.message);
                resetModalState();
            }
        } catch (error) {
            alert('Không thể kết nối đến máy chủ xử lý AI.');
            resetModalState();
        }
    };
}

// Hàm tiếp nhận dữ liệu JSON cấu trúc của đơn và kết xuất thành các ô Input thực tế
function renderAiForm(data) {
    if (tabContentScan) tabContentScan.classList.add('hidden');
    if (tabContentSaved) tabContentSaved.classList.add('hidden');
    if (dynamicFormZone) dynamicFormZone.classList.remove('hidden');
    
    document.getElementById('detected-form-title').innerText = "📄 Loại đơn: " + data.form_name;
    formContainer.innerHTML = ''; // Làm sạch các trường đơn cũ trước khi vẽ
    
    data.fields.forEach(field => {
        const fieldWrapper = document.createElement('div');
        fieldWrapper.className = 'flex flex-col gap-1.5';
        
        // Kiểm tra xem ô này có dữ liệu tự điền (Auto-fill) hay không
        let hasValue = field.suggested_value && field.suggested_value.trim() !== '';
        // Nếu có dữ liệu điền sẵn, nhuộm màu nền xanh để công dân nhận biết hệ thống hỗ trợ
        let inputBgClass = hasValue 
            ? 'bg-blue-50/70 border-blue-200 text-blue-900 font-medium focus:bg-white focus:border-blue-500' 
            : 'bg-gray-50 border-gray-200 text-gray-800 focus:bg-white focus:border-blue-500';

        fieldWrapper.innerHTML = `
            <label class="text-[11px] font-bold text-gray-600 uppercase tracking-wide">${field.label}</label>
            <input type="${field.type}" 
                   name="${field.id}" 
                   value="${field.suggested_value || ''}" 
                   class="w-full ${inputBgClass} border rounded-xl px-3.5 py-2.5 text-sm focus:outline-none shadow-sm transition">
        `;
        formContainer.appendChild(fieldWrapper);
    });

    // Mở khóa kích hoạt nút bấm xác nhận gửi đơn lên cơ quan
    if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

// Hàm giả lập lấy phôi cũ từ hệ thống dữ liệu đã lưu ở Tab 2 không tốn tiền quét lại ảnh
// --- THAY THẾ HOÀN TOÀN HÀM taiLaiFormCu CŨ TRONG FILE index.php BẰNG ĐOẠN NÀY ---
async function taiLaiFormCu(tenForm) {
    // Hiển thị trạng thái chờ trên tab hoặc chuyển sang giao diện loading nếu cần
    document.getElementById('tab-content-scan').classList.add('hidden');
    document.getElementById('tab-content-saved').classList.add('hidden');
    
    try {
        // Gọi API lấy phôi động được sinh tự động từ database MySQL
        const response = await fetch(`get_saved_template.php?form_name=${encodeURIComponent(tenForm)}`);
        const result = await response.json();
        
        if (result.success) {
            // Vẽ lại chính xác 100% tất cả các ô dữ liệu cũ do AI lưu
            renderAiForm(result.data); 
        } else {
            alert('Thông báo: ' + result.message);
            resetModalState();
        }
    } catch (error) {
        alert('Không thể kết nối đến máy chủ lấy cấu trúc biểu mẫu.');
        resetModalState();
    }
}


// Xử lý sự kiện bấm xác nhận để đóng gói form đẩy ngầm sang MySQL
if (submitBtn) {
    submitBtn.onclick = async () => {
        const formData = new FormData(formContainer);
        const formFieldsData = Object.fromEntries(formData.entries());
        const formName = document.getElementById('detected-form-title').innerText.replace("📄 Loại đơn: ", "");

        submitBtn.disabled = true;
        submitBtn.innerText = "Đang lưu hồ sơ vào Database...";

        try {
            // Đẩy dữ liệu JSON cấu trúc sang file xử lý kết nối MySQL của bạn
            const response = await fetch('save_ai_form.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    form_name: formName,
                    fields_data: formFieldsData
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert('🎉 Chúc mừng! Hồ sơ số hóa bằng AI của bạn đã được lưu vào bảng ho_so_ai thành công.');
                closeAiModal();
                window.location.reload(); // Tải lại trang chủ
            } else {
                alert('Lỗi lưu trữ dữ liệu: ' + result.message);
                submitBtn.disabled = false;
                submitBtn.innerText = "Xác nhận nộp hồ sơ";
            }
        } catch (error) {
            alert('Không thể kết nối đến máy chủ lưu trữ dữ liệu MySQL.');
            submitBtn.disabled = false;
            submitBtn.innerText = "Xác nhận nộp hồ sơ";
        }
    };
}

</script>


    <!-- Cấp đổi CCCD -->
    <a href="./thu_tuc/cap_doi_cccd.php" class="block h-full">
        <div class="bg-gray-50 rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-blue-50 border border-transparent hover:border-blue-100 transition group h-full">
            <div class="w-12 h-12 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-id-card"></i>
            </div>
            <h3 class="font-semibold text-gray-800 text-sm mb-1 leading-tight">
                Cấp đổi<br>CCCD
            </h3>
            <p class="text-[10px] text-gray-400">432.104 Hồ sơ đã tiếp nhận</p>
        </div>
    </a>

    <!-- Đăng ký cư trú -->
    <a href="./thu_tuc/dang_ky_cu_tru.php" class="block h-full">
        <div class="bg-gray-50 rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-pink-50 border border-transparent hover:border-pink-100 transition group h-full">
            <div class="w-12 h-12 bg-pink-100 text-pink-500 rounded-full flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-ring"></i>
            </div>
            <h3 class="font-semibold text-gray-800 text-sm mb-1 leading-tight">
                Đăng ký<br>cư trú
            </h3>
            <p class="text-[10px] text-gray-400">128.657 Hồ sơ đã tiếp nhận</p>
        </div>
    </a>

    <!-- Đăng ký khai sinh -->
    <a href="./thu_tuc/dang_ky_khai_sinh.php" class="block h-full">
        <div class="bg-green-50/50 rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-green-50 border border-transparent hover:border-green-200 transition group relative overflow-hidden h-full">
            <div class="absolute top-0 right-0 bg-green-500 text-white text-[8px] font-bold px-2 py-0.5 rounded-bl-lg">HOT</div>
            <div class="w-12 h-12 bg-green-100 text-green-500 rounded-full flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-baby"></i>
            </div>
            <h3 class="font-semibold text-gray-800 text-sm mb-1 leading-tight">
                Đăng ký<br>khai sinh
            </h3>
            <p class="text-[10px] text-gray-400">312.029 Hồ sơ đã tiếp nhận</p>
        </div>
    </a>

    <!-- Cấp hộ chiếu -->
    <a href="./thu_tuc/cap_ho_chieu.php" class="block h-full">
        <div class="bg-gray-50 rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-orange-50 border border-transparent hover:border-orange-100 transition group h-full">
            <div class="w-12 h-12 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-car"></i>
            </div>
            <h3 class="font-semibold text-gray-800 text-sm mb-1 leading-tight">
                Cấp Hộ chiếu<br>phổ thông
            </h3>
            <p class="text-[10px] text-gray-400">267.982 Hồ sơ đã tiếp nhận</p>
        </div>
    </a>

    <!-- Đăng ký kết hôn -->
    <a href="./thu_tuc/dang_ky_ket_hon.php" class="block h-full hidden sm:block">
        <div class="bg-gray-50 rounded-xl p-4 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-purple-50 border border-transparent hover:border-purple-100 transition group h-full">
            <div class="w-12 h-12 bg-purple-100 text-purple-500 rounded-full flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-house-chimney"></i>
            </div>
            <h3 class="font-semibold text-gray-800 text-sm mb-1 leading-tight">
                Đăng ký<br>kết hôn
            </h3>
            <p class="text-[10px] text-gray-400">89.341 Hồ sơ đã tiếp nhận</p>
        </div>
    </a>

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