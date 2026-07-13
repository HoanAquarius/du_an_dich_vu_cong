<?php
// =========================================================================
// 1. KHỞI TẠO DỮ LIỆU 100 THỦ TỤC (MOCK DATA)
// =========================================================================
$co_quan_list = ['UBND cấp xã', 'Công an cấp huyện', 'Sở GTVT', 'Cục Thuế', 'Sở TN&MT', 'BHXH Việt Nam'];
$linh_vuc_list = ['Tư pháp - Hộ tịch', 'Cư trú', 'Đất đai - Nhà ở', 'Bảo hiểm', 'Điện lực', 'Khác', 'Giao thông vận tải', 'Thuế'];

// 5 Thủ tục bắt buộc đầu tiên
$mockProcedures = [
    [
        'id' => 1, 'name' => 'Cấp đổi thẻ Căn cước công dân', 'code' => '1.000123',
        'linh_vuc' => 'Cư trú', 'doi_tuong' => 'Công dân', 'co_quan' => 'Công an cấp huyện', 'url' => '#'
    ],
    [
        'id' => 2, 'name' => 'Đăng ký cư trú (Thường trú/Tạm trú)', 'code' => '1.000124',
        'linh_vuc' => 'Cư trú', 'doi_tuong' => 'Công dân', 'co_quan' => 'Công an cấp huyện', 'url' => '#'
    ],
    [
        'id' => 3, 'name' => 'Đăng ký khai sinh cho trẻ em', 'code' => '1.000125',
        'linh_vuc' => 'Tư pháp - Hộ tịch', 'doi_tuong' => 'Công dân', 'co_quan' => 'UBND cấp xã', 'url' => 'gks.php'
    ],
    [
        'id' => 4, 'name' => 'Cấp Hộ chiếu phổ thông', 'code' => '1.000126',
        'linh_vuc' => 'Cư trú', 'doi_tuong' => 'Công dân', 'co_quan' => 'Công an cấp huyện', 'url' => '#'
    ],
    [
        'id' => 5, 'name' => 'Đăng ký kết hôn', 'code' => '1.000127',
        'linh_vuc' => 'Tư pháp - Hộ tịch', 'doi_tuong' => 'Công dân', 'co_quan' => 'UBND cấp xã', 'url' => '#'
    ]
];

// Sinh tự động 95 thủ tục còn lại cho đủ 100
for ($i = 6; $i <= 100; $i++) {
    $random_lv = $linh_vuc_list[array_rand($linh_vuc_list)];
    $doi_tuong = (in_array($random_lv, ['Thuế', 'Khác']) || rand(0, 1) == 1) ? 'Doanh nghiệp' : 'Công dân';
    
    $mockProcedures[] = [
        'id' => $i,
        'name' => 'Thủ tục hành chính số ' . $i . ' theo quy định hiện hành',
        'code' => '1.' . str_pad($i + 123450, 6, '0', STR_PAD_LEFT),
        'linh_vuc' => $random_lv,
        'doi_tuong' => $doi_tuong,
        'co_quan' => $co_quan_list[array_rand($co_quan_list)],
        'url' => '#'
    ];
}

// =========================================================================
// 2. LOGIC BỘ LỌC TÌM KIẾM (FILTER)
// =========================================================================
$search_query   = $_GET['search'] ?? '';
$current_tab    = $_GET['tab'] ?? 'all'; // all, cong_dan, doanh_nghiep
$active_lv      = $_GET['linh_vuc'] ?? 'all'; 

$filteredData = array_filter($mockProcedures, function($item) use ($search_query, $current_tab, $active_lv) {
    // 1. Lọc theo từ khóa
    $matchSearch = empty($search_query) || mb_stripos($item['name'], $search_query) !== false || mb_stripos($item['code'], $search_query) !== false;
    
    // 2. Lọc theo Tab Đối tượng (hoặc click vào hộp Công dân/Doanh nghiệp)
    $matchTab = ($current_tab === 'all') || 
                ($current_tab === 'cong_dan' && $item['doi_tuong'] === 'Công dân') || 
                ($current_tab === 'doanh_nghiep' && $item['doi_tuong'] === 'Doanh nghiệp');

    // 3. Lọc theo Lĩnh vực (khi click vào các hộp phân loại)
    $matchLv = ($active_lv === 'all') || ($item['linh_vuc'] === $active_lv);

    return $matchSearch && $matchTab && $matchLv;
});

