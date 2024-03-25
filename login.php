<?php
session_start();

// Kiểm tra nếu đã đăng nhập, chuyển hướng đến trang index.php
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Xử lý đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra thông tin đăng nhập
    if ($_POST['username'] == 'minzon' && $_POST['password'] == '123') {
        // Đăng nhập thành công, thiết lập session và chuyển hướng đến trang index.php
        $_SESSION['username'] = $_POST['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Sai tài khoản hoặc mật khẩu!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <?php if(isset($error)) { ?>
        <div><?php echo $error; ?></div>
    <?php } ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Tên đăng nhập:</label><br>
        <input type="text" id="username" name="username" value="minzon"><br>
        <label for="password">Mật khẩu:</label><br>
        <input type="password" id="password" name="password" value="123"><br><br>
        <input type="submit" value="Đăng nhập">
    </form>
</body>
</html>
