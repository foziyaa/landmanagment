<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once("../config/database.php");

// Get ID from query string, e.g., /users/get.php?id=3
if (!isset($_GET["id"])) {
    http_response_code(400);
    echo json_encode(["message" => "ID parameter is required."]);
    exit;
}

$id = intval($_GET["id"]);

$stmt = $conn->prepare("SELECT id, username, role FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    echo json_encode(["status" => "success", "data" => $user]);
} else {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "User not found."]);
}

$stmt->close();
$conn->close();
?>
