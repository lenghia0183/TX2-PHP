<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật thông tin sinh viên</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        a {
            text-decoration: none;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f0f0f0; /* Màu nền tổng thể của trang */
        }

        .wrapper {
            margin-top: 5%;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
        }

        form {
            width: 40%;
            margin: 0 auto;
            text-align: left; /* Canh trái cho nội dung form */
        }

        label {
            display: block;
            margin: 10px 0;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            margin-bottom: 10px;
            display: inline-block;
            margin-right: 10px;
        }

        input[type="submit"],
        a {
            padding: 8px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        a {
            margin-top: 10px;
            display: inline-block;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 20px;
        }

        .message p {
            font-weight: bold;
            color: green;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1>Cập nhật thông tin sinh viên</h1>
        <form action="" method="post" enctype="multipart/form-data">
        <?php
                require_once 'config.php';
                $connect = mysqli_connect(HOST, ACCOUNT_NAME, PASSWORD, DATABASE_NAME);
            
                if (!$connect) {
                    echo "<p>Kết nối thất bại</p>";
                } else {
                    if (isset($_POST['submit'])) {
                        $primaryFieldValue = $_POST[PRIMARY_FIELD_KEY];
                        $setValues = [];
                        foreach ($columns as $col => $colName) {
                            if ($col != IMAGE_FIELD_KEY) {
                                $setValues[] = "$col='" . $_POST[$col] . "'";
                            }
                        }
                        
                        // Kiểm tra xem người dùng đã tải lên ảnh mới hay không
                        if(isset($_FILES[IMAGE_FIELD_KEY]) && $_FILES[IMAGE_FIELD_KEY]["error"] == 0) {
                            $target_dir = "./upload/"; // Thư mục lưu trữ ảnh
                            $target_file = $target_dir . basename($_FILES[IMAGE_FIELD_KEY]["name"]);
                            
                            // Di chuyển ảnh mới vào thư mục lưu trữ
                            if (move_uploaded_file($_FILES[IMAGE_FIELD_KEY]["tmp_name"], $target_file)) {
                                $setValues[] = IMAGE_FIELD_KEY . "='$target_file'"; // Cập nhật đường dẫn ảnh mới
                            } else {
                                echo "<p>Có lỗi khi tải lên ảnh mới.</p>";
                                exit; // Dừng thực thi nếu có lỗi
                            }
                        }
                     
                        $query_update = "UPDATE " . TABLE_NAME . " SET " . implode(', ', $setValues) . " WHERE " . PRIMARY_FIELD_KEY . "='$primaryFieldValue'";
                        $result_update = mysqli_query($connect, $query_update);
                        if ($result_update) {
                            echo "<p>Cập nhật thông tin thành công</p>";
                            echo "<a href='index.php'>Trở về trang quản lý</a>";
                        } else {
                            echo "<p>Lỗi khi cập nhật thông tin</p>";
                        }
                    } else {
                     
                        if (isset($_GET[PRIMARY_FIELD_KEY]) && !empty($_GET[PRIMARY_FIELD_KEY])) {
                            $primaryFieldValue = $_GET[PRIMARY_FIELD_KEY];
                            $query = "SELECT * FROM " . TABLE_NAME . " WHERE " . PRIMARY_FIELD_KEY . "='$primaryFieldValue'";
                            $result = mysqli_query($connect, $query);
                            if (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                ?>
                              <input type="hidden" name="<?php echo PRIMARY_FIELD_KEY; ?>" value="<?php echo $row[PRIMARY_FIELD_KEY]; ?>">
                                <?php
                                foreach ($columns as $col => $colName) {
                                    if ($col == IMAGE_FIELD_KEY || $col == PRIMARY_FIELD_KEY) { // Loại bỏ trường ảnh khỏi form cập nhật thông tin
                                        continue;
                                    }
                                    ?>
                                    <label for="<?php echo $col; ?>"><?php echo $colName; ?>:</label><br>
                                    <input type="text" id="<?php echo $col; ?>" name="<?php echo $col; ?>" value="<?php echo $row[$col]; ?>"><br>
                                    <?php
                                }
                                ?>
                                <label for="<?php echo IMAGE_FIELD_KEY; ?>"><?php echo $columns[IMAGE_FIELD_KEY]; ?>:</label><br>
                                <input type="file" name="<?php echo IMAGE_FIELD_KEY; ?>" id="<?php echo IMAGE_FIELD_KEY; ?>"><br><br>
                                <input type="submit" name="submit" value="Cập nhật">
                                <?php
                            } else {
                                echo "<p>Không tìm thấy sinh viên</p>";
                            }
                        } else {
                            echo "<p>Không có mã sinh viên được cung cấp</p>";
                        }
                    }
                }
                mysqli_close($connect);
            ?>
        </form>
    </div>
</body>
</html>
