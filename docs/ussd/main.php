<?php

require __DIR__ . '/vendor/autoload.php';

use Phpfastcache\Helper\Psr16Adapter;

// Set up caching
$defaultDriver = 'Files';
$Psr16Adapter = new Psr16Adapter($defaultDriver);

// Get the JSON contents
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Retrieve session data
$sessionID = $data['sessionID'];
$userID = $data['userID'];
$newSession = $data['newSession'];
$msisdn = $data['msisdn'];
$userData = $data['userData'];
$network = $data['network'];

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "voting_db";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the database connection
if ($conn->connect_error) {
    throw new Exception("Connection failed: " . $conn->connect_error);
}

// Log file for debugging
$log_file = 'ussd_errors.log';

// Function to log errors
function log_error($message) {
    global $log_file;
    file_put_contents($log_file, date('Y-m-d H:i:s').": ".$message.PHP_EOL, FILE_APPEND);
}

if ($newSession) {
    $message = "Welcome Count My Vote - GH, Kindly choose a service." .
        "\n1. Vote" .
        "\n2. View Results" .
        "\n3. Contact Us";
    $continueSession = true;

    // Initialize the session state
    $currentState = [
        'sessionID' => $sessionID,
        'msisdn' => $msisdn,
        'userData' => $userData,
        'network'   => $network,
        'newSession' => $newSession,
        'message' => $message,
        'level' => 1,
    ];

    $userResponseTracker = $Psr16Adapter->get($sessionID);

    if (!$userResponseTracker) {
        $userResponseTracker = [$currentState];
    } else {
        $userResponseTracker[] = $currentState;
    }

    $Psr16Adapter->set($sessionID, $userResponseTracker);

    http_response_code(200);
    header('Content-Type: application/json');

    echo json_encode([
        'sessionID' => $sessionID,
        'msisdn' => $msisdn,
        'userID' => $userID,
        'continueSession' => $continueSession,
        'message' => $message,
    ]);
    exit();
}

$userResponseTracker = $Psr16Adapter->get($sessionID) ?? [];

if (!(count($userResponseTracker) > 0)) {
    http_response_code(200);
    header('Content-Type: application/json');

    echo json_encode([
        'sessionID' => $sessionID,
        'msisdn' => $msisdn,
        'userID' => $userID,
        'continueSession' => false,
        'message' => 'Error! Please dial code again!',
    ]);
    exit();
}

$lastResponse = $userResponseTracker[count($userResponseTracker) - 1];
$message = "Invalid input.";
$continueSession = false;

