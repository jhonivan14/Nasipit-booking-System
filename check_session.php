<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    echo "OK";
} else {
    echo "EXPIRED";
}
?>