<?php
require_once 'mysql_config.php';
$conn = mysqli_connect($db_server,$db_username,$db_password, $dbname);

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err="";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"]=="POST"){
     // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    }else{
       // Prepare a select statement to check if the username already exists
       $sql = "SELECT id FROM users WHERE username = ?";

       if($stmt = mysqli_prepare($conn, $sql)){
         // Bind variables to the prepared statement as parameters
         mysqli_stmt_bind_param($stmt, "s", $param_username);
         //Set parameters
         $param_username = trim($_POST["username"]);
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            //store result
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) ==1){
                $username_err = "This username is already taken.";
            }else{
                $username =  trim($_POST["username"]);
            }

        }else{
            echo "Oops! Something went wrong. Please try again later.";
        }

       }
       mysqli_stmt_close($stmt);
    }

    // Validate password
    if(empty(trim($_POST['password']))){
        $password_err = "Please eneter a password.";
    }elseif(strlen(trim($_POST['password']))<6){
        $password_err = "Password must have at least 6 characters.";
    }else{
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm your password.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password != $confirm_password) {
            $confirm_password_err = "Passwords do not match.";
        }
    }

    //Check input errors before inserting into the database
    if(empty($username_err)&& empty($password_err)&& empty($confirm_password_err)){
        //// Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?,?)";

        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);// Creates a password hash
            if(mysqli_stmt_execute($stmt)){
                header("location: login.php?success=1");
                exit();
            }else{
                echo "Error! Please try again later!";
            }

        }
        mysqli_stmt_close($stmt);
    }
   // Close connection
   mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="CSS/register.css">
</head>
<body>
    <div>
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username:</label>
                <input type="text" name="username" value="<?php echo $username; ?>">
                <span><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password:</label>
                <input type="password" name="password" value="<?php echo $password; ?>">
                <span><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>">
                <span><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Sign Up">
                <input type="reset" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>