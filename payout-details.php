<?php
// Function to decrypt data
function decryptData($encrypted_data, $encryption_key) {
    $cipher = "AES-256-CBC";
    list($encrypted, $iv) = explode('::', base64_decode($encrypted_data), 2);
    return json_decode(openssl_decrypt($encrypted, $cipher, $encryption_key, 0, $iv), true);
}

if (!isset($_GET['data'])) {
    echo "No payout details found.";
    exit;
}

$encrypted_data = $_GET['data'];
$encryption_key = 'your-encryption-key'; // Same key used during encryption

// Decrypt the data
$payout_details = decryptData($encrypted_data, $encryption_key);

if (!$payout_details) {
    echo "Failed to decrypt payout details.";
    exit;
}

// Display payout details
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payout Details</title>
</head>
<body>
    <h1>Payout Successful</h1>
    <p><strong>Cashfree Transfer ID:</strong> <?php echo htmlspecialchars($payout_details['cf_transfer_id']); ?></p>
    <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($payout_details['transfer_id']); ?></p>
    <p><strong>Amount:</strong> ₹<?php echo htmlspecialchars($payout_details['amount']); ?></p>
    <p><strong>Beneficiary Name:</strong> <?php echo htmlspecialchars($payout_details['beneficiary_name']); ?></p>
    <p><strong>Remarks:</strong> <?php echo htmlspecialchars($payout_details['remarks']); ?></p>
</body>
</html>
