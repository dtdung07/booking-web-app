<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Đặt Bàn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* --- Cài đặt chung & Font chữ --- */
        body {
            font-family: Arial, sans-serif; /* Hoặc font chữ bạn đang dùng */
            background-color: #f0f0f0; /* Màu nền xám nhạt để làm nổi form */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        /* --- Khung Form chính --- */
        .booking-form-container {
            background-color: #ffffff;
            width: 35%;
            max-width: 50px; /* Chiều rộng tối đa của form */
            min-width: 430px; /* Thêm chiều rộng tối thiểu để form không bị bóp méo */
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);        
        }
        

        /* --- Tiêu đề chính "Đặt bàn" --- */
        .form-title {
            font-size: 2.2rem;
            font-weight: bold;
            color: #275228;
            margin: 0 0 10px 0;
        }

       
        /* --- Các nhóm input (Tên, SĐT,...) --- */
      

        .form-section-title {
            font-size: 1rem;
            font-weight: 500;
            color: #495057;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 10px;
        }
        
        .form-section-title i {
            color: #f39c12;
            font-size: 1.1rem;
        }

        /* Dùng để chứa label và input */
        .form-group {
            margin-bottom: 22px;
        }

        /* === YÊU CẦU 2: LABEL NHỎ VÀ MỜ === */
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.8rem;  /* Nhỏ lại */
            color: #868e96;      /* Làm mờ */
        }
        
        /* Dùng để xếp các input trên cùng một hàng */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            align-items: end; /* Căn tất cả các group xuống đáy */
        }

        /* --- Kiểu dáng chung cho input, select, textarea --- */
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 8px 16px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.81rem;
            color: #495057;
            background-color: #fff;
            box-sizing: border-box;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .form-textarea {
            width: 100%;
            height: 80px;
            padding: 8px 16px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.81rem;
            color: #495057;
            background-color: #fff;
            box-sizing: border-box;
            font-weight: 500;
        }

        /* === YÊU CẦU 1: CHIỀU CAO ĐỀU NHAU === */
        /* Áp dụng chiều cao cố định cho các control trong .form-row */
        .form-row .form-select,
        .form-row .input-with-icon,
        .form-row .quantity-selector {
            height: 35px; /* Chiều cao đồng nhất */
            box-sizing: border-box;
        }
        .form-row .input-with-icon .form-input {
            height: 100%; /* Input bên trong sẽ cao 100% wrapper của nó */
        }


        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #f39c12;
        }
        
        /* Ghi chú mờ bên trong input */
        .form-input::placeholder, .form-textarea::placeholder {
            color: #adb5bd;
            font-weight: 400;
        }
        
        /* Tùy chỉnh mũi tên cho thẻ select */
        .form-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23f39c12' stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 1.1em;
            cursor: pointer;
        }
        
        /* --- Các input đặc biệt --- */
        
        /* Khung chứa input có icon */
        .input-with-icon {
            position: relative;
        }
        .input-with-icon i {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #f39c12;
            pointer-events: none;
            font-size: 1.1rem;
        }
        .input-with-icon .form-input {
            padding-right: 45px;
            cursor: pointer;
        }
        
        /* Nút tăng giảm số lượng */
        .quantity-selector {
            display: flex;
            align-items: center;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background-color: #fff;
            overflow: hidden;
            justify-content: flex-start; /* Thay đổi từ style gốc */
        }

        /* === YÊU CẦU 3: NÚT TĂNG GIẢM ĐỀU NHAU === */
        .quantity-selector button {
            background-color: transparent;
            border: none;
            font-size: 1.1rem; /* Điều chỉnh lại 1 chút */
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
            color: #495057;
            /* Thêm CSS để các nút đều nhau */
            width: 40px; /* Set chiều rộng cố định */
            height: 100%; /* Cao 100% theo cha (đã set 41px) */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* === YÊU CẦU 4: DISPLAY SỐ LƯỢNG DÀI HƠN === */
        .quantity-display {
            font-size: 0.81rem; /* Đồng bộ font-size với input */
            font-weight: 500; /* Đồng bộ font-weight */
            border-left: 1px solid #e9ecef;
            border-right: 1px solid #e9ecef;
            /* Thêm CSS để dài hơn */
            min-width: 50px;
            text-align: center;
            flex-grow: 1; /* Tự động co giãn chiếm không gian còn lại */
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* --- Vùng chứa các nút bấm cuối form --- */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 25px;
            margin-top: 30px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 0.9rem;
            border-radius: 30px;
            cursor: pointer;
            border: none;
            text-transform: uppercase;
        }

        .btn-secondary {
            background-color: transparent;
            color: #6c757d;
        }

        .btn-primary {
            background-color: #f39c12;
            color: black;
        }

        /* === YÊU CẦU 5: CSS CHO CUSTOM DATE PICKER MODAL === */
        .date-picker-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: none; /* Ẩn mặc định */
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .date-picker-overlay.show {
            display: flex; /* Hiện khi có class 'show' */
        }
        .date-picker-modal {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            width: 380px; /* Kích thước vuông vắn */
            overflow: hidden;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .dp-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
        }
        #dp-current-month-year {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            text-transform: capitalize;
        }
        .dp-nav {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 6px;
            color: #555;
        }
        .dp-nav:hover:not(:disabled) {
            background-color: #f9f9f9;
        }
        .dp-nav:disabled {
            color: #ccc;
            cursor: not-allowed;
            background-color: transparent;
        }
        .dp-weekdays, .dp-days-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
        }
        .dp-weekdays {
            padding: 10px 0;
            background-color: #fafafa;
        }
        .dp-weekdays div {
            font-size: 0.8rem;
            font-weight: 600;
            color: #888;
            text-transform: uppercase;
        }
        .dp-days-grid {
            padding: 10px;
            gap: 5px; /* Khoảng cách giữa các ô ngày */
        }
        .dp-days-grid .dp-day {
            font-size: 0.95rem;
            height: 45px; /* Làm cho ô vuông vắn */
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid transparent; /* Thêm border trong suốt để không bị giật layout */
        }
        .dp-days-grid .dp-day:not(.disabled):not(.empty):hover {
            background-color: #fef4e6; /* Màu cam nhạt khi hover */
            color: #f39c12;
        }
        .dp-days-grid .dp-day.today {
            font-weight: bold;
            border-color: #f39c12; /* Viền cam cho ngày hôm nay */
        }
        .dp-days-grid .dp-day.selected {
            background-color: #f39c12;
            color: #fff;
            font-weight: bold;
            border-color: #f39c12;
        }
        .dp-days-grid .dp-day.disabled {
            color: #ddd; /* Ngày trong quá khứ / tương lai xa */
            cursor: not-allowed;
            background-color: #fdfdfd;
        }
        .dp-days-grid .dp-day.empty {
            cursor: default;
            background-color: transparent;
        }
        .dp-footer {
            display: flex;
            justify-content: space-between;
            padding: 15px 20px;
            border-top: 1px solid #eee;
            background-color: #fafafa;
        }
        .dp-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .dp-btn-secondary {
            background-color: #e9ecef;
            color: #495057;
        }
        .dp-btn.dp-btn-primary { /* Đặt tên rõ ràng hơn để tránh xung đột */
            background-color: #f39c12;
            color: #fff;
        }
    </style>
