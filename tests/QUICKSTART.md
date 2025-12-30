# 🚀 QUICK START - Auto Testing

## Cài đặt nhanh (3 bước)

### 1. Cài đặt Python packages
```bash
cd c:\xampp\htdocs\booking-web-app\tests
pip install -r requirements.txt
```

### 2. Cấu hình (nếu cần)
Mở `test_config.py` và kiểm tra:
- BASE_URL = "http://localhost/booking-web-app"
- BROWSER_TYPE = "chrome"

### 3. Chạy test
```bash
# Cách 1: Menu tương tác
python run_tests.py

# Cách 2: Chạy trực tiếp
python test_branch_management.py

# Cách 3: Dùng pytest
pytest test_branch_management.py -v -s
```

---

## 📊 Kết quả mong đợi

```
======================== test session starts ========================

test_branch_management.py::TestBranchManagement::test_01... PASSED ✓
test_branch_management.py::TestBranchManagement::test_02... PASSED ✓
test_branch_management.py::TestBranchManagement::test_03... PASSED ✓
...

======================== 12 passed in 45s ==========================
```

---

## 📝 Các test được chạy

✅ Test 1: Load trang thành công  
✅ Test 2: Hiển thị danh sách  
✅ Test 3: Tìm kiếm  
✅ Test 4: Mở modal thêm  
✅ Test 5: Validation form  
✅ Test 6: Thêm cơ sở mới  
✅ Test 7: Cập nhật cơ sở  
✅ Test 8: Hủy xóa  
✅ Test 9: Xác nhận xóa  
✅ Test 10: UI components  
✅ Test API: Get data  
✅ Test API: Add branch  

---

## ⚠️ Yêu cầu trước khi chạy

- ✓ XAMPP đang chạy (Apache + MySQL)
- ✓ Database đã import
- ✓ Python 3.8+ đã cài đặt
- ✓ Chrome/Firefox browser

---

## 🆘 Gặp lỗi?

Xem file `README_TESTING.md` để biết chi tiết troubleshooting.

**Lỗi phổ biến:**
1. Module not found → `pip install -r requirements.txt`
2. WebDriver error → Cập nhật Chrome/Firefox lên bản mới nhất
3. Connection refused → Kiểm tra XAMPP đang chạy

---

**Xem hướng dẫn đầy đủ:** [README_TESTING.md](README_TESTING.md)
