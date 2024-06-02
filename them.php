<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sinh viên mới</title>
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
        <h1>Thêm sinh viên mới</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <?php
             require_once 'config.php';
        
            foreach ($columns as $col => $colName) {
                if ($col == IMAGE_FIELD_KEY) {
                    echo "<label for='$col'>$colName:</label><input type='file' id='$col' name='$col'><br>";
                } else {
                    if(!($col == $calculatedFieldKey)){
                        echo "<label for='$col'>$colName:</label><input type='text' id='$col' name='$col' ><br>";
                    }
                }
            }
            ?>
            <input type="submit" name="submit" value="Thêm sinh viên">
            <a href="index.php">Trở về trang quản lý</a>
        </form>

        <?php   
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            $connect = mysqli_connect(HOST, ACCOUNT_NAME, PASSWORD, DATABASE_NAME);

            if (!$connect) {
                echo "<div class='message'><p>Kết nối thất bại</p></div>";
            } else {
                $values = [];
                foreach ($columns as $col => $colName) {
                    if ($col == IMAGE_FIELD_KEY) {
                        // Upload ảnh
                        $target_dir = "./upload/";
                        $target_file = $target_dir . basename($_FILES[$col]["name"]);

                        if (move_uploaded_file($_FILES[$col]["tmp_name"], $target_file)) {
                            echo "<div class='message'><p>Ảnh đã được tải lên thành công.</p></div>";
                            $values[] = "'$target_file'";
                        } else {
                            echo "<div class='message'><p>Có lỗi khi tải ảnh lên.</p></div>";
                            $values[] = "NULL";
                        }
                    } else {
                        $values[] = "'" . $_POST[$col] . "'";
                    }
                }

                 // Kiểm tra xem có cần tính tổng điểm hay không
            if ($isCalculate) {
            // Tính tổng điểm từ các trường được chỉ định
            $totalScore = 0;
            foreach ($calculatedFields as $field => $fieldName) {
                if (isset($_POST[$field])) {
                    $totalScore += $_POST[$field];
                }
            }

            array_pop($values);
            // Thêm giá trị tổng điểm vào mảng giá trị
            $values[] = "'$totalScore'";
        }


                $columns_list = implode(", ", array_keys($columns));
          
          
                $values_list = implode(", ", $values);
  
                $query = "INSERT INTO " . TABLE_NAME . " ($columns_list) VALUES ($values_list)";
                
                $result = mysqli_query($connect, $query);
                if ($result) {
                    echo "<div class='message'><p>Thêm sinh viên thành công</p></div>";
                } else {
                    echo "<div class='message'><p>Lỗi khi thêm sinh viên: " . mysqli_error($connect) . "</p></div>";
                }
            }
            mysqli_close($connect);
        }
        ?>
    </div>
</body>
</html>
