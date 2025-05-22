<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once("../config/database.php");

$data = json_decode(file_get_contents("php://input"));

if (
    !isset($data->id) ||
    !isset($data->username) ||
    !isset($data->role)
) {
    http_response_code(400);
    echo json_encode(["message" => "ID, username, and role are required."]);
    exit;
}

$id = intval($data->id);
$username = trim($data->username);
$role = trim($data->role);

// Optional: update password if provided
$password_sql = "";
$bind_types = "ssi"; // s = string, i = int
$bind_values = [$username, $role, $id];

if (!empty($data->password)) {
    $hashed_password = password_hash(trim($data->password), PASSWORD_DEFAULT);
    $password_sql = ", password = ?";
    $bind_types = "sssi";
    $bind_values = [$username, $role, $hashed_password, $id];
}

$sql = "UPDATE users SET username = ?, role = ? $password_sql WHERE id = ?";
$stmt = $conn->prepare($sql);

$stmt->bind_param($bind_types, ...$bind_values);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["message" => "User updated successfully."]);
    } else {
        echo json_encode(["message" => "No changes made or user not found."]);
    }
} else {
    http_response_code(500);
    echo json_encode(["message" => "Failed to update user."]);
}

$stmt->close();
$conn->close();
?>
