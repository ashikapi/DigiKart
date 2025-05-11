<?php
session_start();
require 'config.php';

if (!isset($_SESSION['otp_verified']) || !isset($_SESSION['reset_receiver'])) {
    echo "Invalid access!";
    exit;
}

if (isset($_POST['reset'])) {
    $pass = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    if ($pass !== $confirm) {
        $error = "Passwords do not match!";
    } else {
        $hash = md5(mysqli_real_escape_string($conn, $pass));
        $receiver = $_SESSION['reset_receiver'];
        $query = "UPDATE user_info SET password = '$hash' WHERE email = '$receiver' OR phone = '$receiver'";
        mysqli_query($conn, $query);
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="reset_password.css">
</head>
<body>
<div class="container">
    <div class="header"><h2>Reset Password</h2></div>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" class="register-box">
        <input type="password" name="password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit" name="reset" class="register-btn">Reset Password</button>
    </form>
</div>
</body>
</html>
