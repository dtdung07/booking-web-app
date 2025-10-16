<?php
// Kết nối DB (dùng connect của admin/uudai)
require_once __DIR__ . '/../admin/uudai/connect.php';

// Nếu không có kết nối thì im lặng
if (!isset($conn) || !$conn) {
    echo '<!-- promotions: no db connection -->';
    return;
}

// Lấy ưu đãi (chỉnh tên bảng/names nếu khác)
$sql = "SELECT * FROM `uudai` ORDER BY id DESC LIMIT 6";
$result = mysqli_query($conn, $sql);

$promos = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $code = $row['ma_ud'] ?? $row['ma'] ?? $row['code'] ?? $row['MaUD'] ?? ($row['ma_uudai'] ?? '(không có mã)');
        $title = $row['ten_ud'] ?? $row['ten'] ?? $row['title'] ?? 'Ưu đãi';
        $desc = $row['mota'] ?? $row['mo_ta'] ?? $row['description'] ?? '';
        $discount = $row['giam_gia'] ?? $row['discount'] ?? $row['phan_tram'] ?? '';
        $promos[] = [
            'code' => $code,
            'title' => $title,
            'desc' => $desc,
            'discount' => $discount,
        ];
    }
}
if (count($promos) === 0) {
    echo '<!-- promotions: none -->';
    return;
}
?>
<div class="promotions-widget" style="padding:18px;margin:20px 0;background:#fff;border-radius:10px;box-shadow:0 6px 20px rgba(0,0,0,0.04);">
    <h3 style="margin:0 0 12px;color:#1B4E30;">Mã giảm giá & Ưu đãi</h3>
    <div style="display:flex;flex-wrap:wrap;gap:12px;">
        <?php foreach ($promos as $p): ?>
            <div class="promo-card" style="flex:0 0 240px;border:1px solid #eee;border-radius:8px;padding:12px;background:#fbfbfb;">
                <div style="font-weight:700;color:#1B4E30;margin-bottom:6px;"><?php echo htmlspecialchars($p['title']); ?></div>
                <?php if ($p['discount']): ?>
                    <div style="color:#d32f2f;font-weight:700;margin-bottom:6px;">
                        <?php echo htmlspecialchars($p['discount']); ?><?php echo is_numeric($p['discount']) ? '%' : ''; ?>
                    </div>
                <?php endif; ?>
                <div style="font-size:0.9rem;color:#555;min-height:36px;margin-bottom:8px;"><?php echo htmlspecialchars($p['desc']); ?></div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <div style="font-family:monospace;background:#fff;border:1px dashed #cfcfcf;padding:6px 8px;border-radius:4px;"><?php echo htmlspecialchars($p['code']); ?></div>
                    <button class="copy-code-btn" data-code="<?php echo htmlspecialchars($p['code']); ?>" style="background:#1B4E30;color:#fff;border:none;padding:6px 10px;border-radius:6px;cursor:pointer;">Sao chép</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener('click', function(e){
    if (e.target && e.target.classList && e.target.classList.contains('copy-code-btn')) {
        const code = e.target.getAttribute('data-code') || '';
        if (!code) return;
        navigator.clipboard?.writeText(code).then(function(){
            const old = e.target.innerText;
            e.target.innerText = 'Đã sao chép';
            setTimeout(()=> e.target.innerText = old, 1400);
        }).catch(()=> alert('Không thể sao chép mã'));
    }
});
</script>