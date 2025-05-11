<?php
session_start();
require 'config.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOTP($receiver, $otp, $type = 'email') {
    if ($type === 'email') {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'example@gmail.com';
            $mail->Password = 'app password';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;//sTARTTLS port

            $mail->setFrom('example@gmail.com', 'DIgiKArt Password Reset');
            $mail->addAddress($receiver);
            $mail->isHTML(true);
            $mail->Subject = 'OTP for Password Reset';
            $mail->Body    = "Your OTP is: <b>$otp</b>";

            $mail->send();
        } catch (Exception $e) {
            echo "Failed to send OTP: {$mail->ErrorInfo}";
            exit;
        }
    }
}

if (isset($_POST['submit'])) {
    $receiver = mysqli_real_escape_string($conn, $_POST['receiver']);
    $type = filter_var($receiver, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

    $query = mysqli_query($conn, "SELECT * FROM user_info WHERE email='$receiver' OR phone='$receiver'");
    if (mysqli_num_rows($query) > 0) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_time'] = time();
        $_SESSION['reset_receiver'] = $receiver;
        $_SESSION['reset_type'] = $type;

        sendOTP($receiver, $otp, $type);
        header("Location: verify_reset_otp.php");
        exit;
    } else {
        $error = "User not found!";
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="verify_reset_otp.css">
</head>
<body>
<div class="container">
    <div class="header"><h2>Forgot Password</h2></div>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" class="register-box">
        <input type="text" name="receiver" placeholder="Email or Phone" required>
        <button type="submit" name="submit" class="register-btn">Send OTP</button>
    </form>
</div>
</body>
</html>
