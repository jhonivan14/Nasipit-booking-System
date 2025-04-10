<?php
include 'db_connect.php';

$type = $_GET['type'] ?? 'pending'; 


if ($type === 'pending') {
    $sql = "SELECT * FROM booking WHERE status = 'Pending'";
} elseif ($type === 'approved') {
    $sql = "SELECT * FROM booking WHERE status = 'Confirmed'";
} elseif ($type === 'cancelled') {
    $sql = "SELECT * FROM booking WHERE status = 'Cancelled'";
} elseif ($type === 'notifications') {
    echo "<h3>Send Notifications</h3>";
    echo "<p>This section allows you to send SMS notifications.</p>";
    exit(); 
}


$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
}





echo "<table class='table table-responsive table-bordered table-hover' style='box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-radius: 10px;'>";
echo "<thead class='table-info'><tr>
        <th>Booking_Id</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Date of Booking</th>
        <th>Booking_Time</th>
        <th>Submission Date</th>
        <th>Status</th>
        <th style='width: 220px; text-align: center;'>Action</th>
    </tr></thead><tbody>";

  

    while ($row = $result->fetch_assoc()) {
        $formattedDate = date("F j, Y", strtotime($row['booking_date']));
        $bookingTime = date("h:i A", strtotime($row['booking_time']));
        $timeEnd = date("h:i A", strtotime($row['time_end']));
        $bookedDayTime = date("l, F j, Y h:i A", strtotime($row['created_at']));

    
        
        $statusClass = '';
        if ($row['status'] === 'Pending') {
            $statusClass = 'bg-info'; 
            $section = 'pending';
        } elseif ($row['status'] === 'Confirmed') {
            $statusClass = 'bg-success'; 
            $section = 'approved';
        } elseif ($row['status'] === 'Cancelled') {
            $statusClass = 'bg-danger'; 
            $section = 'cancelled';
        }
    
        echo "<tr>
                <td>{$row['booking_id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['email_address']}</td>
                <td>{$row['phone']}</td>
                <td>{$formattedDate}</td>
                <td>{$bookingTime} - {$timeEnd}</td> <!--  Display converted time -->
                <td>{$bookedDayTime}</td> <!-- NEW -->
                <td><span class='badge {$statusClass}'>{$row['status']}</span></td>
                <td>";
    

        
        if ($row['status'] === 'Pending') {
            echo "<button class='btn btn-success btn-sm' onclick=\"updateBooking({$row['booking_id']}, 'Confirmed')\"><i class='fas fa-check'></i>Approve</button>
                  <button class='btn btn-danger btn-sm' onclick=\"updateBooking({$row['booking_id']}, 'Cancelled')\"><i class='fas fa-x'></i>Cancel</button>
                  <button class='btn btn-outline-danger btn-sm' onclick=\"deleteBooking({$row['booking_id']})\"><i class='fas fa-trash'></i></button>";
        } else 
        
        echo " <button class='btn btn-primary btn-sm me-1' onclick=\"editBooking({$row['booking_id']})\">
          <i class='fas fa-edit'></i>Edit
       </button>
         <button class='btn btn-outline-info btn-sm' onclick=\"showBooking({$row['booking_id']})\"><i class='fas fa-eye'></i>View</button>
        
          <button class='btn btn-outline-danger btn-sm' onclick=\"deleteBooking({$row['booking_id']})\"><i class='fas fa-trash'></i></button>";
        echo "</td></tr>";
    }
    

?>
