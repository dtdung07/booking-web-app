/**
 * JavaScript cho hệ thống đặt bàn nhà hàng
 */

$(document).ready(function() {
    // Khởi tạo các thành phần
    initializeDatePicker();
    initializeFormValidation();
    initializeBookingForm();
    initializeMenuFilter();
    initializeScrollEffects();
    
    // Auto hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});

/**
 * Khởi tạo date picker
 */
function initializeDatePicker() {
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    $('input[type="date"]').attr('min', today);
    
    // Set maximum date to 30 days from now
    const maxDate = new Date();
    maxDate.setDate(maxDate.getDate() + 30);
    $('input[type="date"]').attr('max', maxDate.toISOString().split('T')[0]);
}

/**
 * Validation form
 */
function initializeFormValidation() {
    // Phone number validation
    $('input[type="tel"], input[name*="phone"]').on('input', function() {
        const phone = $(this).val().replace(/\D/g, '');
        if (phone.length > 0) {
            // Format: 0123 456 789
            let formatted = phone.replace(/(\d{4})(\d{3})(\d{3})/, '$1 $2 $3');
            $(this).val(formatted);
        }
    });
    
    // Email validation
    $('input[type="email"]').on('blur', function() {
        const email = $(this).val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            $(this).addClass('is-invalid');
            if (!$(this).siblings('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Email không hợp lệ</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').remove();
        }
    });
    
    // Form submission validation
    $('form').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val().trim()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            showAlert('Vui lòng điền đầy đủ thông tin bắt buộc!', 'error');
        }
    });
}

/**
 * Xử lý form đặt bàn
 */
function initializeBookingForm() {
    // Check availability when date/time changes
    $('#booking_date, #booking_time, #guests').on('change', function() {
        checkTableAvailability();
    });
    
    // Table selection
    $(document).on('click', '.table-option', function() {
        $('.table-option').removeClass('selected');
        $(this).addClass('selected');
        
        const tableId = $(this).data('table-id');
        $('input[name="table_id"]').val(tableId);
        
        // Show booking summary
        updateBookingSummary();
    });
    
    // Guest count validation
    $('#guests').on('change', function() {
        const guests = parseInt($(this).val());
        if (guests < 1) {
            $(this).val(1);
        } else if (guests > 20) {
            $(this).val(20);
            showAlert('Số lượng khách tối đa là 20 người. Vui lòng liên hệ trực tiếp để đặt bàn cho nhóm lớn hơn.', 'warning');
        }
    });
}

/**
 * Kiểm tra bàn trống
 */
function checkTableAvailability() {
    const date = $('#booking_date').val();
    const time = $('#booking_time').val();
    const guests = $('#guests').val();
    
    if (!date || !time || !guests) {
        return;
    }
    
    // Show loading
    $('#available-tables').html('<div class="text-center"><div class="loading-spinner"></div> Đang kiểm tra bàn trống...</div>');
    
    $.ajax({
        url: 'index.php?page=booking&action=checkAvailability',
        method: 'POST',
        data: {
            booking_date: date,
            booking_time: time,
            guests: guests
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayAvailableTables(response.tables);
            } else {
                $('#available-tables').html('<div class="alert alert-warning">Không có bàn trống trong thời gian này.</div>');
            }
        },
        error: function() {
            $('#available-tables').html('<div class="alert alert-danger">Có lỗi xảy ra khi kiểm tra bàn trống.</div>');
        }
    });
}

/**
 * Hiển thị danh sách bàn trống
 */
