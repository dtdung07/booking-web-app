<?php
$title = 'Đăng nhập - Quán Nhậu Tự Do';
$additional_head = '<link rel="stylesheet" href="' . asset('css/pages/auth.css') . '">';
?>

<div class="auth-container">
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo-section">
                    <h1 class="brand-title">Hệ thống quản lý</h1>
                    <!-- <p class="brand-subtitle">Đăng nhập vào tài khoản của bạn</p> -->
                </div>
            </div>

            <div class="auth-body">
                <!-- Hiển thị thông báo lỗi -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Hiển thị thông báo thành công -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <form class="auth-form" action="?page=auth&action=authenticate" method="POST">
                    <div class="form-group">
                        <label for="username" class="form-label">
                            <i class="fas fa-user"></i>
                            Tên đăng nhập
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="form-input"
                            placeholder="Nhập tên đăng nhập"
                            value="<?php echo htmlspecialchars($_SESSION['old_input']['username'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Mật khẩu
                        </label>
                        <div class="password-input-wrapper">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input"
                                placeholder="Nhập mật khẩu"
                                required
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="password-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" name="remember_me" value="1">
                            <span class="checkmark"></span>
                            Ghi nhớ đăng nhập
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">
                        <i class="fas fa-sign-in-alt"></i>
                        Đăng nhập
                    </button>
                </form>

                <div class="system-info">
                    <p><strong>Thông tin hệ thống:</strong></p>
                    <p>Chỉ nhân viên và quản trị viên mới có thể đăng nhập</p>
                    <p>Liên hệ quản trị viên để được cấp tài khoản</p>
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
</script>

<?php
// Xóa old input sau khi hiển thị
unset($_SESSION['old_input']);
?>
