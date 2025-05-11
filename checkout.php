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
$cart_items = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id");
$total_price = 0;

while ($row = mysqli_fetch_assoc($cart_items)) {
    $total_price += $row['price'];
}

// Handle form submission
if (isset($_POST['place_order'])) {
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

    if ($payment_method === "SSLCommerz") {
        $_SESSION['checkout_total'] = $total_price;
        header("Location: ssl-init.php");
        exit;
    } else {
        // Insert into orders table
        mysqli_query($conn, "INSERT INTO orders (user_id, total_price, payment_method) VALUES ($user_id, $total_price, '$payment_method')");
        $order_id = mysqli_insert_id($conn);

        $cart_items = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id");

        while ($item = mysqli_fetch_assoc($cart_items)) {
            $item_name = mysqli_real_escape_string($conn, $item['item_name']);
            $duration = mysqli_real_escape_string($conn, $item['duration']);
            $target_info = mysqli_real_escape_string($conn, $item['target_info']);
            $price = $item['price'];

            // Insert into order_items table
            mysqli_query($conn, "INSERT INTO order_items (order_id, item_name, duration, target_info, price)
                                 VALUES ($order_id, '$item_name', '$duration', '$target_info', $price)");

            // ✅ Insert into giftcards table
            $secret_code = strtoupper(bin2hex(random_bytes(4))); // 8-digit hex code
            $purchase_date = date("Y-m-d H:i:s");
            $expiry_date = date("Y-m-d H:i:s", strtotime("+$duration days")); // Dynamic based on cart item

            $sql = "INSERT INTO giftcards (user_id, order_id, package_name, price, purchase_date, expiry_date, secret_code) 
                    VALUES ('$user_id', '$order_id', '$item_name', '$price', '$purchase_date', '$expiry_date', '$secret_code')";
            mysqli_query($conn, $sql);
        }

        // Clear the cart after order
        mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");

        echo "<script>alert('✅ Order placed successfully!'); window.location.href='order-details.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="giftcard.css">
    <style>
         html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            box-sizing: border-box;
        }
        body {
            padding-left: 260px;
            background-image: url('images/checkout_background.jpg');
            border: 4px solid #e8bb97;
            background-repeat: no-repeat;
            background-position: center;
            font-family: Arial;
        }
        .checkout-wrapper {
            padding: 40px;
        }
        .header {
            background: #e8bb97;
            padding: 10px 300px;
            margin-right: 300px;
            text-align: center;
            font-size: 28px;
            border-radius: 10px;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .total {
            color: wheat;
            font-size: 20px;
            margin-bottom: 20px;
        }
        form label {
            font-weight: bold;
        }
        form select, form button {
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            width: 30%;
            border-radius: 8px;
        }
        button {
            background-color: #6200ea;
            color: white;
            border: none;
        }
        .ssl-button {
            display: inline-block;
            text-align: center;
            padding: 10px 20px;
            background-color: #ff6f00;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="checkout-wrapper">
    <div class="header">Checkout</div>
    <div class="total">Total to Pay: ৳<?= number_format($total_price, 2) ?></div>

    <?php if ($total_price > 0): ?>
        <a href="ssl-init.php" class="ssl-button">Pay Now with SSLCommerz</a> <br>OR

        <form method="POST">
            <label>Select Payment Method:</label><br>
            <select name="payment_method" required>
                <option value="">-- Select --</option>
                <option value="Bkash">Bkash</option>
                <option value="Nagad">Nagad</option>
                <option value="Rocket">Rocket</option>
                <option value="SSLCommerz">SSLCommerz</option>
            </select>
            <button type="submit" name="place_order">Confirm & Pay</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty. Please add some items.</p>
    <?php endif; ?>
</div>
</body>
</html>
