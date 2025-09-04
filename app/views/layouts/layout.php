<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Quán Nhậu Tự Do'; ?></title>
   
    <!-- Critical CSS - Design tokens and variables (highest priority) -->
    <link rel="preload" href="<?php echo asset('css/constants.css'); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Layout component styles -->
    <link rel="stylesheet" href="<?php echo asset('css/layout/header.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/layout/footer.css'); ?>">
    
    <!-- Component styles -->
    <link rel="stylesheet" href="<?php echo asset('css/components/buttons.css'); ?>">
    
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="<?php echo asset('css/pages/home.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/pages/menu.css'); ?>">
    
    <!-- External CSS (lowest priority) -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    
    <!-- Performance optimization script -->
    <script>
        // Fallback for preload support
        (function() {
            var links = document.querySelectorAll('link[rel="preload"][as="style"]');
            for (var i = 0; i < links.length; i++) {
                links[i].addEventListener('load', function() {
                    this.rel = 'stylesheet';
                });
            }
        })();
    </script>
    
    <!-- Page-specific head content -->
    <?php if (isset($additional_head)): ?>
        <?php echo $additional_head; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Include Header Component -->
    <?php include 'header.php'; ?>
    
    <!-- Main Content -->
    <main>
        <?php echo $content; ?>
    </main>
    
    <!-- Include Footer Component -->
    <?php include 'footer.php'; ?>
    
    <!-- JavaScript files -->
    <script src="<?php echo asset('js/script.js'); ?>"></script>
    
    <!-- Page-specific scripts -->
    <?php if (isset($additional_scripts)): ?>
        <?php echo $additional_scripts; ?>
    <?php endif; ?>
</body>
</html>
