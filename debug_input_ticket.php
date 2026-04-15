<?php
// Debug input ticket creation
$session_id = 't5tiu60t82sbdjdooe32sjjg38';

echo "=== Debug Input Ticket Creation ===\n";

// Test data
$ticket_data = [
    'createAt' => date('Y-m-d H:i:s'),
    'supplierId' => 1,
    'total' => 225000,
    'status' => 'Đang chờ xác nhận'
];

echo "Sending data:\n";
print_r($ticket_data);
echo "\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/he-thong-thong-tin-doanh-nghiep/api/input_tickets/create.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $ticket_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Cookie: PHPSESSID=' . $session_id
]);
curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $http_code\n";
if ($curl_error) {
    echo "CURL Error: $curl_error\n";
}
echo "Response: $response\n";
?>