</head>
<body>

    <div class="booking-form-container">
        <h1 class="form-title">Đặt bàn</h1>
        <form>
            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-user"></i>Thông tin của bạn</h3>
                <div class="form-group">
                    <input type="text" class="form-input" placeholder="Tên của bạn">
                </div>
                <div class="form-group">
                    <input type="tel" class="form-input" placeholder="Số điện thoại">
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-calendar-check"></i>Thông tin đặt bàn</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="number-of-people">Số lượng người</label>
                        <div class="quantity-selector">
                            <button type="button" onclick="decreaseQuantity()">-</button>
                            <div class="quantity-display">1</div>
                            <button type="button" onclick="increaseQuantity()">+</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date">Chọn ngày</label>
                        <div class="input-with-icon">
                            <input type="text" class="form-input" id="date-display-input" readonly>
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="time">Chọn giờ</label>
                        <select class="form-select" required>
                            <option value="" selected disabled>Chọn giờ</option>
                            <option value="17:00">17:00</option>
                            <option value="17:30">17:30</option>
                            <option value="18:00">18:00</option>
                            <option value="18:30">18:30</option>
                            <option value="19:00">19:00</option>
                            <option value="19:30">19:30</option>
                            <option value="20:00">20:00</option>
                            <option value="20:30">20:30</option>
                            <option value="21:00">21:00</option>
                        </select>
                    </div>
                </div>
            </div>
            <textarea class="form-textarea" placeholder="Ghi chú"></textarea>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary">Đóng</button>
                <button type="submit" class="btn btn-primary">Đặt bàn ngay</button>
            </div>
        </form>
    </div>

    <div id="datePickerOverlay" class="date-picker-overlay">
        <div id="datePickerModal" class="date-picker-modal">
            <div class="dp-header">
                <button type="button" id="dp-prev-month" class="dp-nav"><i class="fas fa-chevron-left"></i></button>
                <span id="dp-current-month-year"></span>
                <button type="button" id="dp-next-month" class="dp-nav"><i class="fas fa-chevron-right"></i></button>
            </div>
            <div class="dp-weekdays">
                <div>CN</div><div>T2</div><div>T3</div><div>T4</div><div>T5</div><div>T6</div><div>T7</div>
            </div>
            <div id="dp-days-grid" class="dp-days-grid"></div>
            <div class="dp-footer">
                <button type="button" id="dp-today-btn" class="dp-btn dp-btn-secondary">Hôm nay</button>
                <button type="button" id="dp-close-btn" class="dp-btn dp-btn-primary">Đóng</button>
            </div>
        </div>
    </div>


    <script>
        // --- Hàm tăng/giảm số lượng (giữ nguyên) ---
        function decreaseQuantity() {
            const display = document.querySelector('.quantity-display');
            let currentValue = parseInt(display.textContent);
            if (currentValue > 1) {
                display.textContent = currentValue - 1;
            }
        }

        function increaseQuantity() {
            const display = document.querySelector('.quantity-display');
            let currentValue = parseInt(display.textContent);
            if (currentValue < 20) { // Giới hạn tối đa 20 người
                display.textContent = currentValue + 1;
            }
        }

        // === YÊU CẦU 5: JAVASCRIPT CHO DATE PICKER MODAL ===
        document.addEventListener('DOMContentLoaded', function() {
            // --- DOM Elements ---
            const dateInput = document.getElementById('date-display-input');
            const overlay = document.getElementById('datePickerOverlay');
            const modal = document.getElementById('datePickerModal');
            const grid = document.getElementById('dp-days-grid');
            const monthYearDisplay = document.getElementById('dp-current-month-year');
            const prevMonthBtn = document.getElementById('dp-prev-month');
            const nextMonthBtn = document.getElementById('dp-next-month');
            const todayBtn = document.getElementById('dp-today-btn');
            const closeBtn = document.getElementById('dp-close-btn');

            // --- Date Constants ---
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Chuẩn hóa về 0 giờ để so sánh chính xác

            const maxDate = new Date(today);
            maxDate.setMonth(maxDate.getMonth() + 2); // Giới hạn 2 tháng kể từ hôm nay

            // --- State Variables ---
            let selectedDate = new Date(today); // Mặc định chọn hôm nay
            let currentCalendarDate = new Date(today); // Tháng & Năm đang hiển thị trên lịch

            // --- Helper: Format date sang "DD tháng MM" ---
            function formatDateForInput(date) {
                const monthName = date.toLocaleString('vi-VN', { month: 'long' });
                const day = ('0' + date.getDate()).slice(-2);
                return `${day} ${monthName}`; // VD: "07 tháng 9"
            }
            
            // Set giá trị ban đầu cho input
            dateInput.value = formatDateForInput(today);
            
            // --- Main Render Function ---
            function renderCalendar(year, month) {
                currentCalendarDate.setFullYear(year, month, 1); // Set ngày về mùng 1 của tháng cần vẽ
                grid.innerHTML = ''; // Xóa lịch cũ
                
                // Cập nhật tiêu đề Tháng/Năm
                monthYearDisplay.textContent = currentCalendarDate.toLocaleString('vi-VN', { month: 'long', year: 'numeric' });
                
                const firstDayOfMonth = new Date(year, month, 1).getDay(); // 0=Chủ Nhật, 1=T2...
                const daysInMonth = new Date(year, month + 1, 0).getDate(); // Lấy ngày cuối cùng của tháng
                
                // 1. Thêm các ô trống (empty) cho ngày trước mùng 1
                for (let i = 0; i < firstDayOfMonth; i++) {
                    grid.insertAdjacentHTML('beforeend', '<div class="dp-day empty"></div>');
                }
                
                // 2. Thêm các ô ngày trong tháng
                for (let day = 1; day <= daysInMonth; day++) {
                    const cellDate = new Date(year, month, day);
                    const cell = document.createElement('div');
                    cell.className = 'dp-day';
                    cell.textContent = day;
                    cell.dataset.date = cellDate.toISOString(); // Lưu trữ ngày đầy đủ

                    let classes = [];

                    // Kiểm tra (vô hiệu hóa) nếu ngày < hôm nay HOẶC ngày > ngày tối đa
                    if (cellDate < today || cellDate > maxDate) {
                        classes.push('disabled');
                    }
                    
                    // Đánh dấu ngày hôm nay
                    if (cellDate.getTime() === today.getTime()) {
                        classes.push('today');
                    }
                    
                    // Đánh dấu ngày đang được chọn
                    if (selectedDate && cellDate.getTime() === selectedDate.getTime()) {
                        classes.push('selected');
                    }
                    
                    cell.classList.add(...classes);
                    grid.appendChild(cell);
                }

                // 3. Kiểm tra trạng thái nút điều hướng
                // Vô hiệu hóa nút LÙI nếu tháng đang xem là tháng hiện tại
                prevMonthBtn.disabled = (year === today.getFullYear() && month === today.getMonth());
                
                // Vô hiệu hóa nút TIẾN nếu tháng đang xem là tháng tối đa
                nextMonthBtn.disabled = (year === maxDate.getFullYear() && month === maxDate.getMonth());
            }

            // --- Event Listeners ---
            
            // 1. Mở Modal khi nhấn vào input ngày
            dateInput.addEventListener('click', () => {
                // Khi mở, luôn vẽ lịch của tháng đang được chọn
                renderCalendar(selectedDate.getFullYear(), selectedDate.getMonth());
                overlay.classList.add('show');
            });

            // 2. Đóng Modal
            function closeModal() {
                overlay.classList.remove('show');
            }
            closeBtn.addEventListener('click', closeModal);
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) closeModal(); // Chỉ đóng khi nhấn vào lớp mờ, không phải modal
            });

            // 3. Điều hướng Tháng
            prevMonthBtn.addEventListener('click', () => {
                renderCalendar(currentCalendarDate.getFullYear(), currentCalendarDate.getMonth() - 1);
            });
            nextMonthBtn.addEventListener('click', () => {
                renderCalendar(currentCalendarDate.getFullYear(), currentCalendarDate.getMonth() + 1);
            });
            
            // 4. Nút "Hôm nay"
            todayBtn.addEventListener('click', () => {
                selectedDate = new Date(today);
                dateInput.value = formatDateForInput(selectedDate);
                closeModal();
            });

            // 5. Chọn một ngày từ Lịch
            grid.addEventListener('click', (e) => {
                const target = e.target.closest('.dp-day');
                // Bỏ qua nếu nhấn vào ô trống hoặc ô bị vô hiệu hóa
                if (!target || target.classList.contains('empty') || target.classList.contains('disabled')) {
                    return; 
                }
                
                selectedDate = new Date(target.dataset.date); // Lấy ngày từ data-date
                dateInput.value = formatDateForInput(selectedDate); // Cập nhật input
                closeModal(); // Đóng modal
            });
        });
    </script>
</body>
</html>