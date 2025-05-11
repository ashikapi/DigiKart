<?php
session_start();
include("config.php");
include("sidebar.php");
include("whatsapp.php");

$games = ['freefire', 'pubg', 'callofduty', 'gta', 'valorant', 'clashroyale', 'clashofclans', 'minecraft', 'roblox', 'efootball'];
$selected_game = isset($_GET['service']) ? $_GET['service'] : '';
$packages = [];

if (in_array($selected_game, $games)) {
    $stmt = $conn->prepare("SELECT * FROM game_topups WHERE game = ? ORDER BY price ASC");
    $stmt->bind_param("s", $selected_game);
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
    <title>Games Top Up</title>
    <style>
        body {
            background-image: url("images/Gametopup_Background.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            margin: 2px;
            border: 4px solid #e8bb97;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .wrapper { padding: 20px; margin-left: 260px; }
        .headers {
            background-color: #e8bb97;
            padding: 10px;
            border-radius: 30px;
            text-align: center;
            margin-left: -100px;
            margin-right: 100px;
            margin-bottom: 20px;
            position: fixed;
            top: 20px; left: 360px; right: 100px;
            z-index: 1000;
        }
        .headers h2 { color: black; font-size: 24px; font-weight: bold; margin: 0; }
        .selector {
            margin-left: -150px; margin-top: 60px; text-align: center;
            margin-bottom: 30px; position: fixed; top: 55px; left: 360px; right: 100px;
            z-index: 999; padding: 10px;
        }
        select {
            padding: 10px; font-size: 16px; border-radius: 8px; width: 250px;
        }
        .package-section { margin-top: 160px; }
        .package-container {
            display: flex; flex-wrap: wrap; justify-content: center; gap: 30px;
            margin-left: -100px;
        }
        .package-card {
            background: #fff; width: 280px; border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden; text-align: center;
            transition: transform 0.3s; padding-bottom: 15px;
        }
        .package-card:hover { transform: translateY(-8px); }
        .package-card img {
            width: 100%; height: 180px; object-fit: cover;
        }
        .package-info {
            padding: 15px;
        }
        .package-info h3 {
            margin-bottom: 10px; color: #6a1b9a;
        }
        .package-info p {
            margin: 5px 0; font-size: 15px; color: #555;
        }
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
        <h2>Select Your Games Top Up Packages</h2>
    </div>

    <div class="selector">
        <form method="GET">
            <select name="service" onchange="this.form.submit()">
                <option value="">-- Select Game --</option>
                <?php foreach ($games as $g): ?>
                    <option value="<?= $g ?>" <?= $selected_game == $g ? 'selected' : '' ?>>
                        <?= ucwords(str_replace('_', ' ', $g)) ?>
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
                            <p>Delivery: <?= htmlspecialchars($pkg['duration']) ?></p>

                            <form id="cart-form-<?= $pkg['id'] ?>" method="POST" action="cart.php" onsubmit="return confirmUID('<?= $pkg['id'] ?>')">
                                <input type="hidden" name="item_name" value="<?= $pkg['name'] ?>">
                                <input type="hidden" name="price" value="<?= $pkg['price'] ?>">
                                <input type="hidden" name="duration" value="<?= $pkg['duration'] ?>">
                                <input type="hidden" name="target_info" id="uid-hidden-<?= $pkg['id'] ?>">

                                <input type="text" id="uid-input-<?= $pkg['id'] ?>" placeholder="Enter Games UID">
                                <button type="button" class="submit-btn" id="submit-btn-<?= $pkg['id'] ?>" onclick="saveUID('<?= $pkg['id'] ?>')">Submit</button>

                                <button type="submit" name="add_to_cart">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color:white; font-size:18px;">Please select a game to view packages.</p>
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
        alert("⚠️ Please enter your UID before submitting.");
        return;
    }
    hidden.value = input.value.trim();
    input.disabled = true;
    button.style.display = 'none';
}

function confirmUID(id) {
    const hidden = document.getElementById('uid-hidden-' + id);
    if (!hidden.value.trim()) {
        alert("Please enter your UID and click Submit before adding to cart.");
        return false;
    }
    return true;
}
</script>
</body>
</html>
