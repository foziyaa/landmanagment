<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once("../config/database.php");

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->id)) {
    http_response_code(400);
    echo json_encode(["message" => "User ID is required."]);
    exit;
}

$id = intval($data->id);

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["message" => "User deleted successfully."]);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "User not found."]);
    }
} else {
    http_response_code(500);
    echo json_encode(["message" => "Failed to delete user."]);
}

$stmt->close();
$conn->close();
?>
