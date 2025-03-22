<?php
$conn = new mysqli("localhost","root", "","food-order");
// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>