// Sắp xếp lại chỉ mục mảng sau khi lọc
$filteredData = array_values($filteredData);

// =========================================================================
// 3. LOGIC PHÂN TRANG (PAGINATION)
// =========================================================================
$items_per_page = 10;
$total_items    = count($filteredData);
$total_pages    = ceil($total_items / $items_per_page);
$total_pages    = $total_pages < 1 ? 1 : $total_pages;

$current_page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page   = max(1, min($total_pages, $current_page)); 

$offset         = ($current_page - 1) * $items_per_page;
$pagedData      = array_slice($filteredData, $offset, $items_per_page);

// Hàm sinh URL giữ tham số lọc
function filterUrl($changes) {
    $params = $_GET;
    foreach ($changes as $key => $val) {
        if ($val === null) {
            unset($params[$key]);
        } else {
            $params[$key] = $val;
        }
    }
    return '?' . http_build_query($params);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh mục thủ tục hành chính - Cổng dịch vụ công quốc gia</title>
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

    <header class="bg-[#004a99] h-16 flex items-center justify-between px-6 text-white shrink-0 shadow-sm z-20 relative">
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
            <div class="flex items-center gap-2 cursor-pointer border-l border-white/20 pl-6">
                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-user text-white text-sm"></i>
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
                <a href="./thu_tuc/thu_tuc.php" class="flex items-center gap-3 px-4 py-2.5 bg-blue-50 text-blue-600 font-medium rounded-lg text-sm">
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

        <main class="flex-1 overflow-y-auto px-6 py-6 bg-[#f8fafc]">
            <div class="space-y-6">
                
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Danh mục thủ tục hành chính</h1>
                    <p class="text-xs text-gray-500 mt-1">Tìm kiếm và tra cứu các thủ tục hành chính theo nhu cầu của bạn</p>
                </div>

                <form method="GET" action="" class="flex gap-2">
                    <input type="hidden" name="tab" value="<?php echo htmlspecialchars($current_tab); ?>">
                    <input type="hidden" name="linh_vuc" value="<?php echo htmlspecialchars($active_lv); ?>">
                    
                    <div class="relative flex-1">
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Nhập tên thủ tục, mã thủ tục hoặc từ khóa..." 
                               class="w-full pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 shadow-sm">
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm
                    </button>
                    <a href="thu_tuc.php" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-lg text-sm font-medium flex items-center justify-center" title="Xóa bộ lọc">
                        <i class="fa-solid fa-rotate-right"></i>
                    </a>
                </form>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    <a href="<?php echo filterUrl(['tab' => 'cong_dan', 'linh_vuc' => 'all', 'page' => 1]); ?>" class="p-4 rounded-xl border transition flex items-center gap-3 <?php echo $current_tab === 'cong_dan' ? 'bg-blue-50 border-blue-300' : 'bg-white border-gray-100 hover:border-gray-300'; ?>">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-sm shrink-0"><i class="fa-solid fa-user"></i></div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-gray-800 truncate">Công dân</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">1.234 thủ tục</div>
                        </div>
                    </a>
                    <a href="<?php echo filterUrl(['tab' => 'doanh_nghiep', 'linh_vuc' => 'all', 'page' => 1]); ?>" class="p-4 rounded-xl border transition flex items-center gap-3 <?php echo $current_tab === 'doanh_nghiep' ? 'bg-green-50 border-green-300' : 'bg-white border-gray-100 hover:border-gray-300'; ?>">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-sm shrink-0"><i class="fa-solid fa-building"></i></div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-gray-800 truncate">Doanh nghiệp</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">1.567 thủ tục</div>
                        </div>
                    </a>
                    <a href="<?php echo filterUrl(['linh_vuc' => 'Đất đai - Nhà ở', 'tab' => 'all', 'page' => 1]); ?>" class="p-4 rounded-xl border transition flex items-center gap-3 <?php echo $active_lv === 'Đất đai - Nhà ở' ? 'bg-orange-50 border-orange-300' : 'bg-white border-gray-100 hover:border-gray-300'; ?>">
                        <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center text-sm shrink-0"><i class="fa-solid fa-house-chimney"></i></div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-gray-800 truncate">Đất đai - Nhà ở</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">289 thủ tục</div>
                        </div>
                    </a>
                    <a href="<?php echo filterUrl(['linh_vuc' => 'Bảo hiểm', 'tab' => 'all', 'page' => 1]); ?>" class="p-4 rounded-xl border transition flex items-center gap-3 <?php echo $active_lv === 'Bảo hiểm' ? 'bg-purple-50 border-purple-300' : 'bg-white border-gray-100 hover:border-gray-300'; ?>">
                        <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-sm shrink-0"><i class="fa-solid fa-shield-heart"></i></div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-gray-800 truncate">Bảo hiểm</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">98 thủ tục</div>
                        </div>
                    </a>
                    <a href="<?php echo filterUrl(['linh_vuc' => 'Điện lực', 'tab' => 'all', 'page' => 1]); ?>" class="p-4 rounded-xl border transition flex items-center gap-3 <?php echo $active_lv === 'Điện lực' ? 'bg-cyan-50 border-cyan-300' : 'bg-white border-gray-100 hover:border-gray-300'; ?>">
                        <div class="w-10 h-10 bg-cyan-100 text-cyan-600 rounded-xl flex items-center justify-center text-sm shrink-0"><i class="fa-solid fa-bolt"></i></div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-gray-800 truncate">Điện lực</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">74 thủ tục</div>
                        </div>
                    </a>
                    <a href="<?php echo filterUrl(['linh_vuc' => 'Khác', 'tab' => 'all', 'page' => 1]); ?>" class="p-4 rounded-xl border transition flex items-center gap-3 <?php echo $active_lv === 'Khác' ? 'bg-gray-100 border-gray-300' : 'bg-white border-gray-100 hover:border-gray-300'; ?>">
                        <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-xl flex items-center justify-center text-sm shrink-0"><i class="fa-solid fa-ellipsis"></i></div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-gray-800 truncate">Khác</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">437 thủ tục</div>
                        </div>
                    </a>
                </div>

                <div class="flex border-b border-gray-200 text-sm font-medium mt-4">
                    <a href="<?php echo filterUrl(['tab' => 'all', 'page' => 1]); ?>" class="px-5 py-2.5 border-b-2 transition <?php echo $current_tab === 'all' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-800'; ?>">Tất cả thủ tục</a>
                    <a href="<?php echo filterUrl(['tab' => 'cong_dan', 'page' => 1]); ?>" class="px-5 py-2.5 border-b-2 transition <?php echo $current_tab === 'cong_dan' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-800'; ?>">Công dân</a>
                    <a href="<?php echo filterUrl(['tab' => 'doanh_nghiep', 'page' => 1]); ?>" class="px-5 py-2.5 border-b-2 transition <?php echo $current_tab === 'doanh_nghiep' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-800'; ?>">Doanh nghiệp</a>
                </div>

                <div class="w-full bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col justify-between">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                                    <th class="px-4 py-3 text-center w-12">STT</th>
                                    <th class="px-4 py-3 min-w-[280px]">Tên thủ tục hành chính</th>
                                    <th class="px-4 py-3 w-36">Lĩnh vực</th>
                                    <th class="px-4 py-3 w-28">Đối tượng</th>
                                    <th class="px-4 py-3 w-48">Cơ quan thực hiện</th>
                                    <th class="px-4 py-3 text-center w-32">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                                <?php if(empty($pagedData)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-12 text-gray-400">
                                            <i class="fa-solid fa-magnifying-glass text-3xl mb-2 text-gray-200 block"></i>
                                            Không tìm thấy kết quả nào phù hợp.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($pagedData as $index => $row): ?>
                                        <tr class="hover:bg-gray-50/80 transition-colors">
                                            <td class="px-4 py-4 text-center text-gray-400 font-normal"><?php echo $offset + $index + 1; ?></td>
                                            <td class="px-4 py-4">
                                                <div class="text-blue-900 font-semibold text-[13px] hover:text-blue-600 cursor-pointer flex items-center gap-1.5">
                                                    <?php echo htmlspecialchars($row['name']); ?>
                                                    <?php if($row['id'] <= 5): ?>
                                                        <i class="fa-solid fa-star text-[10px] text-amber-400"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-[10px] text-gray-400 font-normal mt-1">Mã thủ tục: <?php echo $row['code']; ?></div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <?php
                                                    // Đổi màu nền tag lĩnh vực cho thêm sinh động
                                                    $lv_color = 'bg-gray-100 text-gray-600';
                                                    if($row['linh_vuc'] === 'Tư pháp - Hộ tịch') $lv_color = 'bg-green-50 text-green-600 border border-green-100';
                                                    if($row['linh_vuc'] === 'Cư trú') $lv_color = 'bg-purple-50 text-purple-600 border border-purple-100';
                                                    if($row['linh_vuc'] === 'Đất đai - Nhà ở') $lv_color = 'bg-orange-50 text-orange-600 border border-orange-100';
                                                    if($row['linh_vuc'] === 'Bảo hiểm') $lv_color = 'bg-pink-50 text-pink-600 border border-pink-100';
                                                    if($row['linh_vuc'] === 'Điện lực') $lv_color = 'bg-cyan-50 text-cyan-600 border border-cyan-100';
                                                ?>
                                                <span class="px-2 py-0.5 rounded text-[11px] font-medium whitespace-nowrap <?php echo $lv_color; ?>">
                                                    <?php echo $row['linh_vuc']; ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold whitespace-nowrap <?php echo $row['doi_tuong'] === 'Công dân' ? 'bg-blue-50 text-blue-600' : 'bg-teal-50 text-teal-600'; ?>">
                                                    <?php echo $row['doi_tuong']; ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-gray-600 font-normal"><?php echo $row['co_quan']; ?></td>
                                            <td class="px-4 py-4 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="<?php echo $row['url']; ?>" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-[11px] transition shadow-sm font-semibold whitespace-nowrap">
                                                        Nộp hồ sơ
                                                    </a>
                                                    <button class="text-gray-300 hover:text-blue-600 text-sm" title="Lưu thủ tục"><i class="fa-regular fa-bookmark"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500 bg-gray-50/50">
                        <div>
                            Hiển thị <span class="font-bold text-gray-700"><?php echo empty($filteredData) ? 0 : $offset + 1; ?></span> - 
                            <span class="font-bold text-gray-700"><?php echo min($offset + $items_per_page, $total_items); ?></span> trong tổng số 
                            <span class="font-bold text-gray-700"><?php echo $total_items; ?></span> thủ tục
                        </div>
                        
                        <?php if ($total_pages > 1): ?>
                            <nav class="flex items-center gap-1">
                                <a href="<?php echo $current_page > 1 ? filterUrl(['page' => $current_page - 1]) : '#'; ?>" 
                                   class="w-7 h-7 flex items-center justify-center rounded border bg-white text-gray-500 hover:bg-gray-50 <?php echo $current_page <= 1 ? 'opacity-40 pointer-events-none' : ''; ?>">
                                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                                </a>

                                <?php for($p = 1; $p <= $total_pages; $p++): ?>
                                    <?php if($p == 1 || $p == $total_pages || abs($p - $current_page) <= 1): ?>
                                        <a href="<?php echo filterUrl(['page' => $p]); ?>" 
                                           class="w-7 h-7 flex items-center justify-center rounded border font-medium transition <?php echo $p == $current_page ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'; ?>">
                                            <?php echo $p; ?>
                                        </a>
                                    <?php elseif($p == 2 || $p == $total_pages - 1): ?>
                                        <span class="w-5 text-center text-gray-300">...</span>
                                    <?php endif; ?>
                                <?php endfor; ?>

                                <a href="<?php echo $current_page < $total_pages ? filterUrl(['page' => $current_page + 1]) : '#'; ?>" 
                                   class="w-7 h-7 flex items-center justify-center rounded border bg-white text-gray-500 hover:bg-gray-50 <?php echo $current_page >= $total_pages ? 'opacity-40 pointer-events-none' : ''; ?>">
                                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </a>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>