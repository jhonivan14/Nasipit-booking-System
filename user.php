<?php
session_start();
include 'db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  http_response_code(403); 
  echo "403 Forbidden - Direct access is not allowed.";
  exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $booking_date = $_POST['date'];
    $booking_time = $_POST['booking_time'];
    $time_end = $_POST['time_end'];
    $request = !empty($_POST['request']) ? $_POST['request'] : NULL; 

    
    $sql_check_user = "SELECT * FROM booking WHERE booking_id = ?";
    $stmt_check_user = $conn->prepare($sql_check_user);
    $stmt_check_user->bind_param("s", $email);
    $stmt_check_user->execute();
    $result = $stmt_check_user->get_result();

    if ($result->num_rows > 0) {
        
        header("Location: user.php"); 
        exit();
    }

    
    $sql_insert_booking = "INSERT INTO booking (booking_id, name, email_address, phone, booking_date, booking_time, time_end, request, status) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";
    $stmt_insert_booking = $conn->prepare($sql_insert_booking);
    $stmt_insert_booking->bind_param("isssssss", $booking_id, $name, $email, $phone, $booking_date, $booking_time, $time_end, $request);

    if ($stmt_insert_booking->execute()) {
        
        header("Location: user.php");
        exit();
    } else {
        
        header("Location: " . $_SERVER['HTTP_REFERER']); 
        exit();
    }

   
    $stmt_check_user->close();
    $stmt_insert_booking->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Form</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="user.css">

 
</head>
<body>
    
<div id="cover">
    <div class="text-center mb-4">
      <a href="https://calendar.google.com/calendar/u/0?cid=..." class="calendar-btn">View Calendar of Activities</a>
    </div>
      
    <div id="bookingform" class="container">
    <div class="alert alert-warning text-center mt-3 p-2" id="reminder" role="alert">
  <strong>⏰ Please note:</strong> You must book at least <strong>3 days before</strong> your intended date.
</div>
      <div class="card-header bg-success text-white text-center rounded-3">
        <h2>Book Your Reservation</h2>
      </div>
      <div class="card-body">
        <form id="bookingForm" action="user.php" method="POST">
          <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" autocomplete="off" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control custom-input" id="email" name="email" autocomplete="off" required>
          </div>

          <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="tel" class="form-control custom-input" id="phone" name="phone" autocomplete="off" required>
          </div>

          <div class="row">
            <div class="col-12 col-md-6 mb-3">
              <label for="date" class="form-label">Select Date</label>
              <input type="date" class="form-control custom-input" id="date" name="date" required>
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label for="booking_time" class="form-label">Select Time-Start</label>
              <input type="time" class="form-control custom-input" id="booking_time" name="booking_time" required>
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label for="time_end" class="form-label">Select Time-End</label>
              <input type="time" class="form-control" id="time_end" name="time_end" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="requests" class="form-label">Additional Requests (Optional)</label>
            <textarea class="form-control" id="request" name="request" rows="3" placeholder="Any special requests?"></textarea>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-success w-100 w-md-50">Submit Booking</button>
          </div>
        </form>
      </div>
    </div>
  </div>
   
    <script>
         document.getElementById("viewCalendar").addEventListener("click", function(event) {
    
    document.querySelectorAll("#bookingForm input[required]").forEach(input => {
        input.removeAttribute("required");
    });
});
</script>

<script>
        document.getElementById("bookingForm").addEventListener("submit", function(event) {
            event.preventDefault(); 

            let name = document.getElementById("name").value.trim();
            let email = document.getElementById("email").value.trim();
            let phone = document.getElementById("phone").value.trim();
            let date = document.getElementById("date").value;
            let time = document.getElementById("booking_time").value;
            
              

            
            if (!name || !email || !phone || !date || !time) {
                Swal.fire({
                    title: "Error!",
                    text: "Please fill in all required fields.",
                    icon: "error",
                    confirmButtonColor: "#d33"
                });
                return;
            }

            
            Swal.fire({
                title: "Confirm Your Booking",
                text: `Are you sure you want to book on ${date} at ${time}?`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, Book Now",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#6c757d"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Success!",
                        text: "Your booking has been submitted.",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    });

                    
                    setTimeout(() => document.getElementById("bookingForm").submit(), 2000);
                }
            });
        });
    </script>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