if ($lastResponse['level'] === 1) {
    $option = $userData;

    if ($option == "1") {
        $message = "Enter contestant code:";
        $continueSession = true;
        $level = 2;
    } else if ($option == "2") {
        $message = "Visit the following link to view results: http://www.countmyvote.great-site.net\n1.Choose Event\n2.Select Category and Click Results.";
        $continueSession = false;
    } else if ($option == "3") {
        $message = "Contact us at:\nPhone: +233541436414\nEmail: kwamegilbert1114@gmail.com";
        $continueSession = false;
    } else {
        $message = "Invalid input.";
        $continueSession = false;
    }

    $currentState = [
        'sessionID' => $sessionID,
        'msisdn' => $msisdn,
        'userData' => $userData,
        'network'   => $network,
        'newSession' => $newSession,
        'message' => $message,
        'level' => $level,
    ];

    $userResponseTracker[] = $currentState;
    $Psr16Adapter->set($sessionID, $userResponseTracker);
} else if ($lastResponse['level'] === 2) {
    $contestantCode = $userData;
    $message = "Enter number of votes:";
    $continueSession = true;
    $level = 3;

    $currentState = [
        'sessionID' => $sessionID,
        'msisdn' => $msisdn,
        'userData' => $userData,
        'network'   => $network,
        'newSession' => $newSession,
        'message' => $message,
        'level' => $level,
        'contestantCode' => $contestantCode,
    ];

    $userResponseTracker[] = $currentState;
    $Psr16Adapter->set($sessionID, $userResponseTracker);
} else if ($lastResponse['level'] === 3) {
    $votes = $userData;
    $contestantCode = $lastResponse['contestantCode'];

    // Fetch contestant details from the database
    $sql = "SELECT 
                c.contestant_name,
                c.category_id,
                c.contestant_id,
                cat.event_id,
                cat.cost_per_vote,
                e.event_name
            FROM 
                contestants c
            JOIN 
                categories cat ON c.category_id = cat.category_id
            JOIN 
                events e ON cat.event_id = e.event_id
            WHERE 
                c.contestant_id = '$contestantCode'";

    log_error("Executing SQL: $sql");

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Database query failed: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $contestant = $result->fetch_assoc();
        $name = $contestant['contestant_name'];
        $id = $contestant['contestant_id'];
        $category = $contestant['category_id'];
        $event = $contestant['event_name'];
        $cost_per_vote = $contestant['cost_per_vote'];
        $cost = $votes * $cost_per_vote;

        // Confirm voting details
        $message = "Confirm voting for $name ($id) in category $category - $event with $votes votes costing $cost GHS. Reply with 1 to confirm.";
        $continueSession = true;
        $level = 4;
    } else {
        // Handle invalid contestant code
        $message = "Invalid contestant code.";
        $continueSession = false;
    }

    $currentState = [
        'sessionID' => $sessionID,
        'msisdn' => $msisdn,
        'userData' => $userData,
        'network'   => $network,
        'newSession' => $newSession,
        'message' => $message,
        'level' => $level,
        'contestantCode' => $contestantCode,
        'votes' => $votes,
    ];

    $userResponseTracker[] = $currentState;
    $Psr16Adapter->set($sessionID, $userResponseTracker);
} else if ($lastResponse['level'] === 4 && $userData === '1') {
    $contestantCode = $lastResponse['contestantCode'];
    $votes = (int)$lastResponse['votes'];

    // Fetch cost per vote again
    $sql = "SELECT cost_per_vote FROM categories WHERE category_id = (
        SELECT category_id FROM contestants WHERE contestant_id = '$contestantCode'
    )";
    log_error("Executing SQL for cost per vote: $sql");

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Database query failed: " . $conn->error);
    }

    $cost_per_vote = $result->fetch_assoc()['cost_per_vote'];
    $cost = $votes * $cost_per_vote;

    // Set up Paystack mobile money transaction details
    $amount = ceil(($cost * 100) * 1.02); // Paystack expects the amount in kobo, including 2% fees

    $url = "https://api.paystack.co/charge";
    $fields = [
        'amount' => $amount,
        'email' => "$event@gmail.com", // Replace with a user-specific email if available
        'currency' => 'GHS',
        'mobile_money' => [
            'phone' => $msisdn,
            'provider' => $network // Change provider as needed ('mtn', 'vodafone', 'airtel')
        ]
    ];
    $fields_string = json_encode($fields);

    // Initialize cURL for Paystack API call
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer sk_test_yourSecretKeyHere",
        "Content-Type: application/json"
    ]);

    // Execute the API call
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($http_code !== 200) {
        log_error("Failed to initiate transaction: $response");
        $message = "Failed to initiate payment. Please try again.";
        $continueSession = false;
    } else {
        $response_data = json_decode($response, true);
        if ($response_data['status'] === 'success') {
            $message = "Payment initiated successfully. Please follow the instructions on your phone to complete the payment.";
            $continueSession = false;
        } else {
            log_error("Payment initiation error: " . json_encode($response_data));
            $message = "Failed to initiate payment. Please try again.";
            $continueSession = false;
        }
    }

    curl_close($ch);
} else {
    $message = "Invalid input.";
    $continueSession = false;
}

http_response_code(200);
header('Content-Type: application/json');

echo json_encode([
    'sessionID' => $sessionID,
    'msisdn' => $msisdn,
    'userID' => $userID,
    'continueSession' => $continueSession,
    'message' => $message,
]);

?>
