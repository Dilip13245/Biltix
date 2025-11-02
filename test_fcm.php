<?php
/**
 * Test FCM Notification
 * 
 * This test script reads Firebase credentials from the JSON file
 * instead of using a hardcoded server key.
 * 
 * Usage: php test_fcm.php [device_token]
 */

// Load credentials from JSON file
$credentialsPath = __DIR__ . '/config/firebase-credentials.json';
$apiKey = null;
$projectId = null;

if (file_exists($credentialsPath)) {
    $credentials = json_decode(file_get_contents($credentialsPath), true);
    if (isset($credentials['client'][0]['api_key'][0]['current_key'])) {
        $apiKey = $credentials['client'][0]['api_key'][0]['current_key'];
    }
    if (isset($credentials['project_info']['project_id'])) {
        $projectId = $credentials['project_info']['project_id'];
    }
}

if (empty($apiKey)) {
    die("Error: Could not load API key from credentials file.\n");
}

// Get device token from command line argument or use default
$token = $argv[1] ?? "eeqQLpinSgSQRUJM-TfMLm:APA91bFxYxi9drkR96eL3qeq9lj";

echo "=== FCM Test Notification ===\n";
echo "Project ID: " . ($projectId ?? 'N/A') . "\n";
echo "Device Token: " . substr($token, 0, 30) . "...\n\n";

// Legacy API payload (using API key from JSON)
$payload = [
    'to' => $token,
    'notification' => [
        'title' => 'Test Notification',
        'body' => 'This is a test message from Biltix'
    ],
    'data' => [
        'notification_type' => 'test',
        'priority' => 'high'
    ],
    'priority' => 'high'
];

echo "Sending notification via Legacy API...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: key=' . $apiKey,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "\n=== Response ===\n";
echo "HTTP Code: " . $httpCode . "\n";
if ($error) {
    echo "CURL Error: " . $error . "\n";
}
echo "Response: " . $response . "\n";

$responseData = json_decode($response, true);
if ($responseData) {
    echo "\nParsed Response:\n";
    print_r($responseData);
    
    if (isset($responseData['success']) && $responseData['success'] == 1) {
        echo "\n✅ Notification sent successfully!\n";
    } else {
        echo "\n❌ Notification failed\n";
    }
}
echo "\n";
?>

