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

// Fetch all orders
$orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <link rel="stylesheet" href="giftcard.css">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            padding-left: 260px;
            background-image: url('images/orderlist_background.jpg');
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        /* ✅ Fixed full screen border */
        .fixed-border {
            position: fixed;
            top: 0;
            left: 2px;
            right: 2px;
            bottom: 0;
            border: 4px solid #e8bb97;
            pointer-events: none;
            z-index: 9999;
        }

        .wrapper {
            padding: 40px;
            position: relative;
            z-index: 1;
        }

        .headers {
            background: #e8bb97;
            color: white;
            text-align: center;
            font-size: 28px;
            padding: 15px 30px;
            border-radius: 30px;
            margin-bottom: 30px;
            margin-top: -20px;
           position: fixed;
           left: 220px;
           right: 110px;
        }

       .wrapper h3 {
            font-size: 20px;
            font-weight: bold;
            color: #fff;
            margin-top: 60px;
            margin-left: -70px;
        }

        table {
            width: 100%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            margin-left: -70px;
            margin-right: 1500px;
            margin-bottom: 40px;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 18px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f3f3f3;
        }
    </style>
</head>
<body>

<!-- ✅ Always visible fixed border -->
<div class="fixed-border"></div>

<div class="wrapper">
    <div class="headers"><b>📦 Your Orders</b></div>

    <?php if (mysqli_num_rows($orders) > 0): ?>
        <?php while($order = mysqli_fetch_assoc($orders)): ?>
            <h3>🛒 Order #<?= $order['id'] ?> | Payment: <?= htmlspecialchars($order['payment_method']) ?> | ৳<?= number_format($order['total_price'], 2) ?> | Transaction ID: <?= htmlspecialchars($order['tran_id']) ?> | <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></h3>
            
            <?php
            $order_items = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = " . $order['id']);
            ?>

            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Duration</th>
                        <th>Target Info (UID/Email/Phone)</th>
                        <th>Price (৳)</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($item = mysqli_fetch_assoc($order_items)): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['item_name']) ?></td>
                        <td><?= htmlspecialchars($item['duration']) ?></td>
                        <td><?= htmlspecialchars($item['target_info']) ?></td>
                        <td><?= number_format($item['price'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            <br>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; font-size:18px;">You have no orders yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
