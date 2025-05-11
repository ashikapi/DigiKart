<!-- sidebar.php -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

 <link rel="stylesheet" href="sidebar.css">
<input type="checkbox" class="openSidebarMenu" id="openSidebarMenu">
<label for="openSidebarMenu" class="sidebarIconToggle">
    <div class="spinner diagonal part-1"></div>
    <div class="spinner horizontal"></div>
    <div class="spinner diagonal part-2"></div>
</label>

<div id="sidebarMenu">
<div class="firstheader">
        <img src="images/digikart_logo.png" alt="" width="220px">
        </div>
        <div class="gape">
        </div>
    <ul class="sidebarMenuInner">
    <li><a href="index.php">🏠 Home</a></li>
        <li><a href="profile.php">🙍 Profile</a></li>
        <li><a href="giftcard.php">🎁 Gift Cards</a></li>
        <li><a href="subscriptions.php">🔔 Subscription</a></li>
        <li><a href="games-topup.php">🎮 Games Top Up</a></li>
        <li><a href="payments.php">💳 Payment Method</a></li>
        <li><a href="order-details.php">📦 Order Details</a></li>
        <li><a href="cart.php">🛒 Your Shopping Cart</a></li>
        <li><a href="giftcard-view.php?order_id=order_id">View Gift Card</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login/Register</a></li>
            <?php endif; ?>
    </ul>
</div>
