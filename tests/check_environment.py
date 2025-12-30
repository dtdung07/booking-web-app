"""
Script kiểm tra môi trường test đã sẵn sàng chưa
Chạy file này trước khi chạy test chính
"""

import sys

def check_python_version():
    """Kiểm tra phiên bản Python"""
    print("🔍 Kiểm tra Python...")
    version = sys.version_info
    if version.major >= 3 and version.minor >= 8:
        print(f"   ✓ Python {version.major}.{version.minor}.{version.micro}")
        return True
    else:
        print(f"   ❌ Python {version.major}.{version.minor} (cần >= 3.8)")
        return False

def check_packages():
    """Kiểm tra các package cần thiết"""
    print("\n🔍 Kiểm tra packages...")
    
    packages = {
        'selenium': 'Selenium WebDriver',
        'pytest': 'pytest Framework',
        'requests': 'HTTP Requests',
    }
    
    all_installed = True
    
    for package, name in packages.items():
        try:
            __import__(package)
            print(f"   ✓ {name}")
        except ImportError:
            print(f"   ❌ {name} - CHƯA CÀI ĐẶT")
            all_installed = False
    
    return all_installed

def check_webdriver():
    """Kiểm tra WebDriver"""
    print("\n🔍 Kiểm tra WebDriver...")
    
    try:
        from selenium import webdriver
        from selenium.webdriver.chrome.options import Options
        
        chrome_options = Options()
        chrome_options.add_argument("--headless")
        chrome_options.add_argument("--disable-gpu")
        chrome_options.add_argument("--no-sandbox")
        
        driver = webdriver.Chrome(options=chrome_options)
        driver.quit()
        
        print("   ✓ ChromeDriver hoạt động")
        return True
    except Exception as e:
        print(f"   ❌ ChromeDriver - LỖI: {str(e)[:50]}")
        print("   💡 Cập nhật Chrome hoặc cài webdriver-manager")
        return False

def check_xampp():
    """Kiểm tra XAMPP đang chạy"""
    print("\n🔍 Kiểm tra XAMPP...")
    
    try:
        import requests
        from test_config import BASE_URL
        
        response = requests.get(BASE_URL, timeout=5)
        if response.status_code == 200:
            print(f"   ✓ XAMPP đang chạy ({BASE_URL})")
            return True
        else:
            print(f"   ⚠️ XAMPP phản hồi mã {response.status_code}")
            return False
    except Exception as e:
        print(f"   ❌ Không kết nối được XAMPP")
        print(f"   💡 Kiểm tra Apache đã chạy chưa")
        return False

def check_test_page():
    """Kiểm tra trang test có tồn tại không"""
    print("\n🔍 Kiểm tra trang quản lý cơ sở...")
    
    try:
        import requests
        from test_config import ADMIN_BRANCH_URL
        
        response = requests.get(ADMIN_BRANCH_URL, timeout=5)
        if response.status_code == 200:
            print(f"   ✓ Trang quản lý cơ sở OK")
            return True
        else:
            print(f"   ❌ Trang trả về mã {response.status_code}")
            return False
    except Exception as e:
        print(f"   ❌ Không truy cập được trang")
        print(f"   💡 Kiểm tra đường dẫn trong test_config.py")
        return False

def main():
    """Chạy tất cả các kiểm tra"""
    print("=" * 70)
    print("🚀 KIỂM TRA MÔI TRƯỜNG AUTO TEST")
    print("=" * 70)
    
    results = []
    
    # Chạy các kiểm tra
    results.append(("Python Version", check_python_version()))
    results.append(("Packages", check_packages()))
    results.append(("WebDriver", check_webdriver()))
    results.append(("XAMPP", check_xampp()))
    results.append(("Test Page", check_test_page()))
    
    # Tổng kết
    print("\n" + "=" * 70)
    print("📊 KẾT QUẢ KIỂM TRA")
    print("=" * 70)
    
    passed = sum(1 for _, result in results if result)
    total = len(results)
    
    for name, result in results:
        status = "✓ PASS" if result else "❌ FAIL"
        print(f"   {status:10} | {name}")
    
    print("=" * 70)
    print(f"\n🎯 Kết quả: {passed}/{total} kiểm tra thành công")
    
    if passed == total:
        print("\n✅ MÔI TRƯỜNG ĐÃ SẴN SÀNG!")
        print("   Bạn có thể chạy test ngay bây giờ:")
        print("   → python test_branch_management.py")
        print("   → python run_tests.py")
        return 0
    else:
        print("\n⚠️ MÔI TRƯỜNG CHƯA SẴN SÀNG!")
        print("\n📝 Hành động cần làm:")
        
        if not results[1][1]:  # Packages
            print("   1. Cài đặt packages: pip install -r requirements.txt")
        
        if not results[2][1]:  # WebDriver
            print("   2. Cập nhật Chrome browser lên phiên bản mới nhất")
        
        if not results[3][1]:  # XAMPP
            print("   3. Khởi động XAMPP (Apache + MySQL)")
        
        if not results[4][1]:  # Test page
            print("   4. Kiểm tra URL trong test_config.py")
        
        print("\n📖 Xem hướng dẫn chi tiết: README_TESTING.md")
        return 1

if __name__ == "__main__":
    try:
        exit_code = main()
        print("\n")
        sys.exit(exit_code)
    except KeyboardInterrupt:
        print("\n\n⚠️ Đã hủy kiểm tra.\n")
        sys.exit(1)
    except Exception as e:
        print(f"\n\n❌ Lỗi: {e}\n")
        sys.exit(1)
