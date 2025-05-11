<?php
session_start();
include("config.php");
include("OrderTransaction.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Failed</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            margin-top: 80px;
            font-family: Arial, sans-serif;
            background: #fff3f3;
            text-align: center;
        }
        .fail {
            color: red;
            font-size: 28px;
            font-weight: bold;
        }
        .countdown {
            font-size: 18px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<?php
if (empty($_POST['tran_id']) || empty($_POST['status'])) {
    echo '<h2 class="text-danger">Invalid Payment Info</h2>';
    exit;
}

$tran_id = trim($_POST['tran_id']);
$ot = new OrderTransaction();
$sql = $ot->getRecordQuery($tran_id);
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "<h2 class='text-danger'>Transaction not found</h2>";
    exit;
}

$row = mysqli_fetch_assoc($result);

if ($row['status'] === 'Pending' || $row['status'] === 'Failed') {
    $update = $ot->updateTransactionQuery($tran_id, 'Failed');
    mysqli_query($conn, $update);
}
?>

<div class="fail">❌ Your Payment Has Failed!</div>
<p class="countdown">
    You will be redirected to your shopping cart in <span id="countdown">5</span> seconds...
</p>

<!-- ✅ JS must go at the end of body -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let seconds = 5;
        const countdownElement = document.getElementById("countdown");

        const interval = setInterval(() => {
            countdownElement.textContent = seconds;
            if (seconds === 0) {
                clearInterval(interval);
                window.location.href = "cart.php"; // Redirect target
            }
            seconds--;
        }, 1000);
    });
</script>

</body>
</html>
