<?php
// Show all PHP errors (for debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    echo "❌ Unauthorized access! Please log in first.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for user
$result = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id");

$total_price = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $total_price += $row['price'];
}

if ($total_price == 0) {
    echo "❌ Your cart is empty. Please add items before checking out.";
    exit;
}

// Prepare payment data
$post_data = array();
$post_data['store_id'] = "enter your store id";
$post_data['store_passwd'] = "enter your store password";
$post_data['total_amount'] = $total_price;
$post_data['currency'] = "BDT";
$post_data['tran_id'] = uniqid("TRX_");

$post_data['success_url'] = "http://localhost:8000/success.php";
$post_data['fail_url'] = "http://localhost:8000/fail.php";
$post_data['cancel_url'] = "http://localhost:8000/cancel.php";
$post_data['ipn_url'] = "http://localhost:8000/ipn.php";

// Customer details (fake for testing)
$post_data['cus_name'] = "Test User";
$post_data['cus_email'] = "test@example.com";
$post_data['cus_add1'] = "Dhaka";
$post_data['cus_add2'] = "";
$post_data['cus_city'] = "Dhaka";
$post_data['cus_state'] = "Dhaka";
$post_data['cus_postcode'] = "1207";
$post_data['cus_country'] = "Bangladesh";
$post_data['cus_phone'] = "01711111111";
$post_data['cus_fax'] = "";

// Product details
$post_data['shipping_method'] = "NO";
$post_data['product_name'] = "Gift Cart Purchase";
$post_data['product_category'] = "Gift";
$post_data['product_profile'] = "general";

// Initiate CURL Request
$direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";
$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $direct_api_url);
curl_setopt($handle, CURLOPT_TIMEOUT, 30);
curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($handle, CURLOPT_POST, 1);
curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); // Sandbox only

$content = curl_exec($handle);
$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

if ($code == 200 && !(curl_errno($handle))) {
    $sslcommerzResponse = json_decode($content, true);

    if (isset($sslcommerzResponse['GatewayPageURL'])) {
        // Redirect to SSLCommerz Gateway
        header("Location: " . $sslcommerzResponse['GatewayPageURL']);
        exit;
    } else {
        echo "⚠️ JSON response invalid or missing GatewayPageURL!<br>";
        echo "<pre>";
        print_r($sslcommerzResponse);
        echo "</pre>";
    }
} else {
    echo "❌ Failed to connect to SSLCommerz API!<br>";
    echo "cURL Error: " . curl_error($handle);
}
curl_close($handle);
?>
