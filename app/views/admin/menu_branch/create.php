<?php
// File này được include từ view.php, nên đã có sẵn $conn và $selected_branch_id

// 1. Lấy danh sách ID các món ăn đã có tại cơ sở này
$sql_existing_dishes = "SELECT MaMon FROM menu_coso WHERE MaCoSo = $selected_branch_id";
$result_existing_dishes = mysqli_query($conn, $sql_existing_dishes);
$existing_dish_ids = [];
while ($row = mysqli_fetch_assoc($result_existing_dishes)) {
    $existing_dish_ids[] = $row['MaMon'];
}

// 2. Lấy danh sách tất cả các món ăn chung mà CHƯA CÓ tại cơ sở này
$sql_available_dishes = "SELECT MaMon, TenMon FROM monan";
if (!empty($existing_dish_ids)) {
    $excluded_ids = implode(',', $existing_dish_ids);
    $sql_available_dishes .= " WHERE MaMon NOT IN ($excluded_ids)";
}
$sql_available_dishes .= " ORDER BY TenMon";

$result_available_dishes = mysqli_query($conn, $sql_available_dishes);
$available_dishes = [];
while ($row = mysqli_fetch_assoc($result_available_dishes)) {
    $available_dishes[] = $row;
}
?>

<!-- Modal Thêm món vào Menu cơ sở -->
<div class="modal fade" id="addDishToBranchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i> Thêm món vào thực đơn cơ sở</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="?page=admin&section=menu_branch&action=process-create&branch_id=<?php echo $selected_branch_id; ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="MaCoSo" value="<?php echo $selected_branch_id; ?>">

                    <div class="mb-3">
                        <label for="MaMon" class="form-label">Chọn món ăn:</label>
                        <select class="form-select" id="MaMon" name="MaMon" required>
                            <option value="">-- Chọn từ danh sách món ăn chung --</option>
                            <?php foreach ($available_dishes as $dish) : ?>
                                <option value="<?php echo $dish['MaMon']; ?>"><?php echo htmlspecialchars($dish['TenMon']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (empty($available_dishes)) : ?>
                            <small class="form-text text-warning">Tất cả các món ăn đã được thêm vào cơ sở này.</small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="Gia" class="form-label">Giá bán (VNĐ):</label>
                        <input type="number" class="form-control" id="Gia" name="Gia" placeholder="Ví dụ: 50000" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label for="TinhTrang" class="form-label">Tình trạng:</label>
                        <select class="form-select" id="TinhTrang" name="TinhTrang" required>
                            <option value="con_hang" selected>Còn hàng</option>
                            <option value="het_hang">Hết hàng</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Hủy</button>
                    <button type="submit" class="btn btn-success" <?php echo empty($available_dishes) ? 'disabled' : ''; ?>>
                        <i class="fas fa-save"></i> Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

