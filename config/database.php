<?php
$host = "localhost";
$db_name = "land_management";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $db_name);
if ($conn->connect_error) {
    die(json_encode(["message" => "Database connection failed."]));
}
?>
