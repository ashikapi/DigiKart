<?php
session_start();
include("config.php");

// Set session timeout to 5 minutes (300 seconds)
$inactive = 300; // seconds
if (isset($_SESSION['timeout']) && (time() - $_SESSION['timeout'] > $inactive)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
$_SESSION['timeout'] = time();

if (!isset($_SESSION['user_id'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Session Expired - GiftShop</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            body {
                background: #f8f9fa;
                padding-top: 50px;
            }
            .success-box {
                background: #ffffff;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 success-box text-center">
                    <div class="alert alert-danger">
                        ❌ <strong>Session Expired!</strong><br>Please log in again to continue.
                    </div>
                    <a href="login.php" class="btn btn-primary">Login Again</a>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Success - GiftShop</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <style>
        body {
            background: #f8f9fa;
            padding-top: 50px;
        }
        .success-box {
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .tick {
            font-size: 60px;
            color: green;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 success-box text-center" id="slip-area">
            <?php if ($_POST['status'] === 'VALID'): ?>
                <?php
                    $user_id = intval($_SESSION['user_id']);
                    $tran_id = mysqli_real_escape_string($conn, $_POST['tran_id']);
                    $amount = floatval($_POST['amount']);
                    $method = mysqli_real_escape_string($conn, $_POST['card_issuer']);

                    $check = mysqli_query($conn, "SELECT id FROM orders WHERE tran_id = '$tran_id'");
                    if (mysqli_num_rows($check) == 0) {
                        mysqli_query($conn, "INSERT INTO orders (user_id, total_price, payment_method, tran_id) VALUES ($user_id, $amount, '$method', '$tran_id')");
                        $order_id = mysqli_insert_id($conn);

                        $cart_items = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id");
                        while ($item = mysqli_fetch_assoc($cart_items)) {
                            $name = mysqli_real_escape_string($conn, $item['item_name']);
                            $duration = mysqli_real_escape_string($conn, $item['duration']);
                            $target_info = mysqli_real_escape_string($conn, $item['target_info']);
                            $price = $item['price'];

                            mysqli_query($conn, "INSERT INTO order_items (order_id, item_name, duration, target_info, price)
                                                 VALUES ($order_id, '$name', '$duration', '$target_info', $price)");
                        }
                        mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");
                    }
                ?>

                <div class="tick">✅</div>
                <h2 class="text-success mb-4">Payment Successful!</h2>
                <table class="table table-bordered text-left">
                    <thead class="thead-dark">
                        <tr>
                            <th colspan="2" class="text-center">Payment Details</th>
                        </tr>
                    </thead>
                    <tr>
                        <td><strong>Transaction ID</strong></td>
                        <td><?= $_POST['tran_id'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Payment Method</strong></td>
                        <td><?= $_POST['card_issuer'] ?? 'N/A' ?></td>
                    </tr>
                    <tr>
                        <td><strong>Bank Transaction ID</strong></td>
                        <td><?= $_POST['bank_tran_id'] ?? 'N/A' ?></td>
                    </tr>
                    <tr>
                        <td><strong>Amount</strong></td>
                        <td><?= $_POST['amount'] . ' ' . $_POST['currency'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Date</strong></td>
                        <td><?= $_POST['tran_date'] ?></td>
                    </tr>
                </table>

                <div class="mt-4">
                    <a href="order-details.php" class="btn btn-success">View My Orders</a>
                    <a href="index.php" class="btn btn-secondary">Back to Home</a>
                    <button onclick="downloadSlip()" class="btn btn-info">Download Slip</button>
                </div>

                <script>
                    setTimeout(function(){
                        window.location.href = "order-details.php";
                    }, 5000);

                    function downloadSlip() {
                        const slip = document.getElementById('slip-area');
                        html2canvas(slip).then(canvas => {
                            let link = document.createElement("a");
                            link.download = "payment_slip.png";
                            link.href = canvas.toDataURL();
                            link.click();
                        });
                    }
                </script>

            <?php else: ?>
                <div class="alert alert-danger">
                    ❌ Invalid Payment Status. Please try again or contact support.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
