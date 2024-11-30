<?php
require 'mysql_config.php';

// Function to establish a database connection and create the database if it doesn't exist
function getDBConnection($db_server, $db_username, $db_password, $dbname) {
    $conn = new mysqli($db_server, $db_username, $db_password);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) === TRUE) {
        echo "Database created or already exists.\n";
    } else {
        die("Error creating database: " . $conn->error);
    }

    // Select the database
    $conn->select_db($dbname);
    return $conn;
}

// Function to create the users table
function createUsersTable($conn) {
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
      id INT AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(255) UNIQUE NOT NULL,
      password VARCHAR(255) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Users table created or already exists.\n";
    } else {
        die("Error creating users table: " . $conn->error);
    }
}

// Function to create the leaderboard table
function createLeaderboardTable($conn) {
    $sql = "
    CREATE TABLE leaderboard (
      id INT AUTO_INCREMENT PRIMARY KEY,
      user_id INT NOT NULL,
      score INT NOT NULL,
      gamingtime TIME NOT NULL,
      level ENUM('easy', 'intermediate', 'hard') NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Leaderboard table created or already exists.\n";
    } else {
        die("Error creating leaderboard table: " . $conn->error);
    }
}

// Step 1: Connect to database and create it if needed
$conn = getDBConnection($db_server, $db_username, $db_password, $dbname);

// Step 2: Create the users table
createUsersTable($conn);

// Step 3: Create the leaderboard table
createLeaderboardTable($conn);

$conn->close();
?>
