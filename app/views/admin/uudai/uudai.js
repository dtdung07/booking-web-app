/**
 * UuDai Management JavaScript
 * Xử lý các tương tác trên trang quản lý ưu đãi
 */

document.addEventListener("DOMContentLoaded", function () {
  // ==================== XỬ LÝ CHỌN CƠ SỞ ====================
  const branchSelect = document.getElementById("branchSelect");
  if (branchSelect) {
    branchSelect.addEventListener("change", function () {
      const selectedCoSo = this.value;
      window.location.href = "?page=uudai&coso=" + selectedCoSo;
    });
  }

  // ==================== XỬ LÝ TÌM KIẾM ƯU ĐÃI ====================
  const searchInput = document.getElementById("searchInput");
  const uudaiCards = document.querySelectorAll(".uudai-card");

  if (searchInput && uudaiCards.length > 0) {
    searchInput.addEventListener("input", function () {
      const searchTerm = this.value.toLowerCase().trim();

      uudaiCards.forEach(function (card) {
        const uudaiName = card
          .querySelector(".uudai-name")
          .textContent.toLowerCase();
        const uudaiCode = card
          .querySelector(".uudai-code")
          .textContent.toLowerCase();

        if (uudaiName.includes(searchTerm) || uudaiCode.includes(searchTerm)) {
          card.style.display = "block";
        } else {
          card.style.display = "none";
        }
      });
    });
  }

  // ==================== XỬ LÝ LỌC THEO TRẠNG THÁI ====================
  const statusFilters = document.querySelectorAll(".status-filter");
  if (statusFilters.length > 0) {
    statusFilters.forEach((filter) => {
      filter.addEventListener("click", function () {
        const status = this.getAttribute("data-status");

        uudaiCards.forEach((card) => {
          if (status === "all" || card.getAttribute("data-status") === status) {
            card.style.display = "block";
          } else {
            card.style.display = "none";
          }
        });

        // Cập nhật active class cho filter
        statusFilters.forEach((f) => f.classList.remove("active"));
        this.classList.add("active");
      });
    });
  }

  // ==================== XỬ LÝ XÓA ƯU ĐÃI ====================
  const deleteButtons = document.querySelectorAll(".delete-btn");
  const deleteModal = document.getElementById("deleteModal");
  const uuDaiNameSpan = document.getElementById("uuDaiName");
  const confirmDeleteBtn = document.getElementById("confirmDelete");
  const cancelDeleteBtn = document.getElementById("cancelDelete");
  const closeModal = document.querySelector(".close");

  let currentUuDaiId = null;
  let currentMaCoSo = null;

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      currentUuDaiId = this.getAttribute("data-id");
      const uuDaiName = this.getAttribute("data-name");
      currentMaCoSo =
        this.getAttribute("data-coso") ||
        document.getElementById("branchSelect")?.value ||
        21;

      uuDaiNameSpan.textContent = uuDaiName;
      deleteModal.style.display = "block";
    });
  });

  // Xác nhận xóa ưu đãi
  confirmDeleteBtn.addEventListener("click", function () {
    if (currentUuDaiId) {
      deleteUuDai(currentUuDaiId);
    }
  });

  // Hàm xóa ưu đãi qua AJAX
  function deleteUuDai(uuDaiId) {
    // Hiển thị loading
    confirmDeleteBtn.innerHTML =
      '<i class="fas fa-spinner fa-spin"></i> Đang xóa...';
    confirmDeleteBtn.disabled = true;

    // Gửi yêu cầu xóa
    fetch("?page=uudai_delete", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body:
        "id=" +
        encodeURIComponent(uuDaiId) +
        "&ma_co_so=" +
        encodeURIComponent(currentMaCoSo),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          // Hiển thị thông báo thành công
          showNotification("Xóa ưu đãi thành công!", "success");

          // Reload trang sau 1 giây
          setTimeout(() => {
            window.location.reload();
          }, 1000);
        } else {
          throw new Error(data.message || "Có lỗi xảy ra khi xóa ưu đãi");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        showNotification("Lỗi: " + error.message, "error");
        confirmDeleteBtn.innerHTML = '<i class="fas fa-trash"></i> Xóa ưu đãi';
        confirmDeleteBtn.disabled = false;
      })
      .finally(() => {
        deleteModal.style.display = "none";
      });
  }

  // ==================== XỬ LÝ ĐÓNG MODAL ====================
  function closeDeleteModal() {
    deleteModal.style.display = "none";
    // Reset trạng thái nút xác nhận
    confirmDeleteBtn.innerHTML = '<i class="fas fa-trash"></i> Xóa ưu đãi';
    confirmDeleteBtn.disabled = false;
  }

  if (closeModal) {
    closeModal.addEventListener("click", closeDeleteModal);
  }

  if (cancelDeleteBtn) {
    cancelDeleteBtn.addEventListener("click", closeDeleteModal);
  }

  // Đóng modal khi click bên ngoài
  window.addEventListener("click", function (event) {
    if (event.target === deleteModal) {
      closeDeleteModal();
    }
  });

  // ==================== XỬ LÝ CẬP NHẬT TRẠNG THÁI NHANH ====================
  const statusToggleButtons = document.querySelectorAll(".status-toggle");
  statusToggleButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const uuDaiId = this.getAttribute("data-id");
      const currentStatus = this.getAttribute("data-status");
      const newStatus = currentStatus === "active" ? "inactive" : "active";

      toggleUuDaiStatus(uuDaiId, newStatus, this);
    });
  });

  // Hàm toggle trạng thái ưu đãi
  function toggleUuDaiStatus(uuDaiId, newStatus, button) {
    const originalHTML = button.innerHTML;

    // Hiển thị loading
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;

    fetch("?page=uudai_status", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body:
        "id=" +
        encodeURIComponent(uuDaiId) +
        "&status=" +
        encodeURIComponent(newStatus),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Cập nhật giao diện
          const statusBadge = button
            .closest(".uudai-card")
            .querySelector(".status-badge");
          const newStatusText =
            newStatus === "active" ? "Đang hoạt động" : "Đã kết thúc";

          statusBadge.textContent = newStatusText;
          statusBadge.className = "status-badge " + newStatus;
          button.setAttribute("data-status", newStatus);

          button.innerHTML =
            newStatus === "active"
              ? '<i class="fas fa-pause"></i> Tạm dừng'
              : '<i class="fas fa-play"></i> Kích hoạt';

          showNotification("Cập nhật trạng thái thành công!", "success");
        } else {
          throw new Error(data.message || "Có lỗi xảy ra");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        showNotification("Lỗi: " + error.message, "error");
        button.innerHTML = originalHTML;
      })
      .finally(() => {
        button.disabled = false;
      });
  }

  // ==================== XỬ LÝ FORM THÊM/ SỬA ƯU ĐÃI ====================
  const uuDaiForm = document.getElementById("uuDaiForm");
  if (uuDaiForm) {
    uuDaiForm.addEventListener("submit", function (e) {
      e.preventDefault();
      validateAndSubmitUuDaiForm(this);
    });

    // Xử lý thay đổi loại ưu đãi
    const typeSelect = document.getElementById("type");
    const valueInput = document.getElementById("value");
    const valueHelp = document.getElementById("valueHelp");

    if (typeSelect && valueHelp) {
      typeSelect.addEventListener("change", function () {
        updateValueFieldHelper(this.value, valueHelp);
      });

      // Khởi tạo helper text
      updateValueFieldHelper(typeSelect.value, valueHelp);
    }

    // Xử lý ngày bắt đầu/kết thúc
    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");

    if (startDateInput && endDateInput) {
      startDateInput.addEventListener("change", function () {
        if (
          endDateInput.value &&
          new Date(endDateInput.value) < new Date(this.value)
        ) {
          endDateInput.value = this.value;
        }
        endDateInput.min = this.value;
      });

      endDateInput.addEventListener("change", function () {
        if (
          startDateInput.value &&
          new Date(this.value) < new Date(startDateInput.value)
        ) {
          this.value = startDateInput.value;
        }
      });
    }
  }

  // ==================== HÀM HỖ TRỢ ====================

  // Cập nhật helper text cho trường giá trị
  function updateValueFieldHelper(type, helpElement) {
    const helpers = {
      percentage: "Nhập phần trăm giảm giá (ví dụ: 10 cho 10%)",
      fixed: "Nhập số tiền giảm (ví dụ: 50000 cho 50,000đ)",
      product: "Mô tả sản phẩm/ưu đãi (ví dụ: 1 ly nước miễn phí)",
    };

    helpElement.textContent = helpers[type] || "Nhập giá trị ưu đãi";
  }

  // Validate và submit form
  function validateAndSubmitUuDaiForm(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    // Hiển thị loading
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang lưu...';
    submitBtn.disabled = true;

    // Validate dữ liệu
    if (!validateUuDaiForm(form)) {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
      return;
    }

    // Submit form
    const formData = new FormData(form);

    fetch(form.action, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showNotification("Lưu ưu đãi thành công!", "success");
          setTimeout(() => {
            window.location.href =
              "?page=uudai&coso=" + formData.get("ma_co_so");
          }, 1500);
        } else {
          throw new Error(data.message || "Có lỗi xảy ra khi lưu ưu đãi");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        showNotification("Lỗi: " + error.message, "error");
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      });
  }

  // Validate form ưu đãi
  function validateUuDaiForm(form) {
    const code = form.querySelector("#code").value.trim();
    const name = form.querySelector("#name").value.trim();
    const startDate = form.querySelector("#start_date").value;
    const endDate = form.querySelector("#end_date").value;

    if (!code) {
      showNotification("Vui lòng nhập mã ưu đãi", "error");
      return false;
    }

    if (!name) {
      showNotification("Vui lòng nhập tên ưu đãi", "error");
      return false;
    }

    if (!startDate || !endDate) {
      showNotification("Vui lòng chọn ngày bắt đầu và kết thúc", "error");
      return false;
    }

    if (new Date(endDate) < new Date(startDate)) {
      showNotification("Ngày kết thúc phải sau ngày bắt đầu", "error");
      return false;
    }

    return true;
  }

  // Hiển thị thông báo
  function showNotification(message, type = "info") {
    // Tạo thông báo tạm thời - có thể tích hợp với hệ thống thông báo có sẵn
    const notification = document.createElement("div");
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${getNotificationIcon(type)}"></i>
                <span>${message}</span>
            </div>
        `;

    // Thêm vào body
    document.body.appendChild(notification);

    // Hiệu ứng xuất hiện
    setTimeout(() => notification.classList.add("show"), 100);

    // Tự động ẩn sau 3 giây
    setTimeout(() => {
      notification.classList.remove("show");
      setTimeout(() => {
        if (notification.parentNode) {
          notification.parentNode.removeChild(notification);
        }
      }, 300);
    }, 3000);
  }

  // Lấy icon cho thông báo
  function getNotificationIcon(type) {
    const icons = {
      success: "check-circle",
      error: "exclamation-circle",
      warning: "exclamation-triangle",
      info: "info-circle",
    };
    return icons[type] || "info-circle";
  }

  // ==================== XỬ LÝ PHÍM TẮT ====================
  document.addEventListener("keydown", function (e) {
    // ESC để đóng modal
    if (e.key === "Escape" && deleteModal.style.display === "block") {
      closeDeleteModal();
    }

    // Ctrl + N để thêm mới
    if (e.ctrlKey && e.key === "n") {
      e.preventDefault();
      const addBtn = document.querySelector('a[href*="uudai_create"]');
      if (addBtn) addBtn.click();
    }
  });

  console.log("UuDai Management JS loaded successfully");
});

// Hàm utility để format tiền tệ
function formatCurrency(amount) {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(amount);
}

// Hàm utility để format ngày
function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString("vi-VN");
}
