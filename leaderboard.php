<?php
// Start session for user authentication
session_start();

// Database connection configuration
require_once 'mysql_config.php';

// Function to handle database connection
function connectDatabase() {
    global $db_server, $db_username, $db_password, $dbname;
    
    $conn = new mysqli($db_server, $db_username, $db_password, $dbname);
    
    if ($conn->connect_error) {
        die(json_encode([
            'error' => true, 
            'message' => "Connection failed: " . $conn->connect_error
        ]));
    }
    
    return $conn;
}

// Function to validate and sanitize sorting parameters
function validateSortParams($sortBy, $order) {
    $validSortColumns = ['games_played', 'games_won', 'total_time_played', 'easy_level', 'intermediate_level', 'hard_level'];
    $validOrders = ['ASC', 'DESC'];

    $sortBy = in_array($sortBy, $validSortColumns) ? $sortBy : 'games_won';
    $order = in_array(strtoupper($order), $validOrders) ? strtoupper($order) : 'DESC';

    return [$sortBy, $order];
}

// Main request handling
header('Content-Type: application/json');

// Check for action parameter
if (!isset($_GET['action'])) {
    echo json_encode([
        'error' => true, 
        'message' => 'No action specified'
    ]);
    exit;
}

// Connect to database
$conn = connectDatabase();

// Handle different actions
switch($_GET['action']) {
    case 'get_leaderboard':
        // Validate sort parameters
        list($sortBy, $order) = validateSortParams(
            $_GET['sort'] ?? 'games_won', 
            $_GET['order'] ?? 'DESC'
        );

        // Fetch leaderboard data
        $sql = "SELECT 
                    users.username, 
                    COUNT(leaderboard.id) AS games_played, 
                    SUM(leaderboard.game_results) AS games_won, 
                    SEC_TO_TIME(SUM(TIME_TO_SEC(leaderboard.time_played))) AS total_time_played,
                    SUM(CASE WHEN leaderboard.level = 'easy' THEN 1 ELSE 0 END) AS easy_level, 
                    SUM(CASE WHEN leaderboard.level = 'intermediate' THEN 1 ELSE 0 END) AS intermediate_level, 
                    SUM(CASE WHEN leaderboard.level = 'hard' THEN 1 ELSE 0 END) AS hard_level
                FROM leaderboard
                JOIN users ON leaderboard.user_id = users.id
                GROUP BY users.username
                ORDER BY $sortBy $order
                LIMIT 100";

        $result = $conn->query($sql);

        $leaderboardData = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $leaderboardData[] = $row;
            }
        }

        echo json_encode([
            'error' => false,
            'leaderboard' => $leaderboardData
        ]);
        break;

    case 'get_user_history':
        // Check if user is logged in
        if(!isset($_SESSION['username'])) {
            echo json_encode([
                'error' => true, 
                'message' => 'User not logged in'
            ]);
            exit;
        }

        $username = $conn->real_escape_string($_SESSION['username']);
        
        // Fetch user's game history
        $historySql = "SELECT 
                        leaderboard.level, 
                        IF(leaderboard.game_results = 1, 'Win', 'Loss') AS result, 
                        leaderboard.time_played, 
                        leaderboard.created_at AS game_date
                       FROM leaderboard
                       JOIN users ON leaderboard.user_id = users.id 
                       WHERE users.username = '$username'
                       ORDER BY leaderboard.created_at DESC
                       LIMIT 10";
        
        $historyResult = $conn->query($historySql);
        
        $userGameHistory = [];
        if ($historyResult->num_rows > 0) {
            while($row = $historyResult->fetch_assoc()) {
                $userGameHistory[] = $row;
            }
        }

        echo json_encode([
            'error' => false,
            'game_history' => $userGameHistory
        ]);
        break;

    default:
        echo json_encode([
            'error' => true, 
            'message' => 'Invalid action'
        ]);
}

// Close database connection
$conn->close();
exit;
?>
