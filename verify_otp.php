<?php
session_start();
include("config.php");
include("whatsapp.php");

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Handle Resend OTP
if (isset($_POST['resend_otp'])) {
    if (isset($_SESSION['register_data'])) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_time'] = time();

        $receiver = $_SESSION['register_data']['receiver'];

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
            $mail->Subject = 'Your New OTP Code';
            $mail->Body    = "Your new OTP is: <b>$otp</b>";
            $mail->send();

            $message = "✅ New OTP sent successfully!";
        } catch (Exception $e) {
            echo "Resend Failed: {$mail->ErrorInfo}";
            exit;
        }
    }
}

// Check session validity
if (!isset($_SESSION['register_data']) || !isset($_SESSION['otp'])) {
    echo "Session expired. Please register again.";
    exit;
}

$otp_valid_duration = 300; // 5 minutes
$remaining_time = $_SESSION['otp_time'] + $otp_valid_duration - time();

// Handle OTP verification
if (isset($_POST['verify_otp'])) {
    $entered_otp = $_POST['otp'];
    if (time() - $_SESSION['otp_time'] > $otp_valid_duration) {
        $error = "OTP expired! Please resend.";
    } elseif ($entered_otp != $_SESSION['otp']) {
        $error = "Incorrect OTP!";
    } else {
        // OTP matched - proceed registration
        $data = $_SESSION['register_data'];
        $username = mysqli_real_escape_string($conn, $data['username']);
        $firstname = mysqli_real_escape_string($conn, $data['firstname']);
        $lastname = mysqli_real_escape_string($conn, $data['lastname']);
        $gender = mysqli_real_escape_string($conn, $data['gender']);
        $receiver = mysqli_real_escape_string($conn, $data['receiver']);
        $type = $data['type'];
        $email = ($type == 'email') ? $receiver : '';
        $phone = ($type == 'phone') ? $receiver : '';
        $password = $data['password'];
        $confirm_password = $data['confirm_password'];

        if ($password !== $confirm_password) {
            $error = "Passwords do not match!";
        } else {
            $pass = mysqli_real_escape_string($conn, md5($password));

            // Prepare NULL safe insert values
            $phone_value = !empty($phone) ? "'$phone'" : "NULL";
            $email_value = !empty($email) ? "'$email'" : "NULL";

            // Insert into Database
            $insert = "INSERT INTO user_info (username, firstname, lastname, gender, email, phone, password, is_verified)
                       VALUES ('$username', '$firstname', '$lastname', '$gender', $email_value, $phone_value, '$pass', 1)";
            if (mysqli_query($conn, $insert)) {
                // Success
                unset($_SESSION['register_data'], $_SESSION['otp'], $_SESSION['otp_time'], $_SESSION['receiver'], $_SESSION['type']);
                header("Location: login.php");
                exit;
            } else {
                $error = "Database error! " . mysqli_error($conn);
            }
        }
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="verify_otp.css">
</head>
<body>
<div class="container">
    <div class="header"><h2>OTP Verification</h2></div>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (isset($message)) echo "<p style='color:red;'>$message</p>"; ?>

    <form method="POST" class="register-box">
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="submit" name="verify_otp" class="register-btn">Verify & Register</button>
    </form>

    <div style="margin-top:10px;">
        <span id="countdown" style="color:red; font-weight:bold;"></span>
    </div>

    <form method="POST" id="resendForm" style="display:none; margin-top:10px;">
        <button type="submit" name="resend_otp" class="register-btn" style="background-color:orange;">🔁 Resend OTP</button>
    </form>
</div>

<script>
let remainingTime = <?php echo max(0, $remaining_time); ?>;
const countdownEl = document.getElementById('countdown');
const resendForm = document.getElementById('resendForm');
const verifyBtn = document.querySelector('.register-btn');

function formatTime(seconds) {
    const m = String(Math.floor(seconds / 60)).padStart(2, '0');
    const s = String(seconds % 60).padStart(2, '0');
    return `${m}:${s}`;
}

function updateCountdown() {
    if (remainingTime <= 0) {
        countdownEl.textContent = "⛔ OTP expired! Please resend.";
        countdownEl.style.color = "red";
        resendForm.style.display = "block";
        verifyBtn.disabled = true;
    } else {
        countdownEl.textContent = `⏳ OTP expires in: ${formatTime(remainingTime)}`;
        remainingTime--;
        setTimeout(updateCountdown, 1000);
    }
}
updateCountdown();
</script>
</body>
</html>
