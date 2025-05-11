<?php
session_start();
include("config.php");
include("sidebar.php");
include("whatsapp.php");

// Supported brands
$brands = ['bata', 'apex', 'google', 'arong', 'nordvpn'];
$selected_brand = isset($_GET['service']) ? $_GET['service'] : '';
$packages = [];

// Background mapping
$bgMap = [
    'bata' => 'images/bata_background.jpg',
    'apex' => 'images/apex_background.jpg',
    'google' => 'images/GooglePlay_background.jpg',
    'arong' => 'images/arong_background.jpg',
    'nordvpn' => 'images/NordVpn_background.jpg'
];
$background = isset($bgMap[$selected_brand]) ? $bgMap[$selected_brand] : '';

if (in_array($selected_brand, $brands)) {
    $stmt = $conn->prepare("SELECT * FROM gift_cards WHERE brand = ? ORDER BY price ASC");
    $stmt->bind_param("s", $selected_brand);
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
    <title>Gift Packages</title>
    <style>
        body.gift-packages-page {
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            margin: 2px;
            border: 4px solid #e8bb97;
            text-align: center;
        }
        .wrapper {
            padding: 20px;
            margin-left: 260px;
        }
        .headers {
            background-color: #e8bb97;
            padding: 15px;
            border-radius: 30px;
            margin-left: -100px;
            margin-right: 100px;
            text-align: center;
            margin-bottom: 30px;
            color: black;
            font-size: 24px;
            font-weight: bold;
        }
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
        }
        .package-card:hover {
            transform: translateY(-8px);
        }
        .package-card img {
            width: 100%;
            height: 180px;
            object-fit: contain;
            background: white;
        }
        .package-info {
            padding: 15px;
        }
        .package-info h3 {
            margin-bottom: 10px;
            color: #6a1b9a;
            font-weight: bold;
        }
        .package-info p {
            margin: 5px 0;
            font-size: 15px;
            color: #555;
        }
        .package-info button {
            margin-top: 10px;
            padding: 10px 20px;
            background: #6200ea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .package-info button:hover {
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
<body class="gift-packages-page" style="background-image: url('<?= $background ?>');">

<div class="wrapper">
    <div class="headers">Select Your Gift Package</div>

    <div class="selector">
        <form method="GET">
            <select name="service" onchange="this.form.submit()">
                <option value="">-- Select Brand --</option>
                <?php foreach ($brands as $b): ?>
                    <option value="<?= $b ?>" <?= $b === $selected_brand ? 'selected' : '' ?>><?= ucfirst($b) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <div class="package-container">
        <?php if (!empty($packages)): ?>
            <?php foreach ($packages as $pkg): ?>
                <div class="package-card">
                    <img src="<?= htmlspecialchars($pkg['image']) ?>" alt="<?= htmlspecialchars($pkg['name']) ?>">
                    <div class="package-info">
                        <h3><?= htmlspecialchars($pkg['name']) ?></h3>
                        <p>৳<?= number_format($pkg['price'], 2) ?></p>
                        <p>Duration: <?= htmlspecialchars($pkg['duration']) ?></p>
                        <form method="POST" action="cart.php">
                            <input type="hidden" name="item_name" value="<?= strtoupper($pkg['brand']) . ' ' . $pkg['name'] ?>">
                            <input type="hidden" name="price" value="<?= $pkg['price'] ?>">
                            <input type="hidden" name="duration" value="<?= $pkg['duration'] ?>">
                            <button type="submit" name="add_to_cart">Add to Cart</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: white; font-size: 18px;">Please select a brand to view packages.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
