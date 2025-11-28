<?php
$host = 'db';          // service name from docker-compose
$db   = 'studentdb';
$user = 'root';
$pass = 'root123';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection failed: " . $conn->connect_error;
    exit;
}
