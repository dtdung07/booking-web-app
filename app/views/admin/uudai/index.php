<?php
include __DIR__ . "/connect.php";

// Xử lý các action
if(isset($_GET['action'])){
    switch ($_GET['action']) {
        case 'view':
            include "view.php";
            return; // Dừng để không hiển thị danh sách
        case 'delete':
            include "process-delete.php";
            break;
        case 'process-update':
            include "process-update.php";
            break;
        case 'process-create':
            include "process-create.php";
            break;
    }
}

//Modal thêm ưu đãi
include __DIR__ . "/create.php";

?>
<div class="container-fluid">

    <!-- Search bar -->
  <div class="row mb-3">
    <div class="col-md-6">
      <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm ưu đãi trên trang hiện tại...">
    </div>
    <div class="col-md-6 text-end">
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUuDaiModal">
        <i class="fas fa-plus"></i> Thêm ưu đãi mới
      </button>

    </div>
  </div>

    <?php
        // Logic điều hướng đã được chuyển lên dashboard.php
        // Phần này chỉ cần include file list.php để hiển thị danh sách
        include __DIR__ . "/list.php";
    ?>


    </div>

<script>
  // Tìm kiếm ưu đãi
  document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#uudaiTable tbody tr");
    let visibleCount = 0;
    
    rows.forEach(row => {
      // Bỏ qua hàng "không có dữ liệu"  
      if (row.cells.length === 1 && row.cells[0].colSpan > 1) {
        return;
      }
      
      let text = row.innerText.toLowerCase();
      if (text.includes(filter)) {
        row.style.display = "";
        visibleCount++;
      } else {
        row.style.display = "none";
      }
    });
    
    // Hiển thị thông báo nếu không tìm thấy kết quả
    let noResultRow = document.querySelector("#noSearchResult");
    if (visibleCount === 0 && filter.trim() !== "") {
      if (!noResultRow) {
        let tbody = document.querySelector("#uudaiTable tbody");
        let newRow = document.createElement("tr");
        newRow.id = "noSearchResult";
        newRow.innerHTML = `
          <td colspan="6" class="text-center text-muted py-4">
            <i class="fas fa-search fa-2x mb-2"></i>
            <br>
            Không tìm thấy ưu đãi phù hợp với từ khóa "<strong>${filter}</strong>"
            <br>
            <small>Thử tìm kiếm với từ khóa khác hoặc kiểm tra các trang khác</small>
          </td>
        `;
        tbody.appendChild(newRow);
      } else {
        noResultRow.querySelector("strong").textContent = filter;
        noResultRow.style.display = "";
      }
    } else if (noResultRow) {
      noResultRow.style.display = "none";
    }
  });
</script>

</div>