<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sinh viên</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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

        table {
            border-collapse: collapse;
            width: 70%;
            margin: 0 auto;
            background-color: #fff; /* Màu nền cho bảng */
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        img {
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .search-bar {
            margin: 20px auto;
            text-align: center;
            width: 70%;
        }

        .search-bar input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 60%;
            display: inline-block;
        }

        .search-bar input[type="submit"],
        .add-btn,
        .edit-btn,
        .delete-btn {
            padding: 8px 20px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: inline-block;
            margin-left: 10px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .search-bar input[type="submit"] {
            background-color: #4CAF50; /* Màu nền cho nút tìm kiếm */
        }

        .search-bar input[type="submit"]:hover,
        .add-btn:hover,
        .edit-btn:hover,
        .delete-btn:hover {
            background-color: #007bff; /* Màu nền hover cho nút tìm kiếm và các nút khác */
        }

        .add-btn {
            background-color: #28a745; /* Màu nền cho nút thêm sinh viên */
        }

        .edit-btn {
            background-color: #ffc107; /* Màu nền cho nút sửa */
        }

        .delete-btn {
            background-color: #dc3545; /* Màu nền cho nút xóa */
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1>Quản lý sinh viên</h1>
        <div class="search-bar">
            <form action="" method="get">
                <input type="text" name="search" placeholder="Nhập mã sinh viên...">
                <input type="submit" value="Tìm kiếm">
                <a href="them.php" class="add-btn">Thêm sinh viên</a>
            </form>
        </div>
        
        <?php
            // Include file config.php để sử dụng các hằng số
            require_once 'config.php';



            // $columns = [
            //     IMAGE_FIELD_KEY => IMAGE_FIELD_VALUE,
            //     PRIMARY_FIELD_KEY => PRIMARY_FIELD_VALUE,
            //     'hoten' => 'Tên sinh viên',
            //     'toan' => 'Toán',
            //     'ly' => 'Lý',
            //     'hoa' => 'Hóa',
            // ];
            if (isset($_GET['delete_id'])) {
                $delete_id = $_GET['delete_id'];
                
                $connect = mysqli_connect(HOST, ACCOUNT_NAME, PASSWORD, DATABASE_NAME);
                
                if (!$connect) {
                    echo "<p>Kết nối thất bại</p>";
                } else {
                    $query = "DELETE FROM " . TABLE_NAME . " WHERE " . PRIMARY_FIELD_KEY . " = '$delete_id'";
                    $result = mysqli_query($connect, $query);
                    if ($result) {
                        echo "<p>Sinh viên đã được xóa thành công</p>";
                    } else {
                        echo "<p>Lỗi khi xóa sinh viên: " . mysqli_error($connect) . "</p>";
                    }
                    mysqli_close($connect);

                    // Reload lại trang sau khi xóa
                    echo "<meta http-equiv='refresh' content='0;url=index.php'>";
                }
            }



            $connect = mysqli_connect(HOST, ACCOUNT_NAME, PASSWORD, DATABASE_NAME);
            if(!$connect){
                echo "<p>Kết nối thất bại</p>";
            } else {
                // Kiểm tra xem người dùng đã thực hiện tìm kiếm hay chưa
                if(isset($_GET['search']) && !empty($_GET['search'])) {
                    $search = $_GET['search'];
                    $query = "SELECT * FROM " . TABLE_NAME . " WHERE " . PRIMARY_FIELD_KEY . " LIKE '%$search%' OR hoten LIKE '%$search%'";
                } else {
                    // Nếu không có tìm kiếm, hiển thị tất cả sinh viên
                    $query = "SELECT * FROM " . TABLE_NAME;
                }
                $result = mysqli_query($connect, $query);
                if(!$result){
                    echo "<p>Lỗi truy vấn</p>";
                } else {
                    if(mysqli_num_rows($result) > 0){
                        echo "<table>";
                        echo "<tr>";
                        // Tạo tiêu đề bảng từ mảng thuộc tính
                        foreach ($columns as $col => $colName) {
                            echo "<th>{$colName}</th>";
                        }
                        echo "<th>Tác vụ</th>";
                        echo "</tr>";
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<tr>";
                            foreach ($columns as $col => $colName) {
                                if ($col == IMAGE_FIELD_KEY) {
                                    echo "<td><img src='{$row[$col]}'></td>";
                                } else {
                                    echo "<td>{$row[$col]}</td>";
                                }
                            }
                            echo "<td>
                                    <a class='edit-btn' href='sua.php?" . PRIMARY_FIELD_KEY . "={$row[PRIMARY_FIELD_KEY]}'>Sửa</a>
                                    <a class='delete-btn' href='?delete_id={$row[PRIMARY_FIELD_KEY]}' onclick='return confirm(\"Bạn có chắc chắn muốn xóa sinh viên này không?\")'>Xóa</a>
                              
                                </td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>Không có sinh viên nào phù hợp</p>";
                    }
                }
            }
            mysqli_close($connect);
        ?>
    </div>
</body>
</html>
