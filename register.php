<?php
session_start();
include("config.php");
include("sidebar.php");
include("whatsapp.php");

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send OTP
function sendOTP($receiver, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'example@gmail.com';
        $mail->Password = 'use your app password';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('example@gmail.com', 'DIgiKArt OTP');
        $mail->addAddress($receiver);
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP is: <b>$otp</b>";

        $mail->send();
    } catch (Exception $e) {
        echo "OTP Send Failed: {$mail->ErrorInfo}";
        exit;
    }
}

$message = [];

if (isset($_POST['register'])) {
    $receiver = mysqli_real_escape_string($conn, $_POST['receiver']);
    $type = filter_var($receiver, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

    // Check if email or phone already exists
    $check_query = "SELECT * FROM user_info WHERE email='$receiver' OR phone='$receiver'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $message[] = "🚫 This Email or Phone is already registered.";
    } else {
        // Save user data in session
        $_SESSION['register_data'] = [
            'username' => $_POST['username'],
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'gender' => $_POST['gender'],
            'receiver' => $receiver,
            'type' => $type,
            'password' => $_POST['password'],
            'confirm_password' => $_POST['confirm_password']
        ];

        // Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_time'] = time();
        $_SESSION['receiver'] = $receiver;
        $_SESSION['type'] = $type;

        sendOTP($receiver, $otp);
        header("Location: verify_otp.php");
        exit;
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register with OTP</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>

<?php 
if (isset($message)) {
    foreach ($message as $msg) {
        echo "<p style='color:red; text-align:center;'>$msg</p>";
    }
}
?>

<div class="container">
    <div class="headers">
       <img src="images/digikart_logo.png" alt="DigiKart logo" width="220px">
    </div>

    <form action="" method="POST" class="register-box">
        <h3><b>Create a new account</b></h3>
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="text" name="firstname" placeholder="First Name" required>
        <input type="text" name="lastname" placeholder="Last Name" required>
        <input type="text" name="receiver" placeholder="Enter Email or Phone" required>

        <input type="password" name="password" placeholder="Enter 8 Digit Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>

        <div class="gender-selection">
            <p><b>Select Your Gender</b></p>
            <label><input type="radio" name="gender" value="male" required> Male</label>
            <label><input type="radio" name="gender" value="female" required> Female</label>
        </div>

        <div class="buttons">
            <button type="submit" name="register" class="register-btn"><b>Create Account</b></button>
            <button type="button" class="login-btn" onclick="window.location.href='login.php'"><b>Login</b></button>

        </div>
    </form>
</div>
</body>
</html>
