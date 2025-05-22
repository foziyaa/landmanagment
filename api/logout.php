<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Start session and destroy it
session_start();
session_unset();
session_destroy();

echo json_encode(["message" => "Logout successful."]);
?>