function displayAvailableTables(tables) {
    if (tables.length === 0) {
        $('#available-tables').html('<div class="alert alert-warning">Không có bàn trống phù hợp.</div>');
        return;
    }
    
    let html = '<div class="row">';
    tables.forEach(function(table) {
        html += `
            <div class="col-md-4 mb-3">
                <div class="table-option card" data-table-id="${table.id}">
                    <div class="card-body text-center">
                        <div class="table-number">${table.table_number}</div>
                        <div class="table-info">
                            <small class="text-muted">Sức chứa: ${table.capacity} người</small><br>
                            <small class="text-muted">Vị trí: ${table.location}</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    
    $('#available-tables').html(html);
}

/**
 * Cập nhật tóm tắt đặt bàn
 */
function updateBookingSummary() {
    const selectedTable = $('.table-option.selected');
    if (selectedTable.length === 0) return;
    
    const tableNumber = selectedTable.find('.table-number').text();
    const date = $('#booking_date').val();
    const time = $('#booking_time').val();
    const guests = $('#guests').val();
    
    const summary = `
        <div class="booking-summary-item">
            <strong>Bàn:</strong> ${tableNumber}
        </div>
        <div class="booking-summary-item">
            <strong>Ngày:</strong> ${formatDate(date)}
        </div>
        <div class="booking-summary-item">
            <strong>Thời gian:</strong> ${time}
        </div>
        <div class="booking-summary-item">
            <strong>Số khách:</strong> ${guests} người
        </div>
    `;
    
    $('#booking-summary').html(summary);
}

/**
 * Khởi tạo bộ lọc menu
 */
function initializeMenuFilter() {
    // Category filter
    $('.category-filter').on('click', function(e) {
        e.preventDefault();
        
        const category = $(this).data('category');
        
        // Update active state
        $('.category-filter').removeClass('active');
        $(this).addClass('active');
        
        // Filter dishes
        if (category === 'all') {
            $('.menu-item').show();
        } else {
            $('.menu-item').hide();
            $(`.menu-item[data-category="${category}"]`).show();
        }
    });
    
    // Search filter
    $('#menu-search').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        
        $('.menu-item').each(function() {
            const dishName = $(this).find('.dish-name').text().toLowerCase();
            const dishDesc = $(this).find('.dish-description').text().toLowerCase();
            
            if (dishName.includes(searchTerm) || dishDesc.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
}

/**
 * Khởi tạo hiệu ứng cuộn
 */
function initializeScrollEffects() {
    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 70
            }, 500);
        }
    });
    
    // Animate on scroll
    $(window).on('scroll', function() {
        $('.fade-in').each(function() {
            const elementTop = $(this).offset().top;
            const elementBottom = elementTop + $(this).outerHeight();
            const viewportTop = $(window).scrollTop();
            const viewportBottom = viewportTop + $(window).height();
            
            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $(this).addClass('animated');
            }
        });
    });
}

/**
 * Hiển thị thông báo
 */
function showAlert(message, type = 'info') {
    const alertClass = type === 'error' ? 'alert-danger' : `alert-${type}`;
    const alert = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Insert alert at the top of main content
    $('.main-content').prepend(alert);
    
    // Auto hide after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
}

/**
 * Format date to Vietnamese format
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    const days = ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'];
    const months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    
    const dayName = days[date.getDay()];
    const day = date.getDate().toString().padStart(2, '0');
    const month = months[date.getMonth()];
    const year = date.getFullYear();
    
    return `${dayName}, ${day}/${month}/${year}`;
}

/**
 * Format currency
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

/**
 * Validate Vietnamese phone number
 */
function validateVietnamesePhone(phone) {
    const phoneRegex = /^(0|\+84)[3|5|7|8|9][0-9]{8}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
}

/**
 * Debounce function
 */
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction() {
        const context = this;
        const args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

/**
 * Loading state management
 */
function setLoadingState(element, isLoading) {
    if (isLoading) {
        element.prop('disabled', true);
        element.html('<span class="loading-spinner"></span> Đang xử lý...');
    } else {
        element.prop('disabled', false);
        element.html(element.data('original-text') || 'Gửi');
    }
}

/**
 * Copy to clipboard
 */
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            showAlert('Đã sao chép vào clipboard!', 'success');
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showAlert('Đã sao chép vào clipboard!', 'success');
    }
}

// Global error handler
window.addEventListener('error', function(e) {
    console.error('JavaScript Error:', e.error);
    // You can send error to server here
});

// Handle AJAX errors globally
$(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
    console.error('AJAX Error:', thrownError);
    if (jqXHR.status === 500) {
        showAlert('Có lỗi xảy ra trên server. Vui lòng thử lại sau.', 'error');
    } else if (jqXHR.status === 404) {
        showAlert('Không tìm thấy trang yêu cầu.', 'error');
    }
});
