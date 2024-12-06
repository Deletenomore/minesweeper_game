<?php
session_start(); // Start a session
require 'mysql_config.php';
$conn = new mysqli($db_server,$db_username,$db_password, $dbname);

if ($conn->connect_error) {
  die(json_encode([
      'error' => 1, 
      'message' => "Connection failed: " . $conn->connect_error
  ]));
}

// Retrieve the raw input data
$data = file_get_contents('php://input');

// Decode the JSON payload
$record = json_decode($data, true);

// Validate the JSON decoding
if (json_last_error() !== JSON_ERROR_NONE) {
  echo json_encode(['err' => 1, 'message' => 'Invalid JSON payload']);
  exit;
}

// Ensure the required fields are present
if (!isset($record['game_results'], $record['time_played'], $record['level'])) {
  echo json_encode(['err' => 1, 'message' => 'Missing required fields']);
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])&& isset($_SESSION['id'])  ) {
    $user_id = $_SESSION['id'];
    $game_results = $record['game_results'];
    $time_played = $record['time_played'];
    $level = $record['level'];


    // Validate and sanitize input
    // Ensure time_played is in 'HH:MM:SS' format
    // if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $time_played)) {
    //   $response = ['err' =>1, 'message' => 'Invalid time_played format. Expected HH:MM:SS.'];
    //   echo json_encode($response);
    //   exit;
    // }

    $stmt = $conn->prepare("INSERT INTO leaderboard (user_id, game_results, time_played, level) VALUES (?,?,?,?)");

      // Check if the preparation was successful
      if ($stmt === false) {
        die(json_encode([
            'err' => 0,
            'message' => 'Failed to prepare SQL statement'
        ]));
    }
    $stmt->bind_param("iiss", $user_id, $game_results, $time_played, $level);

     // Execute the statement
     if ($stmt->execute()) {
      $response = ['err' =>0, 'message' => 'Data uploaded successfully'];
  } else {
      $response = ['err' =>1, 'message' => 'Failed to upload data: ' . $stmt->error];
  }
  $stmt->close(); // Close the statement
}else{
  $response = ['err' => 1, 'message' => 'Invalid request or session not set'];
}

$conn->close(); // Close the connection

echo json_encode($response);

?>