<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Expires: 0");

    header("Location: index.html"); 
    exit();
}
if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM booking WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Booking not found.";
        exit;
    }

    $row = $result->fetch_assoc();
} else {
    echo "Booking ID is missing.";
    exit;
}


$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="view_booking.css">
</head>
<body style="background: #055812;">
    <div class="container py-5">
        <h2 class="text-white mb-4">View Booking #<?= htmlspecialchars($booking_id) ?></h2>

        <div class="border border-success rounded p-4 bg-light">

            <div class="mb-3">
                <label class="form-label">Full Name:</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address:</label>
                <input type="email" class="form-control" value="<?= htmlspecialchars($row['email_address']) ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone:</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($row['phone']) ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Booking Date:</label>
                <input type="date" class="form-control" value="<?= $row['booking_date'] ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Start Time:</label>
                <input type="time" class="form-control" value="<?= $row['booking_time'] ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">End Time:</label>
                <input type="time" class="form-control" value="<?= $row['time_end'] ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Status:</label>
                <input type="text" class="form-control" value="<?= $row['status'] ?>" readonly>
            </div>
           
            <div class="text-center mt-4">
                <a href="mailto:<?= htmlspecialchars($row['email_address']) ?>?subject=Booking%20Details&body=" class="btn btn-outline-success">
                    Send notification via Gmail
                </a>
            </div>
            <a href="admin_dashboard.php" class="btn btn-secondary">Back</a>
        </div>
    </div>
</body>
</html>
