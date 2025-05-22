<?php
// api/parcels/assign_owner.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // for dev only
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$mysqli = new mysqli("localhost", "root", "", "lms");

if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$parcel_id = $data['parcel_id'] ?? null;
$owner_id = $data['owner_id'] ?? null;
$assigned_by = $data['admin_id'] ?? null; // who assigned it (optional for audit logs)

if (!$parcel_id || !$owner_id) {
    http_response_code(400);
    echo json_encode(["error" => "parcel_id and owner_id are required."]);
    exit;
}

// Check if parcel exists
$pstmt = $mysqli->prepare("SELECT id FROM parcels WHERE id = ?");
$pstmt->bind_param("i", $parcel_id);
$pstmt->execute();
$pstmt->store_result();
if ($pstmt->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["error" => "Parcel not found."]);
    exit;
}
$pstmt->close();

// Update parcel with new owner
$update = $mysqli->prepare("UPDATE parcels SET owner_id = ? WHERE id = ?");
$update->bind_param("ii", $owner_id, $parcel_id);

if ($update->execute()) {
    // Optional: log this action
    if ($assigned_by) {
        $action = "Assigned owner (User ID $owner_id) to Parcel ID $parcel_id";
        $target = "Parcel ID $parcel_id";

        $log = $mysqli->prepare("INSERT INTO audit_logs (user_id, action, target) VALUES (?, ?, ?)");
        $log->bind_param("iss", $assigned_by, $action, $target);
        $log->execute();
    }

    echo json_encode(["success" => true, "message" => "Owner assigned successfully."]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to assign owner."]);
}
