<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    if (isset($_POST['booking_id'], $_POST['status']) && !isset($_POST['name'])) {
        $booking_id = $_POST['booking_id'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("UPDATE booking SET status = ? WHERE booking_id = ?");
        $stmt->bind_param("si", $status, $booking_id);

        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Error";
        }

        $stmt->close();
    }

    
    else if (isset($_POST['booking_id'], $_POST['name'], $_POST['email_address'], $_POST['phone'], $_POST['booking_date'], $_POST['booking_time'], $_POST['time_end'], $_POST['status'])) {
        $booking_id = $_POST['booking_id'];
        $name = $_POST['name'];
        $email = $_POST['email_address'];
        $phone = $_POST['phone'];
        $booking_date = $_POST['booking_date'];
        $booking_time = $_POST['booking_time'];
        $time_end = $_POST['time_end'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("UPDATE booking SET name = ?, email_address = ?, phone = ?, booking_date = ?, booking_time = ?, time_end = ?, status = ? WHERE booking_id = ?");
        $stmt->bind_param("sssssssi", $name, $email, $phone, $booking_date, $booking_time, $time_end, $status, $booking_id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Booking updated successfully!');
                    window.location.href = 'admin_dashboard.php';
                  </script>";
        } else {
            echo "Error updating booking: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>
