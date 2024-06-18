<?php

require __DIR__ . '/vendor/autoload.php';

use Phpfastcache\Helper\Psr16Adapter;

// Set up caching
$defaultDriver = 'Files';
$cache = new Psr16Adapter($defaultDriver);

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "voting_db";

// Function to log errors
function log_error($message) {
    $log_file = 'ussd_errors.log';
    file_put_contents($log_file, date('Y-m-d H:i:s').": ".$message.PHP_EOL, FILE_APPEND);
}

// Function to send JSON response
function send_response($sessionID, $msisdn, $userID, $continueSession, $message) {
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

try {
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

    // Create a connection to the database
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Initialize or retrieve session state
    if ($newSession) {
        $message = "Welcome to Count My Vote - GH, Kindly choose a service." .
                   "\n1. Vote" .
                   "\n2. View Results" .
                   "\n3. Contact Us";
        $continueSession = true;
        $currentState = [
            'sessionID' => $sessionID,
            'msisdn' => $msisdn,
            'userData' => $userData,
            'network' => $network,
            'newSession' => $newSession,
            'message' => $message,
            'level' => 1,
        ];
        $cache->set($sessionID, [$currentState]);
        send_response($sessionID, $msisdn, $userID, $continueSession, $message);
    }

    $userResponseTracker = $cache->get($sessionID) ?? [];
    if (empty($userResponseTracker)) {
        send_response($sessionID, $msisdn, $userID, false, 'Error! Please dial code again!');
    }

    $lastResponse = end($userResponseTracker);
    $message = "Invalid input.";
    $continueSession = false;

    switch ($lastResponse['level']) {
        case 1:
            $option = $userData;
            if ($option == "1") {
                $message = "Enter contestant code:";
                $continueSession = true;
                $level = 2;
            } elseif ($option == "2") {
                $message = "Visit the following link to view results: http://www.countmyvote.great-site.net\n1.Choose Event\n2.Select Category and Click Results.";
            } elseif ($option == "3") {
                $message = "Contact us at:\nPhone: +233541436414\nEmail: kwamegilbert1114@gmail.com";
            } else {
                $message = "Invalid input.";
            }
            break;

        case 2:
            $contestantCode = $userData;
            $message = "Enter number of votes:";
            $continueSession = true;
            $level = 3;
            break;

        case 3:
            $votes = $userData;
            $contestantCode = $lastResponse['contestantCode'];

            $sql = "SELECT c.contestant_name, c.category_id, c.contestant_id, cat.event_id, cat.cost_per_vote, e.event_name
                    FROM contestants c
                    JOIN categories cat ON c.category_id = cat.category_id
                    JOIN events e ON cat.event_id = e.event_id
                    WHERE c.contestant_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $contestantCode);
            if (!$stmt->execute()) {
                throw new Exception("Database query failed: " . $stmt->error);
            }
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $contestant = $result->fetch_assoc();
                $name = $contestant['contestant_name'];
                $id = $contestant['contestant_id'];
                $category = $contestant['category_id'];
                $event = $contestant['event_name'];  // Ensure the event variable is properly defined
                $cost_per_vote = $contestant['cost_per_vote'];
                $cost = $votes * $cost_per_vote;

                $message = "Confirm voting for $name ($id) in category $category - $event with $votes votes costing $cost GHS. Reply with 1 to confirm.";
                $continueSession = true;
                $level = 4;
            } else {
                $message = "Invalid contestant code.";
            }
            break;

        case 4:
            if ($userData === '1') {
                $contestantCode = $lastResponse['contestantCode'];
                $votes = (int)$lastResponse['votes'];

                $sql = "SELECT cost_per_vote FROM categories WHERE category_id = (SELECT category_id FROM contestants WHERE contestant_id = ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $contestantCode);
                if (!$stmt->execute()) {
                    throw new Exception("Database query failed: " . $stmt->error);
                }
                $result = $stmt->get_result();
                $cost_per_vote = $result->fetch_assoc()['cost_per_vote'];
                $cost = $votes * $cost_per_vote;

                $amount = ceil(($cost * 100) * 1.02);

                $url = "https://api.paystack.co/charge";
                $fields = [
                    'amount' => $amount,
                    'email' => "$event@gmail.com", // Ensure the event variable is used correctly
                    'currency' => 'GHS',
                    'mobile_money' => [
                        'phone' => $msisdn,
                        'provider' => $network // Change provider as needed ('mtn', 'vodafone', 'airtel')
                    ]
                ];
                $fields_string = json_encode($fields);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Authorization: Bearer sk_test_yourSecretKeyHere",
                    "Content-Type: application/json"
                ]);

                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($http_code !== 200) {
                    log_error("Failed to initiate transaction: $response");
                    $message = "Failed to initiate payment. Please try again.";
                } else {
                    $response_data = json_decode($response, true);
                    if ($response_data['status'] === 'success') {
                        $message = "Payment initiated successfully. Please follow the instructions on your phone to complete the payment.";
                    } else {
                        log_error("Payment initiation error: " . json_encode($response_data));
                        $message = "Failed to initiate payment. Please try again.";
                    }
                }
            } else {
                $message = "Invalid input.";
            }
            break;

        default:
            $message = "Invalid input.";
            break;
    }

    $currentState = [
        'sessionID' => $sessionID,
        'msisdn' => $msisdn,
        'userData' => $userData,
        'network' => $network,
        'newSession' => $newSession,
        'message' => $message,
        'level' => $level ?? $lastResponse['level'],
        'contestantCode' => $contestantCode ?? null,
        'votes' => $votes ?? null,
    ];
    $userResponseTracker[] = $currentState;
    $cache->set($sessionID, $userResponseTracker);

    send_response($sessionID, $msisdn, $userID, $continueSession, $message);

} catch (Exception $e) {
    log_error($e->getMessage());
    send_response($sessionID, $msisdn, $userID, false, 'An error occurred. Please try again later.');
}

?>
