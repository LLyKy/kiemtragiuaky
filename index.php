<?php
session_start();

// Kiểm tra nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Xử lý đăng xuất
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Khởi tạo mảng danh sách sinh viên nếu chưa tồn tại
if (!isset($_SESSION['students'])) {
    $_SESSION['students'] = array();
}

// Xử lý thêm hoặc cập nhật sinh viên
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $dob = isset($_POST['dob']) ? $_POST['dob'] : '';

        // Kiểm tra xem ID đã tồn tại trong danh sách chưa
        $existing_student = array_search($id, array_column($_SESSION['students'], 'id'));

        if ($existing_student !== false) {
            // Nếu ID đã tồn tại, cập nhật thông tin sinh viên
            $_SESSION['students'][$existing_student]['name'] = $name;
            $_SESSION['students'][$existing_student]['dob'] = $dob;
        } else {
            // Nếu ID chưa tồn tại, thêm sinh viên mới vào danh sách
            $_SESSION['students'][] = array('id' => $id, 'name' => $name, 'dob' => $dob);
        }
    }

    // Xử lý xóa sinh viên
    if (isset($_POST['delete'])) {
        $delete_id = $_POST['delete_id'];

        // Xóa sinh viên khỏi danh sách
        unset($_SESSION['students'][$delete_id]);
        $_SESSION['students'] = array_values($_SESSION['students']); // Đặt lại chỉ số của mảng
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chính</title>
</head>
<body>
    <h2>Xin chào, <?php echo $_SESSION['username']; ?></h2>
    <a href="index.php?logout=true">Đăng xuất</a>

    <h3>Thêm sinh viên</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="id">ID:</label><br>
        <input type="number" id="id" name="id"><br>
        <label for="name">Họ và tên:</label><br>
        <input type="text" id="name" name="name"><br>
        <label for="dob">Ngày sinh:</label><br>
        <input type="date" id="dob" name="dob"><br><br>
        <input type="submit" name="submit" value="Thêm">
    </form>

    <h3>Danh sách sinh viên</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Họ và tên</th>
            <th>Ngày sinh</th>
            <th>Thao tác</th>
        </tr>
        <?php foreach ($_SESSION['students'] as $key => $student) { ?>
            <tr>
                <td><?php echo $student['id']; ?></td>
                <td><?php echo $student['name']; ?></td>
                <td><?php echo $student['dob']; ?></td>
                <td>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="delete_id" value="<?php echo $key; ?>">
                        <input type="submit" name="delete" value="Xóa">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
