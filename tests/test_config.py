"""
Cấu hình cho các bài test automation
"""

# URL của ứng dụng (thay đổi theo môi trường của bạn)
BASE_URL = "https://shop.bug.io.vn"
ADMIN_BRANCH_URL = f"{BASE_URL}/app/views/admin/branches/index.php"
ADMIN_BRANCH_API_URL = f"{BASE_URL}/app/controllers/admin/AdminBranchController.php"

# Thông tin đăng nhập admin (nếu cần)
ADMIN_USERNAME = "admin"
ADMIN_PASSWORD = "admin"
LOGIN_URL = f"{BASE_URL}/login.php"

# Thời gian chờ cho Selenium
HEADLESS = False  # Đặt True nếu chạy trên Server không có màn hình
TIMEOUT = 50      # Tăng timeout lên vì mạng thực tế có thể chậm hơn local
IMPLICIT_WAIT = 10

# Thông tin test data
TEST_BRANCH = {
    "tenCoSo": "Cơ sở Test Automation",
    "diaChi": "123 Đường Test, Quận Test, TP.HCM",
    "dienThoai": "0987654321",
    "anhUrl": "https://via.placeholder.com/300x200"
}

UPDATE_BRANCH = {
    "tenCoSo": "Cơ sở Test Updated",
    "diaChi": "456 Đường Updated, Quận Updated, TP.HCM",
    "dienThoai": "0912345678",
    "anhUrl": "https://via.placeholder.com/400x300"
}

# Browser options
BROWSER_TYPE = "chrome"  # chrome, firefox, edge
HEADLESS = False  # True để chạy không hiển thị trình duyệt
