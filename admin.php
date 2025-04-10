<?php
session_start();
include 'db_connect.php';


if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password']; 

    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

       
        if ($password === $admin['password']) {
            $_SESSION['admin_id'] = $admin['admin_id']; 
            $_SESSION['admin_username'] = $admin['username']; 
            $_SESSION['welcome_message'] = "Welcome, Admin!";
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid username or password!'); window.location='admin.php';</script>";
        }
    } else {
        echo "<script>alert('Admin not found!'); window.location='admin.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | Nasipit MG Booking System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="admin.css">
</head>
<body style=" background: linear-gradient(135deg, #055812, #0b9720);">

  <div id="cover">
    
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
      <div class="login-container p-4 border rounded bg-light shadow-lg">
        <i class="fas fa-user-shield admin-icon"></i>
        <h3 class="text-center">Admin Login</h3>

        <div id="alertMessage" class="alert alert-danger d-none text-center"></div>

        <form action="admin.php" method="POST" autocomplete="off" id="loginForm">
          <div class="mb-3">
            <input type="text" name="username" id="username" class="form-control" placeholder="Username" autocomplete="off" required>
          </div>
          <div class="mb-3 position-relative">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
            <button type="button" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none;">👁</button>
          </div>
          <button type="submit" class="btn btn-success w-100">Login</button>
          <hr>
          <div class="alert alert-warning p-1 text-center">
            <strong>Reminder!</strong> Double check your username and password before logging in.
          </div>
        </form>
      </div>
    </div>
  </div>

  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      
      document.querySelectorAll("input").forEach(input => {
        input.setAttribute("autocomplete", "off");
      });

      
      const loginForm = document.getElementById("loginForm");
      const alertMessage = document.getElementById("alertMessage");

      loginForm.addEventListener("submit", function(event) {
        const username = document.getElementById("username").value.trim();
        const password = document.getElementById("password").value.trim();

        if (!username || !password) {
          event.preventDefault();
          alertMessage.classList.remove("d-none");
          alertMessage.innerText = "Please fill in both fields!";
        } else {
          alertMessage.classList.add("d-none");
        }
      });

      
      document.getElementById("togglePassword").addEventListener("click", function () {
        const passField = document.getElementById("password");
        const toggleIcon = this;
        const isHidden = passField.type === "password";
        passField.type = isHidden ? "text" : "password";
        toggleIcon.innerHTML = isHidden ? "🙈" : "👁";
      });
    });
  </script>
</body>
</html>
