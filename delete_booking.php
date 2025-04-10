<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    
    $stmt = $conn->prepare("DELETE FROM booking WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error";
    }

    $stmt->close();
    $conn->close();
}
?>
