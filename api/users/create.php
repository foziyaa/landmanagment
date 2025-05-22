<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once("../config/database.php");

$data = json_decode(file_get_contents("php://input"));

if (
    !isset($data->username) || 
    !isset($data->password) || 
    !isset($data->role)
) {
    http_response_code(400);
    echo json_encode(["message" => "Missing required fields: username, password, and role."]);
    exit;
}

$username = trim($data->username);
$password = password_hash(trim($data->password), PASSWORD_DEFAULT); // Secure hash
$role = trim($data->role);

// Insert into database
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $role);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(["message" => "User created successfully."]);
} else {
    http_response_code(500);
    echo json_encode(["message" => "Failed to create user. User might already exist."]);
}

$stmt->close();
$conn->close();
?>
