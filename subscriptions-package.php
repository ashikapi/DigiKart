<?php
session_start();
include("config.php");
include("sidebar.php");
include("whatsapp.php");

$services = ['netflix', 'bongo', 'hoichoi', 'chorki', 'canva', 'telegram', 'crunchyroll', 'youtube'];
$selected_service = isset($_GET['service']) ? $_GET['service'] : '';
$packages = [];

if (in_array($selected_service, $services)) {
    $stmt = $conn->prepare("SELECT * FROM subscription_packages WHERE service = ? ORDER BY price ASC");
    $stmt->bind_param("s", $selected_service);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription Packages</title>
    <style>
        body {
            background-image: url("images/subscription_background.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            margin: 2px;
            border: 5px solid #e8bb97;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .wrapper { padding: 20px; margin-left: 260px; }
        .headers {
            background-color: #e8bb97;
            padding: 10px;
            border-radius: 30px;
            margin-left: -100px;
            margin-right: 100px;
            text-align: center;
            margin-bottom: 30px;
        }
        .headers h2 { color: black; font-size: 24px; margin: 0; }
        .selector {
            margin-left: -150px;
            text-align: center;
            margin-bottom: 30px;
        }
        select {
            padding: 10px;
            font-size: 16px;
            border-radius: 8px;
            width: 250px;
        }
        .package-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
        }
        .package-card {
            background: #fff;
            width: 280px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            text-align: center;
            transition: transform 0.3s;
            padding-bottom: 15px;
        }
        .package-card:hover { transform: translateY(-8px); }
        .package-card img {
            width: 100%; height: 180px; object-fit: cover;
        }
        .package-info { padding: 15px; }
        .package-info h3 { margin-bottom: 10px; color: #6a1b9a; }
        .package-info p { margin: 5px 0; font-size: 15px; color: #555; }
        .package-info input[type="text"] {
            width: 60%; padding: 8px; margin-top: 10px;
            border-radius: 6px; border: 1px solid #ccc;
        }
        .package-info .submit-btn {
            background-color: green; color: white;
            padding: 8px 15px; border: none; border-radius: 6px;
            margin-left: 5px; cursor: pointer;
        }
        .package-info .submit-btn:hover { background-color: darkgreen; }
        .package-info button[type="submit"] {
            margin-top: 10px; padding: 10px 20px;
            background: #6200ea; color: white;
            border: none; border-radius: 8px; cursor: pointer;
        }
        .package-info button[type="submit"]:hover {
            background: #3700b3;
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
    </style>
</head>
<body>

<div class="wrapper">
    <div class="headers">
        <h2>Select Your Subscription Packages</h2>
    </div>

    <div class="selector">
        <form method="GET">
            <select name="service" onchange="this.form.submit()">
                <option value="">-- Select Service --</option>
                <?php foreach ($services as $srv): ?>
                    <option value="<?= $srv ?>" <?= $selected_service === $srv ? 'selected' : '' ?>>
                        <?= ucwords(str_replace('_', ' ', $srv)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <div class="package-section">
        <div class="package-container">
            <?php if (!empty($packages)): ?>
                <?php foreach ($packages as $pkg): ?>
                    <div class="package-card">
                        <img src="<?= htmlspecialchars($pkg['image']) ?>" alt="<?= htmlspecialchars($pkg['name']) ?>">
                        <div class="package-info">
                            <h3><?= htmlspecialchars($pkg['name']) ?></h3>
                            <p>৳<?= number_format($pkg['price'], 2) ?></p>
                            <p>Duration: <?= htmlspecialchars($pkg['duration']) ?></p>
                            <form id="cart-form-<?= $pkg['id'] ?>" method="POST" action="cart.php" onsubmit="return confirmUID('<?= $pkg['id'] ?>')">
                                <input type="hidden" name="item_name" value="<?= $pkg['name'] ?>">
                                <input type="hidden" name="price" value="<?= $pkg['price'] ?>">
                                <input type="hidden" name="duration" value="<?= $pkg['duration'] ?>">
                                <input type="hidden" name="target_info" id="uid-hidden-<?= $pkg['id'] ?>">

                                <input type="text" id="uid-input-<?= $pkg['id'] ?>" placeholder="Enter Email / Phone">
                                <button type="button" class="submit-btn" id="submit-btn-<?= $pkg['id'] ?>" onclick="saveUID('<?= $pkg['id'] ?>')">Submit</button>

                                <button type="submit" name="add_to_cart">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="font-size: 18px; color: white;">Please select a service to view packages.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function saveUID(id) {
    const input = document.getElementById('uid-input-' + id);
    const hidden = document.getElementById('uid-hidden-' + id);
    const button = document.getElementById('submit-btn-' + id);

    if (!input.value.trim()) {
        alert("⚠️ Please enter your UID or Email before submitting.");
        return;
    }
    hidden.value = input.value.trim();
    input.disabled = true;
    button.style.display = 'none';
}

function confirmUID(id) {
    const hidden = document.getElementById('uid-hidden-' + id);
    if (!hidden.value.trim()) {
        alert("Please enter your UID/Email and click Submit before adding to cart.");
        return false;
    }
    return true;
}
</script>

</body>
</html>
