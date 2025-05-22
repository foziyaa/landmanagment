<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once("../config/database.php"); // DB connection
include_once("../models/User.php");     // User model

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->username) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(["message" => "Username and password required."]);
    exit;
}

$user = new User($conn);
$user->username = $data->username;

if ($user->fetchUserByUsername()) {
    if (password_verify($data->password, $user->password)) {
        echo json_encode([
            "message" => "Login successful.",
            "user" => [
                "id" => $user->id,
                "username" => $user->username,
                "role" => $user->role
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Invalid credentials."]);
    }
} else {
    http_response_code(404);
    echo json_encode(["message" => "User not found."]);
}
?>
