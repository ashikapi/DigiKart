<?php 
include("config.php");
include("sidebar.php");
include("whatsapp.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscriptions</title>
    <link rel="stylesheet" href="subscriptions.css">
</head>
<body>

<div class="wrapper">
    <div class="headerr">
        <h2>Subscriptions</h2>
    </div>

    <div class="card-container">
        <div class="card"> <a href="subscriptions-package.php?service=netflix">
            <img src="images/netflix.png" alt="Netflix"></a>
            <p>Netflix</p>
        </div>
        <div class="card"><a href="subscriptions-package.php?service=bongo">
            <img src="images/bongo.png" alt="Bongo Bd"></a>
            <p>Bongo Bd</p>
        </div>
        <div class="card"><a href="subscriptions-package.php?service=hoichoi">
            <img src="images/hoicoi.png" alt="Hoi-Choi"></a>
            <p>Hoi-Choi</p>
        </div>
        <div class="card"><a href="subscriptions-package.php?service=chorki">
            <img src="images/chorki.png" alt="Chorki"></a>
            <p>Chorki</p>
        </div>
        <div class="card"><a href="subscriptions-package.php?service=canva">
            <img src="images/canva.png" alt="Canva Pro"></a>
            <p>Canva Pro</p>
        </div>
        <div class="card"><a href="subscriptions-package.php?service=telegram">
            <img src="images/telegram.png" alt="Telegram Pro"></a>
            <p>Telegram Pro</p>
        </div>
        <div class="card"><a href="subscriptions-package.php?service=crunchyroll">
            <img src="images/crunchyroll.png" alt="Crunchyroll"></a>
            <p>Crunchyroll</p>
        </div>
        <div class="card"><a href="subscriptions-package.php?service=youtube">
            <img src="images/youtube.png" alt="YouTube Premium"></a>
            <p>YouTube Premium</p>
        </div>
    </div>
</div>

</body>
</html>
