<?php
// Create connection
$conn = new mysqli("localhost", "root", "yaya@1984", "repository");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>