<?php
// Gọi kết nối Database. Vì admin_dashboard.php ở root, đường dẫn là thư mục con /config
require_once __DIR__ . '/config/database.php';

try {
    // Truy vấn lấy toàn bộ hồ sơ, mới nhất xếp lên đầu
    $stmt = $pdo->query("SELECT * FROM ho_so ORDER BY created_at DESC");
    $danh_sach_ho_so = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Lỗi truy xuất dữ liệu: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cổng Dịch vụ công Quốc gia - Hệ thống xử lý & giám sát hồ sơ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#f8fafc] text-gray-800 h-screen flex flex-col overflow-hidden">
 
    <audio id="alarm-sound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-500.wav" preload="auto"></audio>
 
    <header class="bg-[#004a99] h-16 flex items-center justify-between px-6 text-white shrink-0 z-50 shadow-md">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-red-600 text-yellow-300 rounded-full flex items-center justify-center font-bold shadow">
                <i class="fa-solid fa-star text-xs"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-base leading-tight">Cổng Dịch vụ công Quốc gia</span>
                <span class="text-[10px] opacity-80 uppercase tracking-wider font-semibold text-yellow-400">Hệ thống xử lý và giám sát hồ sơ</span>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-xs bg-white/10 px-3 py-1.5 rounded-md border border-white/10 font-mono">
                <i class="fa-regular fa-calendar mr-1"></i> <span id="current-date">Thứ 6, 24/05/2024</span>
                <i class="fa-regular fa-clock ml-3 mr-1"></i> <span id="current-time">10:30:45</span>
            </div>
            <div class="relative cursor-pointer">
                <i class="fa-regular fa-bell text-white text-xl"></i>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-4 h-4 flex items-center justify-center rounded-full font-bold">12</span>
            </div>
            <div class="flex items-center gap-2">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" class="w-8 h-8 rounded-full object-cover border-2 border-white/20" alt="Avatar">
                <div class="flex flex-col">
                    <span class="font-medium text-sm leading-tight">Nguyễn Văn B</span>
                    <span class="text-[10px] opacity-70">Cán bộ tiếp nhận</span>
                </div>
            </div>
        </div>
    </header>
 
    <div class="flex flex-1 overflow-hidden">
        
        <aside class="w-64 bg-white border-r flex flex-col justify-between shrink-0 overflow-y-auto hidden md:flex">
            <nav class="p-4 space-y-6">
                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider px-4">Xử lý hồ sơ</span>
                    <div class="mt-2 space-y-1">
                        <a href="#" onclick="updateFilterState('all'); return false;" id="sidebar-all" class="sidebar-filter-btn flex items-center justify-between px-4 py-2.5 bg-blue-50 text-blue-600 font-medium rounded-lg text-sm transition-colors">
                            <span class="flex items-center gap-3"><i class="fa-solid fa-layer-group w-5 text-center"></i> Tất cả hồ sơ</span>
                        </a>
                        <a href="#" onclick="updateFilterState('cho_xu_ly'); return false;" id="sidebar-cho_xu_ly" class="sidebar-filter-btn flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg text-sm transition-colors">
                            <span class="flex items-center gap-3"><i class="fa-regular fa-clock w-5 text-center"></i> Hồ sơ chờ xử lý</span>
                            <span id="badge-cho_xu_ly" class="bg-red-100 text-red-600 font-bold text-[11px] px-2 py-0.5 rounded-full">0</span>
                        </a>
                        <a href="#" onclick="updateFilterState('dang_xu_ly'); return false;" id="sidebar-dang_xu_ly" class="sidebar-filter-btn flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg text-sm transition-colors">
                            <span class="flex items-center gap-3"><i class="fa-solid fa-spinner w-5 text-center"></i> Hồ sơ đang xử lý</span>
                            <span id="badge-dang_xu_ly" class="bg-blue-100 text-blue-600 font-bold text-[11px] px-2 py-0.5 rounded-full">0</span>
                        </a>
                        <a href="#" onclick="updateFilterState('qua_han'); return false;" id="sidebar-qua_han" class="sidebar-filter-btn flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg text-sm transition-colors">
                            <span class="flex items-center gap-3"><i class="fa-solid fa-triangle-exclamation w-5 text-center"></i> Hồ sơ quá hạn</span>
                            <span id="badge-qua_han" class="bg-rose-100 text-rose-600 font-bold text-[11px] px-2 py-0.5 rounded-full">0</span>
                        </a>
                        <a href="#" onclick="updateFilterState('da_hoan_thanh'); return false;" id="sidebar-da_hoan_thanh" class="sidebar-filter-btn flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg text-sm transition-colors">
                            <span class="flex items-center gap-3"><i class="fa-solid fa-square-check w-5 text-center"></i> Đã hoàn thành</span>
                            <span id="badge-da_hoan_thanh" class="bg-emerald-100 text-emerald-600 font-bold text-[11px] px-2 py-0.5 rounded-full hidden">0</span>
                        </a>
                    </div>
                </div>
 
                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider px-4">Báo cáo & Quản trị</span>
                    <div class="mt-2 space-y-1">
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg text-sm">
                            <i class="fa-solid fa-chart-line w-5 text-center"></i> Giám sát SLA
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg text-sm">
                            <i class="fa-solid fa-users-gear w-5 text-center"></i> Phân quyền cán bộ
                        </a>
                    </div>
                </div>
            </nav>
 
            <div class="p-4 border-t shrink-0">
                <a href="logout.php" class="w-full flex items-center gap-2 justify-center py-2.5 text-sm font-medium text-rose-600 hover:bg-rose-50 rounded-lg transition-colors border border-rose-100">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất hệ thống
                </a>
            </div>
        </aside>
 
        <div class="flex-1 overflow-y-auto px-6 py-6 space-y-6">
             
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Xin chào, Nguyễn Văn B 👋</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Tổng quan tình hình xử lý và giám sát thời hạn hồ sơ trực ca hôm nay.</p>
                </div>
                <div class="relative w-full lg:w-96">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" id="search-input" onkeyup="filterTableBySearch()" placeholder="Nhập mã hồ sơ hoặc tên công dân..." 
                           class="w-full bg-gray-50 border border-gray-200 text-sm rounded-lg pl-9 pr-4 py-2.5 focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                </div>
            </div>
 
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                 
                <div id="card-all" onclick="updateFilterState('all')" class="top-filter-card cursor-pointer transform hover:-translate-y-1 transition-all duration-200 bg-white p-4 rounded-xl border border-blue-500 ring-2 ring-blue-500 shadow-md flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-folder-open"></i></div>
                    <div>
                        <span class="text-xs text-gray-500 font-medium">Tổng tiếp nhận</span>
                        <div id="count-all" class="text-xl font-bold text-gray-800">0</div>
                        <span class="text-[10px] text-gray-400 font-medium">Hồ sơ trong ca trực</span>
                    </div>
                </div>
 
                <div id="card-cho_xu_ly" onclick="updateFilterState('cho_xu_ly')" class="top-filter-card cursor-pointer transform hover:-translate-y-1 transition-all duration-200 bg-white p-4 rounded-xl border border-gray-100 ring-2 ring-transparent hover:border-amber-400 hover:shadow-md flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-clock"></i></div>
                    <div>
                        <span class="text-xs text-gray-500 font-medium">Chờ xử lý</span>
                        <div id="count-cho_xu_ly" class="text-xl font-bold text-gray-800">0</div>
                        <span class="text-[10px] text-amber-600 font-semibold">Cần tiếp nhận</span>
                    </div>
                </div>
 
                <div id="card-dang_xu_ly" onclick="updateFilterState('dang_xu_ly')" class="top-filter-card cursor-pointer transform hover:-translate-y-1 transition-all duration-200 bg-white p-4 rounded-xl border border-gray-100 ring-2 ring-transparent hover:border-indigo-400 hover:shadow-md flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-spinner"></i></div>
                    <div>
                        <span class="text-xs text-gray-500 font-medium">Đang xử lý</span>
                        <div id="count-dang_xu_ly" class="text-xl font-bold text-gray-800">0</div>
                        <span class="text-[10px] text-indigo-600 font-semibold">Trong thời hạn</span>
                    </div>
                </div>
 
                <div id="card-qua_han" onclick="updateFilterState('qua_han')" class="top-filter-card cursor-pointer transform hover:-translate-y-1 transition-all duration-200 bg-rose-50/20 p-4 rounded-xl border border-rose-200 ring-2 ring-transparent hover:border-rose-500 hover:shadow-md flex items-center gap-4">
                    <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center text-xl animate-pulse"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div>
                        <span class="text-xs text-rose-600 font-bold">Hồ sơ quá hạn</span>
                        <div id="count-qua_han" class="text-xl font-bold text-rose-700">0</div>
                        <span class="text-[10px] text-rose-600 font-bold uppercase">Cần xử lý ngay</span>
                    </div>
                </div>
 
                <div id="card-da_hoan_thanh" onclick="updateFilterState('da_hoan_thanh')" class="top-filter-card cursor-pointer transform hover:-translate-y-1 transition-all duration-200 bg-white p-4 rounded-xl border border-gray-100 ring-2 ring-transparent hover:border-emerald-400 hover:shadow-md flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-square-check"></i></div>
                    <div>
                        <span class="text-xs text-gray-500 font-medium">Đã hoàn thành</span>
                        <div id="count-da_hoan_thanh" class="text-xl font-bold text-gray-800">0</div>
                        <span class="text-[10px] text-emerald-600 font-semibold">Đã được duyệt</span>
                    </div>
                </div>
            </div>
 
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                 
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 lg:col-span-2 flex flex-col overflow-hidden">
                     
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4 shrink-0">
                        <h2 id="table-title" class="font-bold text-gray-800 text-base">Danh sách hồ sơ - Tất cả</h2>
                        <button class="text-xs text-blue-600 hover:underline font-medium"><i class="fa-solid fa-arrows-rotate mr-1"></i> Tải lại</button>
                    </div>
 
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse" id="ticket-table">
                            <thead>
                                <tr class="border-b border-gray-100 text-xs font-bold text-gray-400 uppercase bg-gray-50/50">
                                    <th class="py-3 px-4">Mã hồ sơ</th>
                                    <th class="py-3 px-4">Thủ tục hành chính</th>
                                    <th class="py-3 px-4">Công dân</th>
                                    <th class="py-3 px-4">Thời gian còn lại (SLA)</th>
                                    <th class="py-3 px-4 text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-50" id="table-body">
                                
                                <?php foreach ($danh_sach_ho_so as $hoso): 
                                    // Chuyển đổi định dạng thời gian MySQL sang JS để Countdown không bị lỗi NaN
                                    $js_target_time = str_replace(' ', 'T', $hoso['target_time']);
                                ?>
                                <tr class="hover:bg-gray-50/50 transition-colors ticket-row" id="row-<?php echo strtolower($hoso['ma_ho_so']); ?>" data-status="<?php echo $hoso['trang_thai']; ?>">
                                    <td class="py-3.5 px-4 font-mono font-medium text-xs text-gray-600 search-target">
                                        <?php echo htmlspecialchars($hoso['ma_ho_so']); ?>
                                    </td>
                                    <td class="py-3.5 px-4 font-medium text-gray-800">
                                        <?php echo htmlspecialchars($hoso['thu_tuc']); ?>
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-500 search-target">
                                        <?php echo htmlspecialchars($hoso['cong_dan']); ?>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="countdown-timer flex items-center gap-1.5 font-mono text-xs font-bold bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full w-fit" data-target-time="<?php echo $js_target_time; ?>">
                                            <i class="fa-regular fa-clock"></i>
                                            <span class="timer-display">Đang tính...</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <button onclick="duyetHoSo('<?php echo htmlspecialchars($hoso['ma_ho_so']); ?>', '<?php echo htmlspecialchars($hoso['thu_tuc']); ?>')" class="text-xs font-semibold bg-blue-600 text-white px-3 py-1.5 rounded-md hover:bg-blue-700 transition">
                                            Duyệt hồ sơ
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
 
                <div class="space-y-6">
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex flex-col items-center">
                        <div class="w-full text-left mb-4"><h2 class="font-bold text-gray-800 text-sm">Tỷ lệ xử lý đúng hạn (SLA)</h2></div>
                        <div class="relative w-36 h-36 flex items-center justify-center">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                <path class="text-gray-100" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                <path class="text-emerald-500" stroke-dasharray="92.4, 100" stroke-width="3.2" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            </svg>
                            <div class="absolute text-center">
                                <div class="text-2xl font-bold text-gray-800">92.4%</div>
                                <div class="text-[10px] text-gray-400 font-medium">Đúng hạn</div>
                            </div>
                        </div>
                        <div class="w-full grid grid-cols-3 gap-2 text-center mt-5 pt-4 border-t border-gray-50 text-[11px]">
                            <div><span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1"></span><span class="text-gray-400 font-medium">Đúng hạn</span><div class="font-bold text-gray-700 mt-0.5">118</div></div>
                            <div><span class="inline-block w-2 h-2 rounded-full bg-amber-500 mr-1"></span><span class="text-gray-400 font-medium">Sắp hạn</span><div class="font-bold text-gray-700 mt-0.5">5</div></div>
                            <div><span class="inline-block w-2 h-2 rounded-full bg-rose-500 mr-1"></span><span class="text-gray-400 font-medium">Quá hạn</span><div class="font-bold text-gray-700 mt-0.5">5</div></div>
                        </div>
                    </div>
 
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                        <h2 class="font-bold text-gray-800 text-sm mb-4">Phân bổ hồ sơ trong ca trực</h2>
                        <div class="space-y-3.5 text-xs">
                            <div>
                                <div class="flex justify-between font-medium text-gray-700 mb-1"><span>Nguyễn Văn B (Bạn)</span><span class="font-bold">28 hồ sơ</span></div>
                                <div class="w-full bg-gray-100 h-1.5 rounded-full"><div class="bg-blue-600 h-1.5 rounded-full" style="width: 75%"></div></div>
                            </div>
                            <div>
                                <div class="flex justify-between font-medium text-gray-600 mb-1"><span>Trần Thị Mai</span><span class="font-bold">24 hồ sơ</span></div>
                                <div class="w-full bg-gray-100 h-1.5 rounded-full"><div class="bg-blue-400 h-1.5 rounded-full" style="width: 65%"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
 
            </div>
        </div>
    </div>
 
    <script>
        let currentStatusFilter = 'all';
 
        // 1. Hàm đếm số lượng hồ sơ từ dữ liệu thực tế trong bảng (Single Source of Truth)
        function updateCardCounts() {
            const rows = document.querySelectorAll('.ticket-row');
            
            let counts = {
                'all': rows.length,
                'cho_xu_ly': 0,
                'dang_xu_ly': 0,
                'qua_han': 0,
                'da_hoan_thanh': 0
            };
 
            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                if (counts[status] !== undefined) {
                    counts[status]++;
                }
            });
 
            document.getElementById('count-all').innerText = counts['all'];
            document.getElementById('count-cho_xu_ly').innerText = counts['cho_xu_ly'];
            document.getElementById('count-dang_xu_ly').innerText = counts['dang_xu_ly'];
            document.getElementById('count-qua_han').innerText = counts['qua_han'];
            document.getElementById('count-da_hoan_thanh').innerText = counts['da_hoan_thanh'];
 
            document.getElementById('badge-cho_xu_ly').innerText = counts['cho_xu_ly'];
            document.getElementById('badge-dang_xu_ly').innerText = counts['dang_xu_ly'];
            document.getElementById('badge-qua_han').innerText = counts['qua_han'];
            
            const badgeHoanThanh = document.getElementById('badge-da_hoan_thanh');
            if (counts['da_hoan_thanh'] > 0) {
                badgeHoanThanh.innerText = counts['da_hoan_thanh'];
                badgeHoanThanh.classList.remove('hidden');
            } else {
                badgeHoanThanh.classList.add('hidden');
            }
        }
 
        // 2. Đồng hồ hệ thống
        function updateSystemDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: '2-digit', day: '2-digit' };
            document.getElementById('current-date').innerText = now.toLocaleDateString('vi-VN', options);
            document.getElementById('current-time').innerText = now.toLocaleTimeString('vi-VN');
        }
        setInterval(updateSystemDateTime, 1000);
        updateSystemDateTime();
 
        // 3. Cập nhật trạng thái bộ lọc đồng bộ cả 2 nơi
        function updateFilterState(status) {
            currentStatusFilter = status;
            
            document.querySelectorAll('.top-filter-card').forEach(card => {
                card.classList.remove('ring-2', 'ring-blue-500', 'ring-amber-500', 'ring-indigo-500', 'ring-rose-500', 'ring-emerald-500', 'shadow-md', 'border-blue-500', 'border-amber-500', 'border-indigo-500', 'border-rose-500', 'border-emerald-500');
                card.classList.add('ring-transparent', 'border-gray-100'); 
            });
            document.getElementById('card-qua_han').classList.add('border-rose-200');
 
            const activeCard = document.getElementById(`card-${status}`);
            if(activeCard) {
                activeCard.classList.remove('ring-transparent', 'border-gray-100', 'border-rose-200');
                activeCard.classList.add('ring-2', 'shadow-md');
                
                if(status === 'all') activeCard.classList.add('ring-blue-500', 'border-blue-500');
                if(status === 'cho_xu_ly') activeCard.classList.add('ring-amber-500', 'border-amber-500');
                if(status === 'dang_xu_ly') activeCard.classList.add('ring-indigo-500', 'border-indigo-500');
                if(status === 'qua_han') activeCard.classList.add('ring-rose-500', 'border-rose-500');
                if(status === 'da_hoan_thanh') activeCard.classList.add('ring-emerald-500', 'border-emerald-500');
            }
 
            document.querySelectorAll('.sidebar-filter-btn').forEach(btn => {
                btn.className = "sidebar-filter-btn flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg text-sm transition-colors";
            });
            const activeSidebar = document.getElementById(`sidebar-${status}`);
            if(activeSidebar) {
                activeSidebar.className = "sidebar-filter-btn flex items-center justify-between px-4 py-2.5 bg-blue-50 text-blue-600 font-medium rounded-lg text-sm transition-colors";
            }
 
            const titles = {
                'all': 'Tất cả',
                'cho_xu_ly': 'Chờ xử lý',
                'dang_xu_ly': 'Đang xử lý',
                'qua_han': 'Quá hạn',
                'da_hoan_thanh': 'Đã hoàn thành'
            };
            document.getElementById('table-title').innerText = `Danh sách hồ sơ - ${titles[status]}`;
 
            executeCombinedFilter();
        }
 
        // 4. Tra cứu Search Bar
        function filterTableBySearch() {
            executeCombinedFilter();
        }
 
        // 5. Thực thi lọc dữ liệu bảng
        function executeCombinedFilter() {
            const searchQuery = document.getElementById('search-input').value.toLowerCase().trim();
            const rows = document.querySelectorAll('.ticket-row');
 
            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const searchTargets = row.querySelectorAll('.search-target');
                
                let matchSearch = false;
                searchTargets.forEach(target => {
                    if (target.innerText.toLowerCase().includes(searchQuery)) {
                        matchSearch = true;
                    }
                });
 
                if (searchQuery === "") matchSearch = true;
                const matchTab = (currentStatusFilter === 'all' || status === currentStatusFilter);
 
                if (matchSearch && matchTab) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
 
        // 6. Đếm ngược thời gian thực (SLA Countdown)
        function initCountdownTimers() {
            const timers = document.querySelectorAll('.countdown-timer');
            const alarmSound = document.getElementById('alarm-sound');
 
            timers.forEach(timer => {
                bindTimerLogic(timer, alarmSound);
            });
        }

        // Tách hàm xử lý timer riêng để tái sử dụng cho các dòng dữ liệu nhận từ WebSocket
        function bindTimerLogic(timer, alarmSound) {
            const targetTimeStr = timer.getAttribute('data-target-time');
            if (!targetTimeStr) return;
 
            const targetTime = new Date(targetTimeStr).getTime();
            const displaySpan = timer.querySelector('.timer-display');
 
            function updateTimer() {
                const ticketRow = timer.closest('.ticket-row');
                if (!ticketRow) return;
                const rowStatus = ticketRow.getAttribute('data-status');
                if (rowStatus === 'da_hoan_thanh') return;
 
                const now = new Date().getTime();
                const distance = targetTime - now;
 
                if (distance < 0) {
                    timer.className = "countdown-timer flex items-center gap-1.5 font-mono text-xs font-bold bg-rose-100 text-rose-700 px-2.5 py-1 rounded-full w-fit border border-rose-200 animate-pulse";
                    displaySpan.innerText = "Quá hạn " + formatTimeSpan(Math.abs(distance));
 
                    if (rowStatus !== 'qua_han') {
                        timer.closest('.ticket-row').setAttribute('data-status', 'qua_han');
                        updateCardCounts(); 
                        alarmSound.play().catch(() => {});
                    }
                    return;
                }
 
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
 
                if (days >= 1) {
                    timer.className = "countdown-timer flex items-center gap-1.5 font-mono text-xs font-bold bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full w-fit";
                    displaySpan.innerText = `${days} ngày ${hours}:${minutes}:${seconds}`;
                } else if (hours >= 2) {
                    timer.className = "countdown-timer flex items-center gap-1.5 font-mono text-xs font-bold bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full w-fit";
                    displaySpan.innerText = `${hours} giờ ${minutes}:${seconds}`;
                } else {
                    timer.className = "countdown-timer flex items-center gap-1.5 font-mono text-xs font-bold bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full w-fit border border-amber-200";
                    displaySpan.innerText = `${hours}g ${minutes}p ${seconds}s`;
                }
            }
 
            updateTimer();
            setInterval(updateTimer, 1000);
        }
 
        function formatTimeSpan(ms) {
            const totalMinutes = Math.floor(ms / (1000 * 60));
            const hours = Math.floor(totalMinutes / 60);
            const mins = totalMinutes % 60;
            return hours > 0 ? `${hours}g ${mins}p` : `${mins} phút`;
        }
 
        // 7. Hàm Duyệt nhanh & Đẩy hồ sơ sang bộ lọc Đã hoàn thành
        function duyetHoSo(maHoSo, tenThuTuc) {
            if(confirm(`Xác nhận phê duyệt hồ sơ ${maHoSo}?`)) {
                const targetRow = document.getElementById(`row-${maHoSo.toLowerCase()}`);
                if (targetRow) {
                    targetRow.setAttribute('data-status', 'da_hoan_thanh');
                    targetRow.classList.add('bg-emerald-50/50');
 
                    const actionCell = targetRow.querySelector('td:last-child');
                    actionCell.innerHTML = '<span class="text-emerald-600 font-bold text-xs"><i class="fa-solid fa-check mr-1"></i> Đã duyệt</span>';
 
                    const timeCell = targetRow.querySelector('.countdown-timer');
                    timeCell.className = "countdown-timer flex items-center gap-1.5 font-mono text-xs font-bold bg-gray-100 text-gray-500 px-2.5 py-1 rounded-full w-fit";
                    timeCell.innerHTML = '<i class="fa-solid fa-check-double"></i> Kết thúc';
 
                    updateCardCounts();
                    executeCombinedFilter();
                }
            }
        }
 
        // Khởi chạy khi DOM tải xong
        document.addEventListener('DOMContentLoaded', () => {
            initCountdownTimers();
            updateCardCounts(); // Đếm số liệu thực tế lần đầu tiên

            // =========================================================================
            // LẮNG NGHE WEBSOCKET REAL-TIME ĐỂ NHẬN HỒ SƠ MỚI TỪ DÂN
            // =========================================================================
            const ws = new WebSocket('ws://localhost:8080/ws');
            const alarmSound = document.getElementById('alarm-sound');
            
            ws.onmessage = function(event) {
                const data = JSON.parse(event.data);
                
                // Tránh trùng lặp nếu phần tử đã tồn tại
                if (document.getElementById(`row-${data.ma_ho_so.toLowerCase()}`)) return;

                const tableBody = document.getElementById('table-body');
                const newRow = document.createElement('tr');
                newRow.className = "hover:bg-gray-50/50 transition-colors ticket-row bg-amber-50/30"; // Đánh dấu màu vàng nhạt cho hồ sơ mới đến
                newRow.id = `row-${data.ma_ho_so.toLowerCase()}`;
                newRow.setAttribute('data-status', 'cho_xu_ly');

                newRow.innerHTML = `
                    <td class="py-3.5 px-4 font-mono font-medium text-xs text-gray-600 search-target">${data.ma_ho_so}</td>
                    <td class="py-3.5 px-4 font-medium text-gray-800">${data.thu_tuc}</td>
                    <td class="py-3.5 px-4 text-gray-500 search-target">${data.cong_dan}</td>
                    <td class="py-3.5 px-4">
                        <div class="countdown-timer flex items-center gap-1.5 font-mono text-xs font-bold bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full w-fit" data-target-time="${data.target_time}">
                            <i class="fa-regular fa-clock"></i>
                            <span class="timer-display">Đang tính...</span>
                        </div>
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <button onclick="duyetHoSo('${data.ma_ho_so}', '${data.thu_tuc}')" class="text-xs font-semibold bg-blue-600 text-white px-3 py-1.5 rounded-md hover:bg-blue-700 transition">Duyệt hồ sơ</button>
                    </td>
                `;

                // Chèn lên trên cùng danh sách
                tableBody.insertBefore(newRow, tableBody.firstChild);

                // Khởi chạy bộ đếm ngược cho phần tử mới thêm vào
                const newTimer = newRow.querySelector('.countdown-timer');
                bindTimerLogic(newTimer, alarmSound);

                // Re-calculate counts & filters
                updateCardCounts();
                executeCombinedFilter();

                // Phát âm báo có hồ sơ mới gửi tới
                alarmSound.play().catch(() => {});
            };
        });
    </script>
</body>
</html>