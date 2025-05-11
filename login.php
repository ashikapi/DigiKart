<?php
// login.php
session_start();
include("config.php");
include("sidebar.php");
include("whatsapp.php");

if (isset($_POST['submit'])) {
    $login_input = mysqli_real_escape_string($conn, $_POST['login_input']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));

    // Check if it's an email or phone
    if (filter_var($login_input, FILTER_VALIDATE_EMAIL)) {
        $query = "SELECT * FROM user_info WHERE email = '$login_input' AND password = '$password' AND is_verified = 1";
    } else {
        $query = "SELECT * FROM user_info WHERE phone = '$login_input' AND password = '$password' AND is_verified = 1";
    }

    $select = mysqli_query($conn, $query);

    if (mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);
        $_SESSION['user_id'] = $row['id'];
        header('Location: index.php');
        exit();
    } else {
        $message = "Incorrect credentials or unverified account.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiKart | Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body class="login-page"> <!-- ✅ Class added here -->

    <div class="container">
        <div class="login">
            <img src="images/digikart_logo.png" alt="DigiKart logo" width="220px">
        </div>

        <div class="login-box">
            <h3>Enter Your details for Login</h3>
            <?php if (isset($message)) echo "<p style='color:red;'>$message</p>"; ?>

            <!-- ✅ Autocomplete enabled here -->
            <form action="" method="POST" autocomplete="on">
                <input type="text" name="login_input" placeholder="Email or Phone" required autocomplete="username">
                <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
                
                <div class="buttons">
                    <button class="register-btn"><a href="register.php">Create Or Register</a></button>
                    <button class="forgot-btn"><a href="forgot_password.php">Forgotten Password</a></button>
                </div>
                <button type="submit" name="submit" class="login-btn">Login</button>
            </form> 
        </div>
    </div>
</body>
</html>
