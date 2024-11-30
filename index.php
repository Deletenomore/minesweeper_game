<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>MINESWEEPER GAME</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/home.css">
  </head>
  <body>
    <h1>WELCOME TO OUR MINESWEEPER GAME!</h1>

    <!-- Dynamic Welcome Message -->
    <?php if (isset($_SESSION['username'])): ?>
        <p>WELCOME TO THE MINESWEEPER GAME, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <p>Not a user?</p><a href="register.php">Register</a>
    <?php endif; ?>

    <!-- Navigation Icons -->
    <div class="icon-container">
      <div class="icon">
        <a href="login.php">
          <img src="images/icons/login.gif" alt="login page" class="icons">
        </a>
        <span>Login</span>
      </div>

      <div class="icon">
        <?php if (isset($_SESSION['username'])): ?>
          <a href="game.html">
            <img src="images/icons/game.gif" alt="game page" class="icons">
          </a>
          <span>Game</span>
        <?php else: ?>
          <a href="login.php">
            <img src="images/icons/game.gif" alt="login required" class="icons">
          </a>
          <span>Login to Play The GAME</span>
        <?php endif; ?>
      </div>

      <div class="icon">
        <?php if (isset($_SESSION['username'])): ?>
          <a href="leaderboard.html">
            <img src="images/icons/leaderboard.gif" alt="leaderboard page" class="icons">
          </a>
          <span>Leaderboard</span>
        <?php else: ?>
          <a href="login.php">
            <img src="images/icons/leaderboard.gif" alt="login required" class="icons">
          </a>
          <span>Login to View Leaderboard</span>
        <?php endif; ?>
      </div>

      <div class="icon">
        <a href="help.html">
          <img src="images/icons/help.gif" alt="help page" class="icons">
        </a>
        <span>Help</span>
      </div>

      <div class="icon">
        <a href="contact.html">
          <img src="images/icons/contact.gif" alt="contact page" class="icons">
        </a>
        <span>Contact</span>
      </div>
    </div>
  </body>
</html>
