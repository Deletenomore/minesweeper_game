# minesweeper_game
# Game Setup Guide

Welcome to the Game! Follow the steps below to set up your environment and start playing.

## Prerequisites

- A working web server with PHP support (e.g., Apache or Nginx).
- MySQL database.
- PHP installed (preferably PHP 7.4 or higher).

## Installation Instructions

### Step 1: Configure the Database Connection

1. Open the `mysql_config.php` file.
2. Locate the following variables and update them with your own database credentials:

    ```php
    $db_server = 'your_database_server';
    $db_username = 'your_database_username';
    $db_password = 'your_database_password';
    $dbname = 'your_database_name';
    ```

    Make sure to replace `your_database_server`, `your_database_username`, `your_database_password`, and `your_database_name` with your actual database details.

### Step 2: Set Up the Database

1. Run the `database_initialization.php` script to initialize your database.
2. This script will create the necessary tables and structure needed for the game to function properly.

### Step 3: Register and Log In

1. Open the game in your web browser.
2. Click on the **Register** button to access the registration page.
3. Register an account by providing your details.
4. After registering, log in using your credentials to start playing the game.

## Troubleshooting

- If you encounter any issues with the database connection, ensure that your database credentials are correct and that your web server has access to the MySQL server.
- Make sure your MySQL database is running and accessible from the web server.


---

Happy gaming!
