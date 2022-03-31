<?php
$env = parse_ini_file('.env');
$servername = $env['SERVER_NAME'];
$username = $env['USER_NAME'];
$password = $env['DB_PASSWORD'];
$dbname = $env['DB_NAME'];

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE " . $dbname;
if ($conn->query($sql) === true) {
    print("Database " . $dbname . " criada com sucesso");
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
