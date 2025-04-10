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

$welcomeMessage = isset($_SESSION['welcome_message']) ? $_SESSION['welcome_message'] : null;
unset($_SESSION['welcome_message']); 


$sql_count = "SELECT COUNT(*) AS pending_count FROM booking WHERE status = 'Pending'";
$result_count = $conn->query($sql_count);
$pending_count = $result_count->num_rows > 0 ? $result_count->fetch_assoc()['pending_count'] : 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
 
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
        
</head>
<body>

    
    <nav class="navbar navbar-expand-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center">
        <img src="img/nasipit logo.png" style="height: 60px; width: auto;" alt="Logo">
        <span id="nasText" class="ms-2">Nasipit MG Booking System</span>
      </div>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" id="homeNav" onclick="loadContent('home')"><i class="fas fa-house"></i> Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="pendingNav" onclick="loadContent('pending')">
              <i class="fas fa-hourglass-half"></i> Pending Bookings
              <span id="pendingBadge">
                <?php if ($pending_count > 0): ?>
                  <span class="badge bg-danger"><?= $pending_count ?></span>
                <?php endif; ?>
              </span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="approvedNav" onclick="loadContent('approved')"><i class="fas fa-check-circle"></i> Approved Bookings</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="cancelledNav" onclick="loadContent('cancelled')"><i class="fas fa-times-circle"></i> Cancelled Bookings</a>
          </li>
         
        </ul>
      </div>

      <button onclick="confirmLogout()" class="btn btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>
  </nav>

  
  
  <div class="container mt-4">
    <div id="dashboardContent" class="table-responsive">
      
    </div>
  </div>

  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php if ($welcomeMessage): ?>
    <script>
      Swal.fire({
        title: "<?= $welcomeMessage ?>",
        text: "You have successfully logged in!",
        icon: "success",
        timer: 2000,
        showConfirmButton: false
      });

       
    </script>
  <?php endif; ?>

  <script>
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }

  window.onpageshow = function(event) {
    
    if (event.persisted) {
      window.location.reload();
    }

    
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
      fetch("check_session.php")
        .then(res => res.text())
        .then(data => {
          if (data.trim() !== "OK") {
            window.location.href = "index.html"; 
          }
        });
    }
  };

  function editBooking(booking_id) {
    Swal.fire({
      title: 'Edit Booking',
      text: "Redirecting to edit page...",
      icon: 'info',
      showConfirmButton: false,
      timer: 1000
    });

    setTimeout(() => {
      window.location.href = `edit_booking.php?id=${booking_id}`;
    }, 1000);
  }

  function showBooking(booking_id) {
    Swal.fire({
      title: 'View Booking',
      text: "Redirecting to view page...",
      icon: 'info',
      showConfirmButton: false,
      timer: 1000
    });

    setTimeout(() => {
      window.location.href = `view_bookings.php?id=${booking_id}`;
    }, 1000);
  }


</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="main.js"></script>
</body>
</html>
