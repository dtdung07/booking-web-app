<?php
include __DIR__ . "/connect.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm mới nhân viên</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
           
        }
        nav{
            background-color: #4A7C59;
            padding: 20px;
        }

        a{
            color: white !important;
            padding: 10px;
            text-decoration: none !important;
        }

    </style>
</head>
<body>
    <nav>
        <a href="index.php?action=view">Danh sách món ăn</a>
        <a href="index.php?action=create">Thêm mới món ăn</a>
    </nav>

    <?php
        if(isset($_GET['action'])){
            switch ($_GET['action']) {
                case 'view':
                    include __DIR__ . "/view.php";
                    break;
                case 'create':
                    include __DIR__ . "/create.php";
                    break;
                case 'update':
                    include __DIR__ . "/update.php";
                    break;
                case 'delete':
                    include "process-delete.php";
                    break;
                case 'process-update':
                    include "process-update.php";
                    break;
                default:
                    include "view.php";
                    break;
            }
        }
    ?>
</body>
</html>