<?php
session_start();
include("config.php");
include("sidebar.php");
include("whatsapp.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check cart validation
$cart_items = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id");
$all_ready = true;
while ($row = mysqli_fetch_assoc($cart_items)) {
    if (empty($row['target_info'])) {
        $all_ready = false;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Method</title>
    <link rel="stylesheet" href="giftcard.css">
    <style>
        body {
            margin: 0;
            padding-left: 260px;
            font-family: Arial, sans-serif;
            background: black;
        }

        .fixed-border {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 6px solid #e8bb97;
            pointer-events: none;
            z-index: 9999;
        }

        .header {
            background: #e8bb97;
            color: black;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            padding: 12px 30px;
            border-radius: 50px;
            position: fixed;
            top: 20px;
            left: 400px;
            right: 200px;
            z-index: 1000;
        }

        .payment-options {
            display: flex;
            justify-content: center;
            align-items: center;
            background: white;
            border-radius: 10px;
            margin-bottom: 100px;
            margin-right: 200px;
            margin-left: 150px;
            margin-top: 140px;
            gap: 60px;
        }

        .payment-options img {
            width: 150px;
            height: 150px;
            border-radius: 20px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .payment-options img:hover {
            transform: scale(1.08);
        }
        #sidebarMenu {
    position: fixed;
    top: 0px; 
    left: 0;
}
    </style>
    <script>
        function redirectTo(method) {
            const ready = <?= $all_ready ? 'true' : 'false' ?>;
            if (!ready) {
                alert("Please fill in all Target Info in the cart before proceeding.");
                window.location.href = "cart.php";
                return;
            }
            window.location.href = "checkout.php?method=" + method;
        }
    </script>
</head>
<body>
    <div class="fixed-border"></div>

    <div class="header">💳 Payment Method</div>

    <div class="payment-options">
        <img src="images/bks.png" alt="bKash" onclick="redirectTo('bkash')">
        <img src="images/roket.png" alt="Rocket" onclick="redirectTo('rocket')">
        <img src="images/nagad.png" alt="Nagad" onclick="redirectTo('nagad')">
    </div>
</body>
</html>
