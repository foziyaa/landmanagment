<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once("../config/database.php");

$sql = "SELECT id, owner_name, parcel_code, region, sub_city, kebele, area, status FROM parcels";
$result = $conn->query($sql);

$parcels = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $parcels[] = $row;
    }

    echo json_encode([
        "status" => "success",
        "data" => $parcels
    ]);
} else {
    echo json_encode([
        "status" => "success",
        "data" => [],
        "message" => "No parcels found."
    ]);
}

$conn->close();
?>
