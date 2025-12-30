"""
Script tiện ích để chạy các test scenarios khác nhau
"""

import sys
import subprocess


def print_menu():
    """Hiển thị menu lựa chọn"""
    print("\n" + "="*70)
    print("🚀 AUTO TEST - QUẢN LÝ CƠ SỞ")
    print("="*70)
    print("\nChọn loại test bạn muốn chạy:\n")
    print("1. Chạy TẤT CẢ test (đầy đủ)")
    print("2. Chạy test UI/UX (chỉ giao diện)")
    print("3. Chạy test CRUD (Thêm/Sửa/Xóa)")
    print("4. Chạy test API")
    print("5. Chạy test cụ thể")
    print("6. Chạy với báo cáo HTML")
    print("7. Chạy ở chế độ HEADLESS (nền)")
    print("0. Thoát")
    print("\n" + "="*70)


def run_command(cmd):
    """Chạy lệnh và hiển thị output"""
    print(f"\n▶️ Đang chạy: {cmd}\n")
    subprocess.run(cmd, shell=True)


def main():
    while True:
        print_menu()
        choice = input("\nNhập lựa chọn của bạn (0-7): ").strip()
        
        if choice == "1":
            # Chạy tất cả test
            run_command("pytest test_branch_management.py -v -s")
        
        elif choice == "2":
            # Chạy test UI/UX
            tests = [
                "test_01_page_load_successfully",
                "test_02_display_branch_list",
                "test_03_search_functionality",
                "test_04_open_add_branch_modal",
                "test_10_ui_responsive_elements"
            ]
            test_list = " or ".join(tests)
            run_command(f'pytest test_branch_management.py -v -s -k "{test_list}"')
        
        elif choice == "3":
            # Chạy test CRUD
            tests = [
                "test_05_add_branch_validation",
                "test_06_add_branch_successfully",
                "test_07_update_branch_successfully",
                "test_08_delete_branch_cancel",
                "test_09_delete_branch_confirm"
            ]
            test_list = " or ".join(tests)
            run_command(f'pytest test_branch_management.py -v -s -k "{test_list}"')
        
        elif choice == "4":
            # Chạy test API
            run_command("pytest test_branch_management.py::TestBranchAPI -v -s")
        
        elif choice == "5":
            # Chạy test cụ thể
            print("\nDanh sách test có thể chạy:")
            print("  - test_01_page_load_successfully")
            print("  - test_02_display_branch_list")
            print("  - test_03_search_functionality")
            print("  - test_04_open_add_branch_modal")
            print("  - test_05_add_branch_validation")
            print("  - test_06_add_branch_successfully")
            print("  - test_07_update_branch_successfully")
            print("  - test_08_delete_branch_cancel")
            print("  - test_09_delete_branch_confirm")
            print("  - test_10_ui_responsive_elements")
            print("  - test_api_get_data")
            print("  - test_api_add_branch")
            
            test_name = input("\nNhập tên test: ").strip()
            run_command(f"pytest test_branch_management.py::{test_name} -v -s")
        
        elif choice == "6":
            # Chạy với báo cáo HTML
            run_command("pytest test_branch_management.py -v -s --html=test_report.html --self-contained-html")
            print("\n✅ Báo cáo đã được tạo: test_report.html")
            print("   Mở file này trong trình duyệt để xem chi tiết")
        
        elif choice == "7":
            # Chạy headless
            print("\n⚠️ Chế độ headless: Trình duyệt sẽ chạy ẩn")
            print("   Chỉnh sửa test_config.py để thay đổi cài đặt này vĩnh viễn")
            
            confirm = input("Tiếp tục? (y/n): ").strip().lower()
            if confirm == 'y':
                # Tạm thời set HEADLESS=True
                import test_config
                original_headless = test_config.HEADLESS
                test_config.HEADLESS = True
                
                run_command("pytest test_branch_management.py -v -s")
                
                # Restore setting
                test_config.HEADLESS = original_headless
        
        elif choice == "0":
            print("\n👋 Tạm biệt!\n")
            sys.exit(0)
        
        else:
            print("\n❌ Lựa chọn không hợp lệ. Vui lòng chọn lại.")
        
        # Hỏi có muốn tiếp tục không
        input("\n\nNhấn Enter để quay lại menu...")


if __name__ == "__main__":
    try:
        main()
    except KeyboardInterrupt:
        print("\n\n👋 Đã dừng chương trình. Tạm biệt!\n")
        sys.exit(0)
