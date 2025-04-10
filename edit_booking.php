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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
   
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
        exit;
    } else {
        echo "Error updating booking: " . $stmt->error;
    }

    $stmt->close();
} else if (isset($_GET['id'])) {
   
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
    <title>Edit Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="edit_booking.css">
</head>
<body  style=" background: #055812;" >
    <div class="container py-5">
    <h2 class="mb-4">Edit Booking #<?= htmlspecialchars($booking_id) ?></h2>

    <form method="POST" action="" class="border border-success rounded p-4 bg-light">
        <input type="hidden" name="booking_id" value="<?= htmlspecialchars($booking_id) ?>">

        <div class="mb-3">
            <label class="form-label">Full Name:</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address:</label>
            <input type="email" class="form-control" name="email_address" value="<?= htmlspecialchars($row['email_address']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone:</label>
            <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($row['phone']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Booking Date:</label>
            <input type="date" class="form-control" name="booking_date" value="<?= $row['booking_date'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Start Time:</label>
            <input type="time" class="form-control" name="booking_time" value="<?= $row['booking_time'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">End Time:</label>
            <input type="time" class="form-control" name="time_end" value="<?= $row['time_end'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status:</label>
            <select class="form-select" name="status">
                <option <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option <?= $row['status'] == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                <option <?= $row['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Booking</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
    </div>
</body>
</html>
