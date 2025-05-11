<?php

class OrderTransaction {

    // ✅ Get Transaction Info
    public function getRecordQuery($tran_id)
    {
        return "SELECT * FROM orders WHERE tran_id = '" . addslashes($tran_id) . "'";
    }

    // ✅ Save New Transaction (initial step from ssl-init)
    public function saveTransactionQuery($post_data)
    {
        $name = addslashes($post_data['cus_name']);
        $email = addslashes($post_data['cus_email']);
        $phone = addslashes($post_data['cus_phone']);
        $transaction_amount = floatval($post_data['total_amount']);
        $address = addslashes($post_data['cus_add1']);
        $transaction_id = addslashes($post_data['tran_id']);
        $currency = addslashes($post_data['currency']);

        return "INSERT INTO orders (name, email, phone, amount, address, status, tran_id, currency)
                VALUES ('$name', '$email', '$phone', $transaction_amount, '$address', 'Pending', '$transaction_id', '$currency')";
    }

    // ✅ Update Transaction Status
    public function updateTransactionQuery($tran_id, $status = 'Success')
    {
        return "UPDATE orders SET status = '" . addslashes($status) . "' WHERE tran_id = '" . addslashes($tran_id) . "'";
    }

    // ✅ Generate Countdown Redirect Script (5s → cart.php)
    public function getCountdownRedirectScript($seconds = 5, $target = 'cart.php') {
        return "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let seconds = $seconds;
                const countdownElement = document.getElementById('countdown');
                const interval = setInterval(function() {
                    if (countdownElement) countdownElement.textContent = seconds;
                    if (seconds === 0) {
                        clearInterval(interval);
                        window.location.href = '$target';
                    }
                    seconds--;
                }, 1000);
            });
        </script>";
    }
}
?>
