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
    CREATE TABLE IF NOT EXISTS leaderboard (
      id INT AUTO_INCREMENT PRIMARY KEY,
      user_id INT NOT NULL,
      game_results BOOLEAN NOT NULL,
      time_played TIME NOT NULL,
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

// Function to insert sample data into the users table
function insertUsersTable($conn) {
    $password = 'password';
    $param_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "
    INSERT INTO users (username, password)
    VALUES ('admin', '$param_password'),
           ('user1', '$param_password'),
           ('user2', '$param_password'),
           ('chester', '$param_password'),
           ('deletenomore', '$param_password')
    ";
    

    if ($conn->query($sql) === TRUE) {
        echo "Sample data inserted into users table.\n";
    } else {
        die("Error inserting data into users table: " . $conn->error);
    }
}

// Function to insert sample data into the leaderboard table
function insertLeaderboardTable($conn) {
    $sql = "
    INSERT INTO leaderboard (user_id, game_results, time_played, level)
    VALUES (1, 1, '00:02:00', 'easy'),
           (2, 1, '00:02:30', 'intermediate'),
           (3, 1, '00:07:45', 'hard'),
           (4, 1, '00:08:00', 'hard'),
           (5, 1, '00:01:30', 'easy'),
           (1, 0, '00:10:00', 'hard'),
           (2, 0, '00:01:20', 'easy'),
           (3, 0, '00:04:38', 'intermediate'),
           (4, 0, '00:04:22', 'intermediate'),
           (5, 0, '00:14:00', 'hard') 
    ";

    if ($conn->query($sql) === TRUE) {
        echo "Sample data inserted into leaderboard table.\n";
    } else {
        die("Error inserting data into leaderboard table: " . $conn->error);
    }
}

// Step 1: Connect to database and create it if needed
$conn = getDBConnection($db_server, $db_username, $db_password, $dbname);

// Step 2: Create the users table
createUsersTable($conn);

// Step 3: Create the leaderboard table
createLeaderboardTable($conn);

// Step 4: Insert sample data into the users table
insertUsersTable($conn);

// Step 5: Insert sample data into the leaderboard table
insertLeaderboardTable($conn);


$conn->close();
?>
