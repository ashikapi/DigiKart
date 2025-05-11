<?php
session_start();
include("config.php");
include("sidebar.php");
include("whatsapp.php");

$pageTitle = 'User Profile';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user info safely
$stmt = mysqli_prepare($conn, "SELECT * FROM user_info WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Fallback if not found
if (!$user) {
    echo "User not found.";
    exit();
}

// Adjusted avatar system — using simple filename, like second code
$avatar = $user['gender'] === 'male' ? 'images/boyuser.webp' : 'images/girluser.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profile.css">
</head>
<body class="profile-page"> <!-- ✅ Class added here -->

<!-- Profile Info -->
<div class="headers"><h2>Profile</h2></div>
<div class="container">

    <div class="profile-box">
        <div class="profile-image">
            <!-- Final updated image line -->
            <img src="<?php echo $avatar; ?>" alt="Profile Image">
        </div>

        <div class="profile-details">
            <div class="detail-row">
                <span class="label">User Name:</span>
                <span class="value"><?php echo htmlspecialchars($user['username']); ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Full Name:</span>
                <span class="value"><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Email:</span>
                <span class="value"><?php echo htmlspecialchars($user['email']); ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Phone:</span>
                <span class="value"><?php echo htmlspecialchars($user['phone']); ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Gender:</span>
                <span class="value"><?php echo ucfirst($user['gender']); ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Registered:</span>
                <span class="value"><?php echo htmlspecialchars($user['created_at'] ?? 'N/A'); ?></span>
            </div>
        </div>
    </div>
</div>

</body>
</html>
