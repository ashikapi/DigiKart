<?php
file_put_contents("ipn_log.txt", json_encode($_POST) . PHP_EOL, FILE_APPEND);
?>
