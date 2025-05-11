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

// Save target_info
if (isset($_POST['save_info'])) {
    $cart_id = (int)$_POST['cart_id'];
    $target_info = mysqli_real_escape_string($conn, $_POST['target_info']);
    mysqli_query($conn, "UPDATE cart SET target_info = '$target_info' WHERE id = $cart_id AND user_id = $user_id");
    header("Location: cart.php");
    exit;
}

// Add to cart with target_info
if (isset($_POST['add_to_cart'])) {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $price = (float)$_POST['price'];
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $target_info = isset($_POST['target_info']) ? mysqli_real_escape_string($conn, $_POST['target_info']) : '';

    $check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id AND item_name = '$item_name' AND target_info = '$target_info'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "INSERT INTO cart (user_id, item_name, price, duration, target_info) VALUES ($user_id, '$item_name', $price, '$duration', '$target_info')");
    }
    header("Location: cart.php");
    exit;
}

// Remove item
if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    mysqli_query($conn, "DELETE FROM cart WHERE id = $remove_id AND user_id = $user_id");
    header("Location: cart.php");
    exit;
}

// Get cart items
$items = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id");
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="giftcard.css">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            box-sizing: border-box;
            font-family: Arial;
        }
        body {
            padding-left: 260px;
            background-image: url('images/shoppingcart_background.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            background-attachment: fixed;
        }

        .fixed-border {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 7px solid #e8bb97;
            pointer-events: none;
            z-index: 9999;
        }

        .cart-container {
            padding: 40px;
            position: relative;
            z-index: 1;
        }

        .header {
            background: #e8bb97;
            text-align: center;
            padding: 15px 30px;
            border-radius: 30px;
            color: black;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 30px;
            margin-left: -200px;
            margin-right: 200px;
            position: fixed;
            top: 30px;
            left: 300px;
            right: 30px;
        }

        table {
            width: 100%;
            background: #fff;
            border-radius: 10px;
            border-collapse: collapse;
            overflow: hidden;
            margin-left: -200px;
            margin-right: 200px;
            margin-top: 100px;
        }

        th, td {
            padding: 12px 18px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f3f3f3;
        }

        .form-inline {
            margin-top: 8px;
        }

        .form-inline input[type="text"] {
            padding: 6px;
            width: 70%;
            border-radius: 5px;
        }

        .form-inline button {
            padding: 6px 12px;
            margin-left: 6px;
            background: green;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .total {
            color: white;
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
            font-size: 18px;
            margin-left: -200px;
            margin-right: 200px;
            margin-bottom: 20px;
        }

        .checkout-btn {
            background: #6200ea;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            float: right;
        }

        a.remove-link {
            color: red;
            font-weight: bold;
            text-decoration: none;
        }

        a.remove-link:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function validateCheckout() {
            const infos = document.querySelectorAll('[data-required]');
            for (let info of infos) {
                const text = info.textContent.toLowerCase();
                if ((!text || text.includes("not provided")) && !text.includes("not required")) {
                    alert("Please provide UID / Email / Phone for all required items before checkout.");
                    return false;
                }
            }
            return true;
        }
    </script>
</head>
<body>

<div class="fixed-border"></div>

<div class="cart-container">
    <div class="header">🛒 Your Shopping Cart</div>

    <?php if (mysqli_num_rows($items) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Duration</th>
                    <th>Target Info (UID / Email / Phone)</th>
                    <th>Price (৳)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $no_target_required_items = ['bata', 'google', 'apex', 'nordvpn', 'arong', 'google play', 'itunes', 'amazon', 'steam'];

            while($row = mysqli_fetch_assoc($items)): 
                $total += $row['price'];
                $is_optional = false;
                foreach ($no_target_required_items as $skip) {
                    if (stripos($row['item_name'], $skip) !== false) {
                        $is_optional = true;
                        break;
                    }
                }
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['item_name']) ?></td>
                    <td><?= htmlspecialchars($row['duration']) ?></td>
                    <td>
                        <?php if ($row['target_info']): ?>
                            <div data-required="true"><?= htmlspecialchars($row['target_info']) ?></div>
                        <?php elseif ($is_optional): ?>
                            <div data-required="true"><em>Not provided (Not Required)</em></div>
                        <?php else: ?>
                            <div data-required="true"><em>Not provided</em></div>
                            <form method="POST" class="form-inline">
                                <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                                <input type="text" name="target_info" placeholder="Enter UID / Email / Phone" required>
                                <button type="submit" name="save_info">Submit</button>
                            </form>
                        <?php endif; ?>
                    </td>
                    <td><?= number_format($row['price'], 2) ?></td>
                    <td>
                        <a href="?remove=<?= $row['id'] ?>" class="remove-link">Remove ❌</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <div class="total">
            Total: ৳<?= number_format($total, 2) ?> <br>
            <a href="checkout.php" class="checkout-btn" onclick="return validateCheckout()">Proceed to Checkout</a>
        </div>
    <?php else: ?>
        <p style="font-size: 18px; text-align:center; color:#333;">Your cart is empty.</p>
    <?php endif; ?>
</div>
</body>
</html>
