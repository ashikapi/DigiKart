<?php
include("config.php");
include("whatsapp.php");
session_start();
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ Full sendOTP function
function sendOTP($receiver, $otp, $type = 'email') {
    if ($type === 'email') {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'example@gmail.com';
            $mail->Password = 'use your app password';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('example@gmail.com', 'DIgiKArt Password Reset');
            $mail->addAddress($receiver);
            $mail->isHTML(true);
            $mail->Subject = 'OTP for Password Reset';
            $mail->Body    = "Your OTP is: <b>$otp</b>";

            $mail->send();
        } catch (Exception $e) {
            echo "OTP Send Failed: {$mail->ErrorInfo}";
            exit;
        }
    }
}

// ❌ Block if session expired
if (!isset($_SESSION['reset_receiver']) || !isset($_SESSION['otp'])) {
    echo "Session expired!";
    exit;
}

// ✅ OTP Verification
if (isset($_POST['verify'])) {
    $entered = $_POST['otp'];
    if (time() - $_SESSION['otp_time'] > 300) {
        $error = "OTP expired!";
    } elseif ($entered != $_SESSION['otp']) {
        $error = "Invalid OTP!";
    } else {
        $_SESSION['otp_verified'] = true;
        header("Location: reset_password.php");
        exit;
    }
}

// ✅ Resend OTP
if (isset($_POST['resend'])) {
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_time'] = time();
    $receiver = $_SESSION['reset_receiver'];
    $type = $_SESSION['reset_type'];

    sendOTP($receiver, $otp, $type);
    $message = "✅ A new OTP has been sent to your email!";
}

// ✅ Countdown after resend
$remaining_time = $_SESSION['otp_time'] + 300 - time();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <link rel="stylesheet" href="verify_reset_otp.css">
</head>
<body>
<div class="container">
    <div class="header"><h2>Verify OTP</h2></div>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (isset($message)) echo "<p style='color:green;'>$message</p>"; ?>

    <form method="POST" class="register-box">
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="submit" name="verify" class="register-btn">Verify</button>
    </form>

    <div id="countdown" style="color:green; margin-top:10px;"></div>

    <form method="POST" id="resendForm" style="display:none; margin-top:10px;">
        <button type="submit" name="resend" class="register-btn" style="background: orange;">🔁 Resend OTP</button>
    </form>
</div>

<!-- ✅ Countdown Script -->
<script>
let remainingTime = <?php echo max(0, $remaining_time); ?>;
let countdownEl, resendForm, timerID;

window.onload = function () {
    countdownEl = document.getElementById('countdown');
    resendForm = document.getElementById('resendForm');
    startCountdown();
};

function formatTime(s) {
    return String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0');
}

function startCountdown() {
    if (timerID) clearTimeout(timerID);

    function updateCountdown() {
        if (remainingTime <= 0) {
            countdownEl.textContent = "⛔ OTP expired! Please resend.";
            countdownEl.style.color = "red";
            resendForm.style.display = "block";
        } else {
            countdownEl.textContent = "⏳ OTP will expire in: " + formatTime(remainingTime);
            remainingTime--;
            timerID = setTimeout(updateCountdown, 1000);
        }
    }

    resendForm.style.display = "none";
    countdownEl.style.color = "red";
    updateCountdown();
}

// ✅ AJAX Resend
document.getElementById('resendForm').addEventListener('submit', function (e) {
    e.preventDefault();

    fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'resend=1'
    })
    .then(res => res.text())
    .then(html => {
        document.open();
        document.write(html);
        document.close();
    });
});
</script>
</body>
</html>
