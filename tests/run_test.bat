@echo off
chcp 65001 >nul
echo ========================================
echo 🚀 AUTO TEST - QUẢN LÝ CƠ SỞ
echo ========================================
echo.

REM Kiểm tra Python đã cài đặt chưa
python --version >nul 2>&1
if errorlevel 1 (
    echo ❌ Python chưa được cài đặt!
    echo Vui lòng tải Python tại: https://www.python.org/downloads/
    pause
    exit /b 1
)

echo ✓ Python đã cài đặt
echo.

REM Kiểm tra các package đã cài chưa
echo Đang kiểm tra các package cần thiết...
python -c "import selenium" >nul 2>&1
if errorlevel 1 (
    echo.
    echo 📦 Đang cài đặt các package cần thiết...
    echo.
    pip install -r requirements.txt
    if errorlevel 1 (
        echo.
        echo ❌ Lỗi khi cài đặt packages!
        pause
        exit /b 1
    )
    echo.
    echo ✓ Đã cài đặt thành công!
)

echo.
echo ========================================
echo Bạn muốn chạy test như thế nào?
echo ========================================
echo.
echo 1. Menu tương tác (khuyến nghị)
echo 2. Chạy tất cả test ngay
echo 3. Chạy với báo cáo HTML
echo.
set /p choice="Nhập lựa chọn (1-3): "

if "%choice%"=="1" (
    python run_tests.py
) else if "%choice%"=="2" (
    python test_branch_management.py
) else if "%choice%"=="3" (
    pytest test_branch_management.py -v -s --html=test_report.html --self-contained-html
    echo.
    echo ✅ Báo cáo đã được tạo: test_report.html
    start test_report.html
) else (
    echo.
    echo ❌ Lựa chọn không hợp lệ!
)

echo.
pause
