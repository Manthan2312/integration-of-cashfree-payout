
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashfree Payout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #4CAF50;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input[type="text"], input[type="number"], input[type="email"], input[type="tel"], button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Initiate Payout</h1>
        <form action="" method="POST">
            <label for="beneficiary_name">Beneficiary Name</label>
            <input type="text" id="beneficiary_name" name="beneficiary_name" value="John Doe" required>

            <label for="bank_account_number">Bank Account Number</label>
            <input type="text" id="bank_account_number" name="bank_account_number" value="026291800001191" required>

            <label for="bank_ifsc">Bank IFSC Code</label>
            <input type="text" id="bank_ifsc" name="bank_ifsc" value="YESB0000262" required>

            <label for="beneficiary_email">Beneficiary Email</label>
            <input type="email" id="beneficiary_email" name="beneficiary_email" value="john@example.com" required>

            <label for="beneficiary_phone">Beneficiary Phone</label>
            <input type="tel" id="beneficiary_phone" name="beneficiary_phone" value="9999999999" required>

            <label for="transfer_amount">Transfer Amount (INR)</label>
            <input type="number" id="transfer_amount" name="transfer_amount" value="100" required>

            <label for="transfer_remarks">Remarks</label>
            <input type="text" id="transfer_remarks" name="transfer_remarks" value="Salary for May 2023" required>

            <button type="submit">Submit</button>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Fetch form data dynamically
        $transfer_id = 'txn_' . time();
        $transfer_amount = $_POST['transfer_amount'];
        $beneficiary_name = $_POST['beneficiary_name'];
        $bank_account_number = $_POST['bank_account_number'];
        $bank_ifsc = $_POST['bank_ifsc'];
        $beneficiary_email = $_POST['beneficiary_email'];
        $beneficiary_phone = $_POST['beneficiary_phone'];
        $transfer_remarks = $_POST['transfer_remarks'];

        // Your Cashfree API integration code
        // Replace this comment with the API call as shown in the original script
        
// Function to encrypt data
function encryptData($data, $encryption_key) {
    $cipher = "AES-256-CBC";
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
    $encrypted = openssl_encrypt(json_encode($data), $cipher, $encryption_key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

// Fetch the public IP address of the server
$public_ip = file_get_contents("https://api.ipify.org");

echo "Public IP Address of the server: " . $public_ip . "\n";

// Cashfree credentials and API version
$client_id = ''; // Your Cashfree App ID
$client_secret = ''; // Your Cashfree Secret Key
$api_version = '2024-01-01'; // API Version
$sandbox_mode = true; // Set to false for production

// Prepare the API endpoint (sandbox or production)
$endpoint = $sandbox_mode ? 'https://sandbox.cashfree.com/payout/transfers' : 'https://api.cashfree.com/payout/transfers';

// Prepare the transfer details
$transfer_id = 'txn_' . time(); // Unique transfer ID
$transfer_amount = 100; // Amount in INR
$beneficiary_name = 'John Doe'; // Beneficiary name
$bank_account_number = '026291800001191'; // Bank account number
$bank_ifsc = 'YESB0000262'; // Bank IFSC code
$beneficiary_email = 'john@example.com'; // Beneficiary email
$beneficiary_phone = '9999999999'; // Beneficiary phone number
$transfer_remarks = 'Salary for May 2023'; // Remarks for the transfer

// Construct the request body
$request_body = json_encode([
    'transfer_id' => $transfer_id,
    'transfer_amount' => $transfer_amount,
    'transfer_currency' => 'INR',
    'transfer_mode' => 'banktransfer',
    'beneficiary_details' => [
        'beneficiary_name' => $beneficiary_name,
        'beneficiary_instrument_details' => [
            'bank_account_number' => $bank_account_number,
            'bank_ifsc' => $bank_ifsc
        ],
        'beneficiary_contact_details' => [
            'beneficiary_email' => $beneficiary_email,
            'beneficiary_phone' => $beneficiary_phone
        ]
    ],
    'transfer_remarks' => $transfer_remarks
]);

// Set the headers for the request
$headers = [
    'x-client-id: ' . $client_id,
    'x-client-secret: ' . $client_secret,
    'x-api-version: ' . $api_version,
    'Content-Type: application/json'
];

// Initialize cURL session
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $endpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $request_body,
    CURLOPT_HTTPHEADER => $headers
]);

// Execute cURL request and capture the response
$response = curl_exec($curl);
$err = curl_error($curl);
$response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// Close cURL session
curl_close($curl);

// Error handling and response logging
if ($err) {
    echo "cURL Error #: " . $err;
} else {
    $response_data = json_decode($response, true);
    if (isset($response_data['cf_transfer_id'])) {
        // Transfer was successful
        $payout_details = [
            'cf_transfer_id' => $response_data['cf_transfer_id'],
            'transfer_id' => $transfer_id,
            'amount' => $transfer_amount,
            'beneficiary_name' => $beneficiary_name,
            'remarks' => $transfer_remarks
        ];

        // Encrypt the payout details
        $encryption_key = 'your-encryption-key'; // Replace with a strong key
        $encrypted_data = encryptData($payout_details, $encryption_key);

        // Redirect to the next page with encrypted details
        header('Location: payout-details.php?data=' . urlencode($encrypted_data));
        exit;
    } elseif (isset($response_data['type']) && $response_data['type'] === 'authentication_error') {
        echo "Error: " . $response_data['message'] . "\n";
        echo "Your IP is not whitelisted. Please whitelist this IP: " . $public_ip . "\n";
    } else {
        echo "Unexpected response: " . $response . "\n";
    }
}


        echo "<p style='text-align: center; color: green;'>Payout details submitted successfully!</p>";
    }
    ?>
</body>
</html>
