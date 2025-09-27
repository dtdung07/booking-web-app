<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include dirname(__DIR__,4) . "/config/connect.php";

// Xử lý cập nhật trạng thái bàn sẽ được xử lý trong status.php

// Xử lý các action
if(isset($_GET['action'])){
    switch ($_GET['action']) {
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

//Modal thêm bàn
include __DIR__ . "/create.php";

?>

 <!-- Bootstrap 5 -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="container-fluid">

    <!-- Search bar -->
  <div class="row mb-3">
    <div class="col-md-6">
      <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm bàn trên trang hiện tại...">
      <!-- <small class="text-muted">
        <i class="fas fa-info-circle"></i> Tìm kiếm chỉ hoạt động trên trang hiện tại
      </small> -->
    </div>
    <div class="col-md-6 text-end">
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTableModal">
        <i class="fas fa-plus"></i> Thêm bàn mới
      </button>

    </div>
  </div>

    <?php
        if(isset($_GET['action'])){
            switch ($_GET['action']) {
                case 'view':
                    include __DIR__ . "/view.php";
                    break;
                case 'create':
                    include __DIR__ . "/create.php";
                    break;
                case 'update':
                    include __DIR__ . "/update.php";
                    break;
                case 'status':
                    // Hiển thị trang quản lý trạng thái bàn
                    include __DIR__ . "/status.php";
                    break;
                default:
                    include "view.php";
                    break;
            }
        } else {
            // Mặc định hiển thị danh sách bàn khi không có tham số action
            include __DIR__ . "/view.php";
        }
    ?>


    </div>

<script>
  // Tìm kiếm món ăn
  document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#tableTable tbody tr");
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
        let tbody = document.querySelector("#menuTable tbody");
        let newRow = document.createElement("tr");
        newRow.id = "noSearchResult";
        newRow.innerHTML = `
          <td colspan="5" class="text-center text-muted py-4">
            <i class="fas fa-search fa-2x mb-2"></i>
            <br>
            Không tìm thấy món ăn phù hợp với từ khóa "<strong>${filter}</strong>"
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