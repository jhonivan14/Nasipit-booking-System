
function confirmLogout() {
    Swal.fire({
        title: "Are you sure you want to logout?",
        text: "You will be redirected to the login page.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, logout!"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php';
        }
    });
}


function loadContent(type) {
    if (type === 'home') {
        document.getElementById("dashboardContent").innerHTML = `
          
            <div class="text-center " >
            <img src="img/nasipit logo.png" id="imglogo" alt="Nasipit Gym Logo" class="img-fluid mb-3 w-50" >
            <h2>Welcome to the Admin Dashboard</h2>
            <p class="text-muted">Manage bookings, view statuses, and handle notifications with ease.</p>
        </div>

 
        
        `;
    } else {    
        fetch(`fetch_booking.php?type=${type}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById("dashboardContent").innerHTML = data;
                
            })
            .catch(error => console.error("Error loading content:", error));
    }
    setActiveNav(type);
}


function setActiveNav(type) {
    document.querySelectorAll('.nav-link').forEach(nav => nav.classList.remove('active'));
    document.getElementById(type + "Nav").classList.add('active');
}


function updatePendingCount() {
    fetch('fetch_pending.php')
        .then(response => response.json())
        .then(data => {
            let badge = document.getElementById("pendingBadge");
            if (data.total_pending > 0 ) {
                badge.innerHTML = `<span class="badge bg-danger">${data.total_pending}</span>`;
            } else {
                badge.innerHTML = "";
            }
        })
        .catch(error => console.error("Error fetching pending count:", error));
}


document.addEventListener("DOMContentLoaded", function () {
    loadContent("home");
    setInterval(updatePendingCount, 5000); 
});


function updateBooking(booking_id, status) {
let actionText = status === 'Confirmed' ? 'approve' : 'cancel';

Swal.fire({
    title: `Are you sure you want to ${actionText} this booking?`,
    text: "This action cannot be undone!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: status === 'Confirmed' ? '#28a745' : '#dc3545',
    cancelButtonColor: '#6c757d',
    confirmButtonText: `Yes, ${actionText} it!`
}).then((result) => {
    if (result.isConfirmed) {
        fetch('update_booking.php', {  
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `booking_id=${booking_id}&status=${status}`
        })
        .then(response => response.text())
        .then(data => {
            Swal.fire({
                title: "Success!",
                text: `Booking has been ${status.toLowerCase()}.`,
                icon: "success",
                timer: 2000,
                showConfirmButton: false
            });

            
            setTimeout(() => {
                if (status === 'Confirmed') {
                    loadContent("approved");
                } else {
                    loadContent("cancelled");
                }
            }, 2000); 
        })
        .catch(error => console.error("Error:", error));
    }
});
}

function deleteBooking(booking_id) {
   
Swal.fire({
    title: "Are you sure you want to delete this booking?",
    text: "This action cannot be undone!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dc3545",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Yes, delete it!"
}).then((result) => {
    if (result.isConfirmed) {
        fetch('delete_booking.php', {  
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `booking_id=${booking_id}`
        })
        .then(response => response.text())
        .then(data => {
            Swal.fire({
                title: "Deleted!",
                text: "The booking has been removed.",
                icon: "success",
                timer: 2000,
                showConfirmButton: false
            });

           
            setTimeout(() => {
                if (section === "pending") {
                    loadContent("pending");
                } else if (section === "approved") {
                    loadContent("approved");
                } else if (section === "cancelled") {
                    loadContent("cancelled");
                } else {
                    loadContent("home"); 
                }
            }, 2000);x``
        })
        .catch(error => console.error("Error:", error));
    }
});
}

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

