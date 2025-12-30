# 🧪 Auto Testing Documentation

## 📁 Cấu trúc thư mục Tests

```
tests/
├── test_branch_management.py   # File test chính (12 test cases)
├── test_config.py               # Cấu hình (URL, timeout, test data)
├── requirements.txt             # Dependencies Python
├── run_tests.py                 # Script menu tương tác
├── run_test.bat                 # Script Windows (double-click)
├── check_environment.py         # Kiểm tra môi trường
├── README_TESTING.md            # Hướng dẫn đầy đủ
├── QUICKSTART.md                # Hướng dẫn nhanh
└── .gitignore                   # Ignore files
```

## ⚡ Quick Start

### Cách 1: Windows (đơn giản nhất)
1. Mở thư mục `tests/`
2. Double-click file `run_test.bat`
3. Chọn option từ menu

### Cách 2: Command Line
```bash
cd tests
pip install -r requirements.txt
python run_tests.py
```

### Cách 3: Kiểm tra môi trường trước
```bash
cd tests
python check_environment.py
```

## 📋 Test Coverage

### Trang: Quản lý Cơ sở
- ✅ **UI Tests** (5 tests)
  - Load trang thành công
  - Hiển thị danh sách
  - Tìm kiếm
  - Modal thêm cơ sở
  - Responsive components

- ✅ **CRUD Tests** (5 tests)
  - Form validation
  - Thêm cơ sở mới
  - Cập nhật thông tin
  - Hủy xóa
  - Xác nhận xóa

- ✅ **API Tests** (2 tests)
  - GET /get_data
  - POST /add

**Tổng cộng: 12 test cases**

## 🛠️ Công nghệ

- **Python 3.8+**
- **Selenium WebDriver** - Browser automation
- **pytest** - Testing framework
- **requests** - HTTP client
- **Chrome/Firefox/Edge** - Browsers

## 📊 Báo cáo Test

Sau khi chạy test, bạn sẽ có:

1. **Console output** - Kết quả real-time
2. **HTML report** - Chi tiết đầy đủ (nếu chạy với option `--html`)
3. **pytest summary** - Tổng kết cuối cùng

## 🎯 Mục tiêu

Đảm bảo chất lượng code bằng cách:
- ✓ Tự động kiểm tra các chức năng
- ✓ Phát hiện bug sớm
- ✓ Regression testing
- ✓ Tiết kiệm thời gian QA

## 📖 Tài liệu

- [README_TESTING.md](tests/README_TESTING.md) - Hướng dẫn đầy đủ
- [QUICKSTART.md](tests/QUICKSTART.md) - Bắt đầu nhanh
- [test_config.py](tests/test_config.py) - Cấu hình chi tiết

## 🆘 Hỗ trợ

Gặp vấn đề? Xem phần **Troubleshooting** trong [README_TESTING.md](tests/README_TESTING.md)

---

**Tạo bởi:** Auto Test Generator  
**Ngày tạo:** December 2025  
**Phiên bản:** 1.0.0
