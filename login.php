<?php
   ob_start();  // Turn on output buffering
   session_start(); // Start a session
   require_once 'mysql_config.php';
  $conn = mysqli_connect($db_server,$db_username,$db_password, $dbname);

     // Define variables and initialize with empty values
     $username = $password = "";
     $username_err = $password_err = $login_err = "";
  
     // Process the form data when submitted
     if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate username
        if (empty(trim($_POST["username"]))) {
           $username_err = "Please enter your username.";
        } else {
           $username = trim($_POST["username"]);
        }
  
        // Validate password
        if (empty(trim($_POST["password"]))) {
           $password_err = "Please enter your password.";
        } else {
           $password = trim($_POST["password"]);
        }
  
        // Check credentials if no errors
        if (empty($username_err) && empty($password_err)) {
           // Prepare a SELECT statement to check the username and password
           $sql = "SELECT id, username, password FROM users WHERE username = ?";
           
           if ($stmt = mysqli_prepare($conn, $sql)) {
              // Bind variables to the prepared statement as parameters
              mysqli_stmt_bind_param($stmt, "s", $param_username);
              $param_username = $username;
  
              // Attempt to execute the prepared statement
              if (mysqli_stmt_execute($stmt)) {
                 // Store the result
                 mysqli_stmt_store_result($stmt);
  
                 // Check if username exists
                 if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                       // Verify the password
                       if (password_verify($password, $hashed_password)) {
                          // Start a new session
                          session_start();
                          
                          // Store user data in session variables
                          $_SESSION["loggedin"] = true;
                          $_SESSION["id"] = $id;
                          $_SESSION["username"] = $username;
                          
                          // Redirect to homepage or another protected page
                          header("location: index.php");
                          exit();
                       } else {
                          $login_err = "Invalid password.";
                       }
                    }
                 } else {
                    $login_err = "No account found with that username.";
                 }
              } else {
                 $login_err = "Error: Could not execute query. Try again.";
              }
           }
           mysqli_stmt_close($stmt); // Close statement
        }
        mysqli_close($conn); // Close database connection
     }
  ?>
  
  <!DOCTYPE html>
  <html lang="en">
     <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="CSS/login.css">
     </head>
     <body>
        <div>
           <!-- Display Login Errors -->
           <?php if (!empty($login_err)): ?>
              <div class="error"><?php echo $login_err; ?></div>
           <?php endif; ?>
           <h2>Login to Your Account</h2>
           <!-- Login Form -->
           <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
              <!-- Username Field -->
              <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                 <label for="username">Username</label>
                 <input type="text" name="username" placeholder="Enter your username" value="<?php echo htmlspecialchars($username); ?>" required>
                 <span><?php echo $username_err; ?></span>
              </div>
  
              <!-- Password Field -->
              <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                 <label for="password">Password</label>
                 <input type="password" name="password" placeholder="Enter your password" required>
                 <span><?php echo $password_err; ?></span>
              </div>
  
              <!-- Submit Button -->
              <div class="form-group">
              <button type="submit">Login</button>
              </div>
           </form>
           <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </div>
     </body>
  </html>