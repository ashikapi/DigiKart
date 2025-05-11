<?php
session_start();
include("config.php"); // DB connection
include("sidebar.php");
include("whatsapp.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiKart</title>
    <link rel="stylesheet" href="index.css">
</head>
<body class="index-page"> <!-- ✅ Class Added Here -->

    <div class="main-content">
    <header class="main-header">
    <h2>Welcome 
        <?php
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $result = mysqli_query($conn, "SELECT username FROM user_info WHERE id = '$user_id'");
            $user = mysqli_fetch_assoc($result);
            echo htmlspecialchars($user['username']);
        } else {
            echo "Guest";
        }
        ?>
    </h2>
    
   
    
    <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="login.php" class="login-btn">Login/Register</a>
    <?php endif; ?>
</header>

        <div class="gift-card">
            <h2>GIFT CARD</h2>
        </div>
        <div class="card-container1">
            <div class="cards"><a href="gift-packages.php?service=arong"><img src="images/arong.png" alt="Arong"></a><p>ARONG</p></div>
            <div class="cards"><a href="gift-packages.php?service=apex"><img src="images/apex.png" alt="Apex"></a><p>APEX</p></div>
            <div class="cards"><a href="gift-packages.php?service=nordvpn"><img src="images/nordvpn.png" alt="Nord VPN"></a><p>Nord VPN</p></div>
            <div class="cards"><a href="gift-packages.php?service=google"><img src="images/google.png" alt="Google Play"></a><p>GOOGLE PLAY</p></div>
            <div class="cards"><a href="giftcard.php"><img src="images/rsz_arrow1.png" alt="More..."></a><p>More...</p></div>
        </div>

        <div class="gift-card">
            <h2>SUBSCRIPTIONS</h2>
        </div>
        <div class="card-container2">
            <div class="cards"><a href="subscriptions-package.php?service=netflix"><img src="images/netflix.png" alt="Netflix"></a><p>Netflix</p></div>
            <div class="cards"><a href="subscriptions-package.php?service=bongo"><img src="images/bongo.png" alt="Bongo Bd"></a><p>Bongo Bd</p></div>
            <div class="cards"><a href="subscriptions-package.php?service=hoichoi"><img src="images/hoicoi.png" alt="Hoi-Choi"></a><p>Hoi-Choi</p></div>
            <div class="cards"><a href="subscriptions-package.php?service=chorki"><img src="images/chorki.png" alt="Chorki"></a><p>Chorki</p></div>
            <div class="cards"><a href="subscriptions-package.php?service=canva"><img src="images/canva.png" alt="Canva Pro"></a><p>Canva Pro</p></div>
            <div class="cards"><a href="subscriptions.php"><img src="images/arrow1.png" alt="More..."></a><p>More...</p></div>
        </div>

        <div class="gift-card">
            <h2>GAMES TOP UP</h2>
        </div>
        <div class="card-container3">
            <div class="cards"><a href="games-topup_packages.php?service=pubg"><img src="images/pubg.jpg" height="90px" alt="PUBG"></a><p>PUBG</p></div>
            <div class="cards"><a href="games-topup_packages.php?service=freefire"><img src="images/freefire.png" alt="Free Fire"></a><p>Free Fire</p></div>
            <div class="cards"><a href="games-topup_packages.php?service=callofduty"><img src="images/cod.png" alt="Call of Duty"></a><p>Call of Duty</p></div>
            <div class="cards"><a href="games-topup_packages.php?service=gta"><img src="images/gta.png" alt="GTA"></a><p>Grand Theft Auto</p></div>
            <div class="cards"><a href="games-topup_packages.php?service=clashroyale"><img src="images/clashroyl.png" alt="Clash Royale"></a><p>Clash Royale</p></div>
            <div class="cards"><a href="games-topup.php"><img src="images/arrow1.png" alt="More..."></a><p>More...</p></div>
        </div>

    </div>
</body>
</html>






