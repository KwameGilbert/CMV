<?php
// Configuration settings
define('LOG_FILE', 'logs/ussd_log.txt');

// Function to log USSD requests and responses
function logUssdRequest($data) {
    file_put_contents(LOG_FILE, $data, FILE_APPEND);
}
?>
