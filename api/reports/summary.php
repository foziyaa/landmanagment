<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once("../config/database.php");

// Initialize summary array
$summary = [];

// Total users
$userQuery = "SELECT COUNT(*) as total_users FROM users";
$userResult = $conn->query($userQuery);
$summary['total_users'] = $userResult->fetch_assoc()['total_users'];

// Total parcels
$parcelQuery = "SELECT COUNT(*) as total_parcels FROM parcels";
$parcelResult = $conn->query($parcelQuery);
$summary['total_parcels'] = $parcelResult->fetch_assoc()['total_parcels'];

// Parcels by status
$statusQuery = "SELECT status, COUNT(*) as count FROM parcels GROUP BY status";
$statusResult = $conn->query($statusQuery);
$summary['parcel_statuses'] = [];
while ($row = $statusResult->fetch_assoc()) {
    $summary['parcel_statuses'][$row['status']] = $row['count'];
}

// Total disputes (optional table)
$disputeQuery = "SELECT COUNT(*) as total_disputes FROM disputes";
if ($conn->query("SHOW TABLES LIKE 'disputes'")->num_rows == 1) {
    $disputeResult = $conn->query($disputeQuery);
    $summary['total_disputes'] = $disputeResult->fetch_assoc()['total_disputes'];
} else {
    $summary['total_disputes'] = 0;
}

echo json_encode([
    "status" => "success",
    "data" => $summary
]);

$conn->close();
?>
