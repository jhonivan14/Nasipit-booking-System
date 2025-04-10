<?php
include 'db_connect.php';


$sql = "SELECT COUNT(*) AS total_pending FROM booking WHERE status = 'Pending'";
$result = $conn->query($sql);


if ($result) {
    $row = $result->fetch_assoc();
    echo json_encode(["total_pending" => $row['total_pending']]);
} else {
    echo json_encode(["total_pending" => 0]); //  If query fails, return 0
}

$conn->close();
?>
