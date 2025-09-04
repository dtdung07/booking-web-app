<?php
$title = 'Đổi mật khẩu - Quán Nhậu Tự Do';
$additional_head = '<link rel="stylesheet" href="' . asset('css/pages/profile.css') . '">';
?>

<div class="profile-container">
    <div class="container">
        <div class="profile-wrapper">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-avatar">
                    <div class="avatar-circle">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
                    <p><?php echo htmlspecialchars($user['username']); ?></p>
                    <span class="user-role"><?php echo ucfirst($user['role']); ?></span>
                </div>
                
                <nav class="profile-nav">
                    <ul>
                        <li><a href="?page=profile"><i class="fas fa-user"></i> Thông tin cá nhân</a></li>
                        <li><a href="?page=profile&action=changePassword" class="active"><i class="fas fa-lock"></i> Đổi mật khẩu</a></li>
                        <li><a href="?page=bookings"><i class="fas fa-calendar-alt"></i> Quản lý đặt bàn</a></li>
                        <?php if ($user['role'] === 'admin'): ?>
                            <li><a href="?page=admin"><i class="fas fa-cog"></i> Quản trị hệ thống</a></li>
                        <?php endif; ?>
                        <li><a href="?page=auth&action=logout" class="logout-link"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="profile-content">
                <div class="content-header">
                    <h1><i class="fas fa-lock"></i> Đổi mật khẩu</h1>
                    <p>Cập nhật mật khẩu để bảo mật tài khoản</p>
                </div>

                <!-- Hiển thị thông báo -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <ul class="error-list">
                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php unset($_SESSION['errors']); ?>
                <?php endif; ?>

                <div class="profile-info-card">
                    <div class="card-header">
                        <h2><i class="fas fa-key"></i> Thay đổi mật khẩu</h2>
                    </div>
                    
                    <div class="card-body">
                        <form action="?page=auth&action=changePassword" method="POST" class="password-form">
                            <div class="form-group">
                                <label for="current_password" class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Mật khẩu hiện tại
                                </label>
                                <div class="password-input-wrapper">
                                    <input 
                                        type="password" 
                                        id="current_password" 
                                        name="current_password" 
                                        class="form-input"
                                        placeholder="Nhập mật khẩu hiện tại"
                                        required
                                    >
                                    <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                        <i class="fas fa-eye" id="current_password-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="new_password" class="form-label">
                                    <i class="fas fa-key"></i>
                                    Mật khẩu mới
                                </label>
                                <div class="password-input-wrapper">
                                    <input 
                                        type="password" 
                                        id="new_password" 
                                        name="new_password" 
                                        class="form-input"
                                        placeholder="Nhập mật khẩu mới"
                                        minlength="6"
                                        required
                                    >
                                    <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                        <i class="fas fa-eye" id="new_password-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength" id="password-strength"></div>
                            </div>

                            <div class="form-group">
                                <label for="confirm_password" class="form-label">
                                    <i class="fas fa-check"></i>
                                    Xác nhận mật khẩu mới
                                </label>
                                <div class="password-input-wrapper">
                                    <input 
                                        type="password" 
                                        id="confirm_password" 
                                        name="confirm_password" 
                                        class="form-input"
                                        placeholder="Nhập lại mật khẩu mới"
                                        minlength="6"
                                        required
                                    >
                                    <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                        <i class="fas fa-eye" id="confirm_password-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    Cập nhật mật khẩu
                                </button>
                                <a href="?page=profile" class="btn btn-secondary">
                                    <i class="fas fa-times"></i>
                                    Hủy bỏ
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security Tips -->
                <div class="security-tips">
                    <h2><i class="fas fa-shield-alt"></i> Lời khuyên bảo mật</h2>
                    
                    <div class="tips-grid">
                        <div class="tip-item">
                            <div class="tip-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div class="tip-content">
                                <h4>Mật khẩu mạnh</h4>
                                <p>Sử dụng ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt</p>
                            </div>
                        </div>
                        
                        <div class="tip-item">
                            <div class="tip-icon">
                                <i class="fas fa-user-secret"></i>
                            </div>
                            <div class="tip-content">
                                <h4>Bảo mật tài khoản</h4>
                                <p>Không chia sẻ mật khẩu với người khác và đăng xuất sau khi sử dụng</p>
                            </div>
                        </div>
                        
                        <div class="tip-item">
                            <div class="tip-icon">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                            <div class="tip-content">
                                <h4>Thay đổi định kỳ</h4>
                                <p>Nên thay đổi mật khẩu định kỳ để đảm bảo an toàn tối đa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById(inputId + '-eye');
    
    if (input.type === 'password') {
        input.type = 'text';
        eye.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        eye.className = 'fas fa-eye';
    }
}

// Password strength checker
document.getElementById('new_password').addEventListener('input', function() {
    const password = this.value;
    const strengthDiv = document.getElementById('password-strength');
    
    let strength = 0;
    let feedback = [];
    
    if (password.length >= 6) strength++;
    else feedback.push('Ít nhất 6 ký tự');
    
    if (password.match(/[a-z]/)) strength++;
    else feedback.push('Chữ thường');
    
    if (password.match(/[A-Z]/)) strength++;
    else feedback.push('Chữ hoa');
    
    if (password.match(/[0-9]/)) strength++;
    else feedback.push('Số');
    
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    else feedback.push('Ký tự đặc biệt');
    
    const strengthLabels = ['Rất yếu', 'Yếu', 'Trung bình', 'Mạnh', 'Rất mạnh'];
    const strengthClasses = ['very-weak', 'weak', 'medium', 'strong', 'very-strong'];
    
    if (password.length > 0) {
        strengthDiv.className = `password-strength ${strengthClasses[strength - 1]}`;
        strengthDiv.textContent = `Độ mạnh: ${strengthLabels[strength - 1]}`;
        if (feedback.length > 0 && strength < 4) {
            strengthDiv.textContent += ` (Cần: ${feedback.join(', ')})`;
        }
    } else {
        strengthDiv.className = 'password-strength';
        strengthDiv.textContent = '';
    }
});

// Confirm password validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('new_password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword.length > 0) {
        if (password === confirmPassword) {
            this.style.borderColor = 'var(--success-color)';
        } else {
            this.style.borderColor = 'var(--error-color)';
        }
    } else {
        this.style.borderColor = '';
    }
});

// Auto hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
});

// Form validation before submit
document.querySelector('.password-form').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('Mật khẩu xác nhận không khớp!');
        return false;
    }
});
</script>
