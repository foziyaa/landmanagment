<?php
// api/parcels/create.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$mysqli = new mysqli("localhost", "root", "", "lms");

if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

// Required fields
$parcel_code = trim($data['parcel_code'] ?? '');
$region = trim($data['region'] ?? '');
$subcity = trim($data['subcity'] ?? '');
$kebele = trim($data['kebele'] ?? '');
$size = intval($data['size'] ?? 0);

// Optional fields
$lat = floatval($data['lat'] ?? 0);
$lng = floatval($data['lng'] ?? 0);
$owner_id = $data['owner_id'] ?? null;
$created_by = $data['admin_id'] ?? null;

if (!$parcel_code || !$size) {
    http_response_code(400);
    echo json_encode(["error" => "Parcel code and size are required."]);
    exit;
}

// Insert new parcel
$stmt = $mysqli->prepare("INSERT INTO parcels (parcel_code, region, subcity, kebele, size, lat, lng, owner_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssiddi", $parcel_code, $region, $subcity, $kebele, $size, $lat, $lng, $owner_id);

if ($stmt->execute()) {
    $new_id = $stmt->insert_id;

    // Optional log entry
    if ($created_by) {
        $action = "Created parcel with code $parcel_code";
        $target = "Parcel ID $new_id";
        $log = $mysqli->prepare("INSERT INTO audit_logs (user_id, action, target) VALUES (?, ?, ?)");
        $log->bind_param("iss", $created_by, $action, $target);
        $log->execute();
    }

    echo json_encode([
        "success" => true,
        "message" => "Parcel created successfully.",
        "parcel_id" => $new_id
    ]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to insert parcel."]);
}
