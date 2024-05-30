<?php

require_once 'config.php';
require_once 'db_connect.php';

// Read the variables sent via POST from our API
$sessionId   = $_POST["sessionId"];
$serviceCode = $_POST["serviceCode"];
$phoneNumber = $_POST["phoneNumber"];
$networkCode = $_POST["networkCode"];
$text        = $_POST["text"];

// Log the request for debugging
logUssdRequest("Session ID: $sessionId, Service Code: $serviceCode,Network: $networkCode Phone Number: $phoneNumber, Text: $text\n");

//USSD App handler.
if ($text == "") {
    // This is the first request. Note how we start the response with CON
    $response  = "CON Welcome to Count My Vote GH \n";
    $response .= "What do you want to do? \n";
    $response .= "1. Vote \n";
    $response .= "2. Results \n";
    $response .= "3. Contact Us \n";
} else {
    // Explode the text to get the user's input levels
    $inputArray = explode("*", $text);

    if ($inputArray[0] == "1") {
        if (count($inputArray) == 1) {
            // Ask for nominee code
            $response = "CON COUNT MY VOTE GHANA \n";
            $response .= "Enter nominee code \n";
        } elseif (count($inputArray) == 2) {
            $nomineeCode = $inputArray[1];
            // Process the nominee code here (e.g., save to a database)
            // Assume a successful process for this example
            $response = "END You have voted for nominee code $nomineeCode. Thank you for voting.";
        }
    } elseif ($inputArray[0] == "2") {
        $response = "END Results are pending. \n".$phoneNumber;
    } elseif ($inputArray[0] == "3") {
        $response = "END Contact us at contact@countmyvotegh.com or call 123-456-7890.";
    } else {
        $response = "END Invalid option. Please try again.";
    }
}

// Echo the response back to the API
header('Content-type: text/plain');
echo $response;
?>
