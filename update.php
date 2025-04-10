<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    
    if (!empty($booking_id) && !empty($status)) {
        $sql = "UPDATE booking SET status = ? WHERE booking_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $booking_id);

        if ($stmt->execute()) {
            echo "Booking status updated successfully!";
        } else {
            echo "Error updating booking: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Invalid request!";
    }
}

$conn->close();
?>
