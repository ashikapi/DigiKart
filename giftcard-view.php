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

$query = "SELECT oi.*, o.created_at AS purchase_date 
          FROM order_items oi 
          JOIN orders o ON oi.order_id = o.id 
          WHERE o.user_id = $user_id 
          ORDER BY o.created_at DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Gift Card</title>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('images/orderlist_background.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        .border-overlay {
            position: fixed;
            top: 0; left: 0;
            right: 0; bottom: 0;
            border: 6px solid #e8bb97;
            pointer-events: none;
            z-index: 9999;
        }

        .title {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 999;
            background-color: #e8bb97;
            color: black;       
            font-size: 28px;
            font-weight: bold;
            padding: 10px 40px;
            border-radius: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        }

        .cards-container {
            margin-top: 120px;
            padding: 30px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
        }

        .gift-card {
            background: linear-gradient(90deg, #a6a6a6, #ffffff);
            border-radius: 25px;
            width: 310px;
            padding: 20px;
            text-align: center;
            color: black;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .gift-card img.logo {
            width: 130px;
            margin-bottom: 10px;
        }

        .gift-card h3 {
            font-style: italic;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .gift-card p {
            margin: 4px 0;
        }

        .gift-card strong {
            font-weight: bold;
        }

        .countdown {
            color: red;
            font-weight: bold;
        }

        .thankyou {
            font-weight: bold;
            color: #0f6c85;
            font-style: italic;
            margin-top: 15px;
        }

        .gift-card .logo-dk {
            margin-top: 10px;
            width: 220px;
        }

        .download-btn {
            background-color: #64dd17;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            margin-top: 10px;
            font-weight: bold;
            cursor: pointer;
        }
        .firstheader {
    background: #e8bb97;
    text-align: center;
    padding: 5px;
    margin-bottom: -15px;
    border-radius: 2px;
    box-shadow: none;
}

.gape {
    background: black;
    padding: 10px;
    border-radius: 2px;
    box-shadow: none;
}

.firstheader h2 {
    color: white;
    font-size: 24px;
    margin-top: 10px;
}
#sidebarMenu {
    position: fixed;
    top: 0px; /* ← এইটা background সহ পুরো box কে নিচে নামাবে */
    left: 0;
}
    </style>
</head>
<body>
<div class="border-overlay"></div>
<div class="title">View Gift Card</div>

<div class="cards-container">
<?php 
$index = 1;
while ($row = mysqli_fetch_assoc($result)): 
    $card_id = $row['id'];
    $item_name = strtolower($row['item_name']);

    if (!preg_match('/bata|apex|google|arong|nordvpn/i', $item_name)) continue;

    $purchase_date = $row['purchase_date'];
    $duration_raw = strtolower($row['duration']);

    $duration = 7;
    if (strpos($duration_raw, 'month') !== false) $duration = 30;
    elseif (strpos($duration_raw, 'week') !== false) $duration = 7;
    elseif (strpos($duration_raw, 'day') !== false) $duration = 1;

    $expire_date = date("Y-m-d H:i:s", strtotime($purchase_date . " +$duration days"));
    $now = date("Y-m-d H:i:s");
    $is_expired = (strtotime($expire_date) < strtotime($now));

    // Handle secret code and status
    $secret_code = $row['secret_code'];
    $status = $row['status'];

    if (!$secret_code) {
        $secret_code = rand(1000000000, 9999999999);
        mysqli_query($conn, "UPDATE order_items SET secret_code='$secret_code' WHERE id=$card_id");
    }

    if ($is_expired && $status !== 'expired') {
        mysqli_query($conn, "UPDATE order_items SET status='expired' WHERE id=$card_id");
        $status = 'expired';
    }

    $img = "images/gift.png";
    if (strpos($item_name, 'bata') !== false) $img = "images/bata.png";
    elseif (strpos($item_name, 'apex') !== false) $img = "images/apex.png";
    elseif (strpos($item_name, 'google') !== false) $img = "images/google.png";
    elseif (strpos($item_name, 'arong') !== false) $img = "images/arong.png";
    elseif (strpos($item_name, 'nordvpn') !== false) $img = "images/nordvpn.png";
?>
<div class="gift-card" id="card-<?= $index ?>">
    <img src="<?= $img ?>" class="logo" alt="logo">
    <h3><?= ucfirst(explode(' ', $item_name)[0]) ?> Gift Card</h3>
    <p><strong>Duration:</strong> <?= $row['duration'] ?></p>
    <p><strong>Purchased Date:</strong> <?= date("d/m/Y", strtotime($purchase_date)) ?></p>
    <p><strong>Expire Date:</strong> <?= date("d/m/Y", strtotime($expire_date)) ?></p>
    <p><strong>End in :</strong> 
        <span class="countdown" id="countdown-<?= $index ?>">
            <?= $status === 'expired' ? 'Expired' : '' ?>
        </span>
    </p>
    <p><strong>Secret Number:</strong> <em><?= $secret_code ?></em></p>
    <img src="images/digikart_logo.png" class="logo-dk" alt="DigiKart">
    <div class="thankyou"><b>Thank You For Using DigiKArt</b></div>
    <button class="download-btn" onclick="downloadCard('card-<?= $index ?>')">Download Card</button>
</div>
<?php $index++; endwhile; ?>
</div>

<script>
<?php 
mysqli_data_seek($result, 0);
$index = 1;
while ($row = mysqli_fetch_assoc($result)):
    if (!preg_match('/bata|apex|google|arong|nordvpn/i', $row['item_name'])) continue;
    if ($row['status'] === 'expired') continue;

    $purchase_date = $row['purchase_date'];
    $duration_raw = strtolower($row['duration']);
    $duration = 7;
    if (strpos($duration_raw, 'month') !== false) $duration = 30;
    elseif (strpos($duration_raw, 'week') !== false) $duration = 7;
    elseif (strpos($duration_raw, 'day') !== false) $duration = 1;

    $expire_ts = strtotime($purchase_date . " +$duration days") * 1000;
?>
let countdown<?= $index ?> = setInterval(function() {
    let now = new Date().getTime();
    let distance = <?= $expire_ts ?> - now;

    if (distance < 0) {
        document.getElementById("countdown-<?= $index ?>").innerHTML = "Expired";
        clearInterval(countdown<?= $index ?>);
    } else {
        let d = Math.floor(distance / (1000 * 60 * 60 * 24));
        let h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let s = Math.floor((distance % (1000 * 60)) / 1000);
        document.getElementById("countdown-<?= $index ?>").innerHTML = `${d} day ${h} h ${m} min ${s} sec`;
    }
}, 1000);
<?php $index++; endwhile; ?>
</script>

<script>
function downloadCard(id) {
    const card = document.getElementById(id);
    const button = card.querySelector('.download-btn');
    button.style.display = 'none';

    html2canvas(card).then(canvas => {
        let link = document.createElement("a");
        link.download = id + ".png";
        link.href = canvas.toDataURL();
        link.click();
        button.style.display = 'inline-block';
    });
}
</script>

</body>
</html>
