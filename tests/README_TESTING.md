# 🚀 Hướng dẫn Auto Testing cho Trang Quản lý Cơ sở

## 📋 Mục lục
1. [Giới thiệu](#giới-thiệu)
2. [Yêu cầu hệ thống](#yêu-cầu-hệ-thống)
3. [Cài đặt](#cài-đặt)
4. [Cấu hình](#cấu-hình)
5. [Chạy test](#chạy-test)
6. [Các test case](#các-test-case)
7. [Xem báo cáo](#xem-báo-cáo)
8. [Troubleshooting](#troubleshooting)

---

## 🎯 Giới thiệu

Bộ test tự động này được xây dựng để kiểm tra các chức năng của trang **Quản lý Cơ sở** trong hệ thống đặt bàn nhà hàng.

### Công nghệ sử dụng:
- **Selenium WebDriver**: Automation testing cho web
- **pytest**: Framework testing cho Python
- **Chrome/Firefox/Edge**: Trình duyệt hỗ trợ

### Phạm vi test:
- ✅ Hiển thị danh sách cơ sở
- ✅ Tìm kiếm cơ sở
- ✅ Thêm cơ sở mới
- ✅ Cập nhật thông tin cơ sở
- ✅ Xóa cơ sở
- ✅ Validation form
- ✅ UI/UX components
- ✅ API endpoints

---

## 💻 Yêu cầu hệ thống

### 1. Phần mềm cần cài đặt:
- **Python 3.8+** ([Download](https://www.python.org/downloads/))
- **pip** (thường đi kèm với Python)
- **Google Chrome** hoặc **Firefox** hoặc **Edge**
- **XAMPP** đang chạy (Apache + MySQL)

### 2. Kiểm tra Python đã cài đặt:
```bash
python --version
# hoặc
python3 --version
```

### 3. Kiểm tra pip:
```bash
pip --version
```

---

## 📦 Cài đặt

### Bước 1: Di chuyển vào thư mục tests
```bash
cd c:\xampp\htdocs\booking-web-app\tests
```

### Bước 2: Tạo môi trường ảo (khuyến nghị)
```bash
# Tạo virtual environment
python -m venv venv

# Kích hoạt virtual environment
# Trên Windows:
venv\Scripts\activate

# Trên Linux/Mac:
source venv/bin/activate
```

### Bước 3: Cài đặt các thư viện cần thiết
```bash
pip install -r requirements.txt
```

### Bước 4: Cài đặt WebDriver (tự động)
Test sẽ tự động tải ChromeDriver khi chạy lần đầu. Nếu gặp lỗi, có thể cài thủ công:

**Cách 1: Dùng webdriver-manager (đã có trong requirements.txt)**
```python
# Code sẽ tự động tải driver khi chạy
```

**Cách 2: Tải thủ công**
- Chrome: https://chromedriver.chromium.org/
- Firefox: https://github.com/mozilla/geckodriver/releases
- Edge: https://developer.microsoft.com/en-us/microsoft-edge/tools/webdriver/

---

## ⚙️ Cấu hình

### File: `test_config.py`

Mở file và điều chỉnh các thông số:

```python
# URL của ứng dụng
BASE_URL = "http://localhost/booking-web-app"

# Thông tin đăng nhập (nếu cần)
ADMIN_USERNAME = "admin"
ADMIN_PASSWORD = "admin123"

# Chọn trình duyệt
BROWSER_TYPE = "chrome"  # hoặc "firefox", "edge"

# Chế độ headless (không hiển thị trình duyệt)
HEADLESS = False  # True để chạy nền

# Thời gian chờ
TIMEOUT = 10
IMPLICIT_WAIT = 5
```

### Lưu ý quan trọng:
1. Đảm bảo **XAMPP đang chạy**
2. Database **booking_restaurant** đã được import
3. URL trong config phải khớp với đường dẫn thực tế

---

## ▶️ Chạy test

### 1. Chạy tất cả các test:
```bash
python test_branch_management.py
```

hoặc dùng pytest:
```bash
pytest test_branch_management.py -v -s
```

### 2. Chạy một test cụ thể:
```bash
# Chạy test kiểm tra load trang
pytest test_branch_management.py::TestBranchManagement::test_01_page_load_successfully -v -s

# Chạy test thêm cơ sở
pytest test_branch_management.py::TestBranchManagement::test_06_add_branch_successfully -v -s
```

### 3. Chạy với báo cáo HTML:
```bash
pytest test_branch_management.py -v -s --html=test_report.html --self-contained-html
```

### 4. Chạy song song (nhanh hơn):
```bash
pytest test_branch_management.py -v -s -n auto
```

### 5. Chạy ở chế độ headless:
Sửa trong `test_config.py`:
```python
HEADLESS = True
```

---

## 📝 Các Test Case

### TestBranchManagement (UI Tests)

| # | Test Case | Mô tả |
|---|-----------|-------|
| 1 | `test_01_page_load_successfully` | Kiểm tra trang load thành công |
| 2 | `test_02_display_branch_list` | Kiểm tra hiển thị danh sách |
| 3 | `test_03_search_functionality` | Kiểm tra tìm kiếm |
| 4 | `test_04_open_add_branch_modal` | Kiểm tra mở modal thêm |
| 5 | `test_05_add_branch_validation` | Kiểm tra validation form |
| 6 | `test_06_add_branch_successfully` | Thêm cơ sở mới |
| 7 | `test_07_update_branch_successfully` | Cập nhật cơ sở |
| 8 | `test_08_delete_branch_cancel` | Hủy xóa cơ sở |
| 9 | `test_09_delete_branch_confirm` | Xác nhận xóa cơ sở |
| 10 | `test_10_ui_responsive_elements` | Kiểm tra UI components |

### TestBranchAPI (API Tests)

| # | Test Case | Mô tả |
|---|-----------|-------|
| 1 | `test_api_get_data` | Test API lấy danh sách |
| 2 | `test_api_add_branch` | Test API thêm cơ sở |

---

## 📊 Xem báo cáo

### 1. Báo cáo console (real-time)
Khi chạy test, bạn sẽ thấy kết quả trực tiếp trong terminal:
```
[TEST 1] Kiểm tra trang quản lý cơ sở load thành công
✓ Trang load thành công với đầy đủ các thành phần
PASSED

[TEST 2] Kiểm tra hiển thị danh sách cơ sở
✓ Hiển thị 5 cơ sở trong danh sách
PASSED
...
```

### 2. Báo cáo HTML
Sau khi chạy với option `--html`:
```bash
pytest test_branch_management.py -v -s --html=test_report.html --self-contained-html
```

Mở file `test_report.html` trong trình duyệt để xem báo cáo chi tiết với:
- ✅ Số test passed/failed
- ⏱️ Thời gian chạy
- 📸 Screenshots (nếu có lỗi)
- 📋 Log chi tiết

### 3. Pytest summary
Cuối mỗi lần chạy, pytest hiển thị tổng kết:
```
======================== test session starts ========================
collected 12 items

test_branch_management.py::TestBranchManagement::test_01_page_load_successfully PASSED
test_branch_management.py::TestBranchManagement::test_02_display_branch_list PASSED
...

======================== 12 passed in 45.23s ========================
```

---

## 🔧 Troubleshooting

### ❌ Lỗi: "ModuleNotFoundError: No module named 'selenium'"
**Giải pháp:**
```bash
pip install selenium
# hoặc
pip install -r requirements.txt
```

### ❌ Lỗi: "WebDriver not found" hoặc "ChromeDriver not compatible"
**Giải pháp:**
1. Cập nhật Chrome lên phiên bản mới nhất
2. Cài webdriver-manager:
```bash
pip install webdriver-manager
```
3. Sửa code sử dụng webdriver-manager:
```python
from selenium import webdriver
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.chrome.service import Service

service = Service(ChromeDriverManager().install())
driver = webdriver.Chrome(service=service)
```

### ❌ Lỗi: "Connection refused" hoặc không load được trang
**Giải pháp:**
1. Kiểm tra XAMPP đã chạy chưa:
   - Apache: ✓ Running
   - MySQL: ✓ Running
2. Kiểm tra URL trong `test_config.py` đúng chưa
3. Thử truy cập thủ công: `http://localhost/booking-web-app/app/views/admin/branches/index.php`

### ❌ Lỗi: "Element not found" hoặc "Timeout"
**Giải pháp:**
1. Tăng thời gian chờ trong `test_config.py`:
```python
TIMEOUT = 20
IMPLICIT_WAIT = 10
```
2. Kiểm tra trang có load đầy đủ không (F12 → Console)

### ❌ Test thêm/sửa/xóa thất bại
**Giải pháp:**
1. Kiểm tra database có dữ liệu chưa
2. Kiểm tra quyền truy cập của database user
3. Xem log PHP (xampp/apache/logs/error.log)
4. Kiểm tra console trong test:
```bash
pytest test_branch_management.py -v -s --tb=long
```

### ❌ Test chạy quá chậm
**Giải pháp:**
1. Bật chế độ headless:
```python
HEADLESS = True
```
2. Giảm thời gian chờ (nếu kết nối ổn định)
3. Chạy song song:
```bash
pytest test_branch_management.py -n 4
```

---

## 📞 Hỗ trợ

### Cấu trúc thư mục tests:
```
tests/
├── test_branch_management.py  # File test chính
├── test_config.py              # Cấu hình
├── requirements.txt            # Dependencies
├── README_TESTING.md           # File này
├── test_report.html            # Báo cáo (sau khi chạy)
└── venv/                       # Virtual environment (nếu tạo)
```

### Tài liệu tham khảo:
- Selenium: https://selenium-python.readthedocs.io/
- pytest: https://docs.pytest.org/
- Python: https://docs.python.org/3/

---

## ✨ Tips

1. **Chạy test thường xuyên** sau mỗi lần thay đổi code
2. **Sử dụng virtual environment** để tránh xung đột thư viện
3. **Commit test code** vào Git cùng với source code
4. **Xem video demo** test chạy bằng cách tắt headless mode
5. **Thêm test case mới** khi có feature mới

---

## 🎉 Hoàn thành!

Bạn đã sẵn sàng để chạy auto test cho trang Quản lý Cơ sở!

Chạy lệnh sau để bắt đầu:
```bash
cd c:\xampp\htdocs\booking-web-app\tests
python test_branch_management.py
```

**Happy Testing! 🚀**
