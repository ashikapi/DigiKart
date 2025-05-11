<?php 
include("config.php");
include("sidebar.php");
include("whatsapp.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gift Cards</title>
    <link rel="stylesheet" href="giftcard.css">
</head>
<body class="giftcard-page"> 

<div class="wrapper">
    <div class="headerr">
        <h2>Gift Cards</h2>
    </div>

    <div class="card-container">
        <div class="card"> <a href="gift-packages.php?service=bata">
            <img src="images/bata.jpg" alt="Bata" height="100px"></a>
            <p>Bata</p>
        </div>
        <div class="card"> <a href="gift-packages.php?service=google">
            <img src="images/google.png" alt="Google"></a>
            <p>Google</p>
        </div>
        <div class="card"> <a href="gift-packages.php?service=apex">
            <img src="images/apex.png" alt="Apex"></a>
            <p>Apex</p>
        </div>
        <div class="card"> <a href="gift-packages.php?service=arong">
            <img src="images/arong.png" alt="Arong"></a>
            <p>Arong</p>
        </div>
        <div class="card"> <a href="gift-packages.php?service=nordvpn">
            <img src="images/nordvpn.png" alt="Nord VPN"></a>
            <p>Nord VPN</p>
        </div>
    </div>
</div>

</body>
</html>
