<?php
session_start();
session_destroy();
header("Location: ../public/html/login.html"); // Redirect to login page
exit();
?>