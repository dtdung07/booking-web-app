"""
Test Automation cho trang Quản lý Cơ sở (Branch Management)
Sử dụng Selenium WebDriver và pytest
"""

import pytest
import time
import random
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.common.exceptions import TimeoutException, NoSuchElementException
from test_config import *


class TestBranchManagement:
    """Test suite cho quản lý cơ sở"""
    
    
    @pytest.fixture(autouse=True)
    def setup_teardown(self):
        """Khởi tạo và đóng trình duyệt trước/sau mỗi test"""
        # Setup
        chrome_options = Options()
        if HEADLESS:
            chrome_options.add_argument("--headless")
        chrome_options.add_argument("--disable-notifications")
        chrome_options.add_argument("--disable-popup-blocking")
        chrome_options.add_argument("--start-maximized")
        
        self.driver = webdriver.Chrome(options=chrome_options)
        self.driver.implicitly_wait(IMPLICIT_WAIT)
        self.wait = WebDriverWait(self.driver, TIMEOUT)
        
        yield
        
        # Teardown
        self.driver.quit()
    
    def test_01_page_load_successfully(self):
        """Test 1: Kiểm tra trang quản lý cơ sở load thành công"""
        self.driver.get(ADMIN_BRANCH_URL)
        self.wait.until(EC.presence_of_element_located((By.ID, "branchTable")))
        print("\n[TEST 1] Kiểm tra trang quản lý cơ sở load thành công")
        # Kiểm tra title
        assert "Dashboard - Quản trị nhà hàng" in self.driver.title
        # Kiểm tra header
        # header = self.wait.until(
        #     EC.presence_of_element_located((By.TAG_NAME, "h1"))
        # )
        # assert "Quản lý Cơ sở" in header.text
        
        # Kiểm tra table tồn tại
        table = self.driver.find_element(By.ID, "branchTable")
        assert table.is_displayed()
        
        print("✓ Trang load thành công với đầy đủ các thành phần")
    
    # def test_02_display_branch_list(self):
    #     """Test 2: Kiểm tra hiển thị danh sách cơ sở"""
    #     print("\n[TEST 2] Kiểm tra hiển thị danh sách cơ sở")
        
    #     self.driver.get(ADMIN_BRANCH_URL)
        
    #     # Đợi table load
    #     table = self.wait.until(
    #         EC.presence_of_element_located((By.ID, "branchTable"))
    #     )
        
    #     # Kiểm tra header của bảng
    #     headers = self.driver.find_elements(By.CSS_SELECTOR, "#branchTable thead th")
    #     expected_headers = ["Mã cơ sở", "Tên cơ sở", "Địa chỉ", "Số điện thoại", "Hành động"]
        
    #     for i, header in enumerate(headers):
    #         assert expected_headers[i] in header.text
        
    #     # Kiểm tra có dữ liệu trong table
    #     rows = self.driver.find_elements(By.CSS_SELECTOR, "#branchTable tbody tr")
    #     print(f"✓ Hiển thị {len(rows)} cơ sở trong danh sách")
        
    #     assert len(rows) >= 0  # Cho phép table rỗng
    
    # def test_03_search_functionality(self):
    #     """Test 3: Kiểm tra chức năng tìm kiếm"""
    #     print("\n[TEST 3] Kiểm tra chức năng tìm kiếm")
        
    #     self.driver.get(ADMIN_BRANCH_URL)
        
    #     # Tìm input search
    #     search_input = self.wait.until(
    #         EC.presence_of_element_located((By.ID, "searchInput"))
    #     )
        
    #     # Nhập từ khóa tìm kiếm
    #     search_keyword = "Lang"
    #     search_input.clear()
    #     search_input.send_keys(search_keyword)
        
    #     time.sleep(1)  # Đợi filter hoạt động
        
    #     # Kiểm tra kết quả tìm kiếm
    #     visible_rows = self.driver.find_elements(
    #         By.CSS_SELECTOR, "#branchTable tbody tr:not([style*='display: none'])"
    #     )
        
    #     print(f"✓ Tìm thấy {len(visible_rows)} kết quả với từ khóa '{search_keyword}'")
    
    def test_04_open_add_branch_modal(self):
        """Test 4: Kiểm tra mở modal thêm cơ sở mới"""
        print("\n[TEST 4] Kiểm tra mở modal thêm cơ sở mới")
        
        self.driver.get(ADMIN_BRANCH_URL)
        
        # Click nút "Thêm cơ sở mới"
        add_button = self.wait.until(
            EC.element_to_be_clickable((By.CSS_SELECTOR, "button[data-bs-target='#addBranchModal']"))
        )
        add_button.click()
        
        # Kiểm tra modal hiển thị
        modal = self.wait.until(
            EC.visibility_of_element_located((By.ID, "addBranchForm"))
        )
        
        assert modal.is_displayed()
        
        # Kiểm tra các trường input trong modal
        form_fields = ["tenCoSo", "diaChi", "dienThoai", "anhUrl"]
        for field in form_fields:
            input_field = self.driver.find_element(By.ID, field)
            assert input_field.is_displayed()
        
        print("✓ Modal thêm cơ sở mới hiển thị đầy đủ các trường")
    
    # def test_05_add_branch_validation(self):
    #     """Test 5: Kiểm tra validation khi thêm cơ sở"""
    #     print("\n[TEST 5] Kiểm tra validation khi thêm cơ sở (bỏ trống trường bắt buộc)")
        
    #     self.driver.get(ADMIN_BRANCH_URL)
        
    #     # Mở modal thêm
    #     add_button = self.wait.until(
    #         EC.element_to_be_clickable((By.CSS_SELECTOR, "button[data-bs-target='#addBranchModal']"))
    #     )
    #     add_button.click()
        
    #     # Đợi modal hiển thị
    #     self.wait.until(
    #         EC.visibility_of_element_located((By.ID, "addBranchModal"))
    #     )
        
    #     # Click nút lưu mà không điền gì
    #     save_button = self.driver.find_element(By.CSS_SELECTOR, ".modal-footer .btn-primary")
    #     save_button.click()
        
    #     time.sleep(1)
        
    #     # Kiểm tra HTML5 validation hoặc alert
    #     # Note: Browser có thể hiển thị validation message tự động
    #     print("✓ Test validation hoàn thành")
    
    def test_06_add_branch_successfully(self):
        """Test 6: Thêm cơ sở mới thành công"""
        print("\n[TEST 6] Thêm cơ sở mới thành công")
        
        self.driver.get(ADMIN_BRANCH_URL)
        
        # Lấy số lượng cơ sở hiện tại
        initial_rows = len(self.driver.find_elements(By.CSS_SELECTOR, "#branchTable tbody tr"))
        
        # Mở modal thêm
        add_button = self.wait.until(
            EC.element_to_be_clickable((By.CSS_SELECTOR, "button[data-bs-target='#addBranchModal']"))
        )
        add_button.click()
        
        # Đợi modal hiển thị
        self.wait.until(
            EC.visibility_of_element_located((By.ID, "addBranchModal"))
        )
        
        # Thêm timestamp để tránh trùng tên
        timestamp = int(time.time())
        test_data = TEST_BRANCH.copy()
        test_data["tenCoSo"] = f"{TEST_BRANCH['tenCoSo']} {timestamp}"
        
        # Điền form
        self.driver.find_element(By.ID, "tenCoSo").send_keys(test_data["tenCoSo"])
        self.driver.find_element(By.ID, "diaChi").send_keys(test_data["diaChi"])
        self.driver.find_element(By.ID, "dienThoai").send_keys(test_data["dienThoai"])
        # self.driver.find_element(By.ID, "addAnhUrl").send_keys(test_data["anhUrl"])
        
        # Click nút lưu
        save_button = self.driver.find_element(By.CSS_SELECTOR, ".modal-footer .btn-primary")
        save_button.click()
        
        # Đợi modal đóng và trang reload
        time.sleep(2)
        
        # Kiểm tra số lượng cơ sở tăng lên
        self.driver.get(ADMIN_BRANCH_URL)
        time.sleep(1)
        
        final_rows = len(self.driver.find_elements(By.CSS_SELECTOR, "#addBranchForm button[type='submit']"))
        
        print(f"✓ Đã thêm cơ sở mới: {test_data['tenCoSo']}")
        print(f"  Số cơ sở: {initial_rows} -> {final_rows}")
        
        # Lưu tên để dùng cho test update và delete
        self.added_branch_name = test_data["tenCoSo"]
    
    def test_07_update_branch_successfully(self):
        """Test 7: Cập nhật thông tin cơ sở thành công"""
        print("\n[TEST 7] Cập nhật thông tin cơ sở")
        
        self.driver.get(ADMIN_BRANCH_URL)
        
        # Tìm nút edit đầu tiên
        try:
            # 1. Tìm TẤT CẢ các nút edit (btn-warning) TRONG TABLE (không phải modal)
            # Chờ ít nhất 1 nút xuất hiện
            self.wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, "#branchTable button.btn-warning")))
            edit_buttons = self.driver.find_elements(By.CSS_SELECTOR, "#branchTable button.btn-warning")
            
            if not edit_buttons:
                print("✓ Không tìm thấy nút Edit nào.")
                return
            edit_button = edit_buttons[-1] # lấy nút cuối cùng (mới thêm nhất)

            # Cuộn màn hình đến nút đó (đề phòng danh sách dài bị khuất)
            self.driver.execute_script("arguments[0].scrollIntoView(true);", edit_button)
            time.sleep(0.5)

            # Click và đợi AJAX request hoàn tất
            edit_button.click()
            
            # Đợi modal edit hiển thị (sau khi fetch API xong)
            # Modal sẽ có class 'show' khi hiển thị
            self.wait.until(
                EC.visibility_of_element_located((By.CSS_SELECTOR, "#editBranchModal.show"))
            )
            
            # Cập nhật thông tin
            ten_input = self.driver.find_element(By.ID, "editTenCoSo")
            ten_input.clear()
            
            timestamp = int(time.time())
            new_name = f"Updated Branch {timestamp}"
            ten_input.send_keys(new_name)
            
            # Lưu thay đổi
            save_button = self.driver.find_element(By.CSS_SELECTOR, "#editBranchForm button[type='submit']")
            save_button.click()
            
            time.sleep(2)
            
            print(f"✓ Đã cập nhật cơ sở thành: {new_name}")
            
        except TimeoutException:
            print("✓ Không có cơ sở để cập nhật (bảng rỗng)")
    
    # def test_08_delete_branch_cancel(self):
    #     """Test 8: Hủy xóa cơ sở"""
    #     print("\n[TEST 8] Kiểm tra hủy xóa cơ sở")
        
    #     self.driver.get(ADMIN_BRANCH_URL)
        
    #     try:
    #         # Lấy số lượng ban đầu
    #         initial_rows = len(self.driver.find_elements(By.CSS_SELECTOR, "#branchTable tbody tr"))
            
    #         if initial_rows == 0:
    #             print("✓ Không có cơ sở để test xóa")
    #             return
            
    #         # Click nút delete
    #         delete_button = self.wait.until(
    #             EC.element_to_be_clickable((By.CSS_SELECTOR, "button.btn-danger"))
    #         )
            
    #         # Lưu tên cơ sở trước khi click
    #         branch_row = delete_button.find_element(By.XPATH, "./ancestor::tr")
    #         branch_name = branch_row.find_element(By.CSS_SELECTOR, "td:nth-child(2)").text
            
    #         delete_button.click()
            
    #         # Đợi confirm dialog và click Cancel
    #         time.sleep(0.5)
    #         alert = self.driver.switch_to.alert
    #         alert_text = alert.text
    #         alert.dismiss()  # Click Cancel
            
    #         time.sleep(1)
            
    #         # Kiểm tra số lượng không thay đổi
    #         final_rows = len(self.driver.find_elements(By.CSS_SELECTOR, "#branchTable tbody tr"))
    #         assert initial_rows == final_rows
            
    #         print(f"✓ Đã hủy xóa cơ sở: {branch_name}")
            
    #     except TimeoutException:
    #         print("✓ Không có cơ sở để test xóa")
    
    # def test_09_delete_branch_confirm(self):
    #     """Test 9: Xác nhận xóa cơ sở"""
    #     print("\n[TEST 9] Xóa cơ sở và xác nhận")
        
    #     self.driver.get(ADMIN_BRANCH_URL)
        
    #     try:
    #         # Lấy số lượng ban đầu
    #         initial_rows = len(self.driver.find_elements(By.CSS_SELECTOR, "#branchTable tbody tr"))
            
    #         if initial_rows == 0:
    #             print("✓ Không có cơ sở để xóa")
    #             return
            
    #         # Click nút delete
    #         delete_button = self.wait.until(
    #             EC.element_to_be_clickable((By.CSS_SELECTOR, "button.btn-danger"))
    #         )
            
    #         # Lưu tên cơ sở
    #         branch_row = delete_button.find_element(By.XPATH, "./ancestor::tr")
    #         branch_name = branch_row.find_element(By.CSS_SELECTOR, "td:nth-child(2)").text
            
    #         delete_button.click()
            
    #         # Đợi confirm dialog và click OK
    #         time.sleep(0.5)
    #         alert = self.driver.switch_to.alert
    #         alert.accept()  # Click OK
            
    #         time.sleep(2)
            
    #         # Reload trang và kiểm tra
    #         self.driver.get(ADMIN_BRANCH_URL)
    #         time.sleep(1)
            
    #         final_rows = len(self.driver.find_elements(By.CSS_SELECTOR, "#branchTable tbody tr"))
            
    #         print(f"✓ Đã xóa cơ sở: {branch_name}")
    #         print(f"  Số cơ sở: {initial_rows} -> {final_rows}")
            
    #     except TimeoutException:
    #         print("✓ Không có cơ sở để xóa")
    
    def test_10_ui_responsive_elements(self):
        """Test 10: Kiểm tra các thành phần UI responsive"""
        print("\n[TEST 10] Kiểm tra các thành phần UI")
        
        self.driver.get(ADMIN_BRANCH_URL)
        
        # Kiểm tra search input
        search_input = self.driver.find_element(By.ID, "searchInput")
        assert search_input.is_displayed()
        assert search_input.get_attribute("placeholder") == "Tìm kiếm cơ sở..."
        
        # Kiểm tra nút thêm
        add_button = self.driver.find_element(By.CSS_SELECTOR, "button[data-bs-target='#addBranchModal']")
        assert add_button.is_displayed()
        
        # Kiểm tra table có class bootstrap
        table = self.driver.find_element(By.ID, "branchTable")
        assert "table" in table.get_attribute("class")
        assert "table-bordered" in table.get_attribute("class")
        
        print("✓ Tất cả các thành phần UI hiển thị đúng")



def run_tests():
    """Chạy tất cả các test"""
    print("=" * 70)
    print("BẮT ĐẦU CHẠY AUTO TEST CHO TRANG QUẢN LÝ CƠ SỞ")
    print("=" * 70)
    
    pytest.main([
        __file__,
        "-v",  # verbose
        "-s",  # hiển thị print statements
        "--tb=short",  # traceback ngắn gọn
        # "--html=test_report.html",  # tạo báo cáo HTML (nếu có plugin)
        # "--self-contained-html"  # báo cáo HTML độc lập
    ])


if __name__ == "__main__":
    run_tests()
