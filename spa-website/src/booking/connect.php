<?php
// Database credentials
$host = 'localhost';       // MySQL host (usually 'localhost' for local development)
$dbname = 'spa_bookings'; // Replace with your database name (e.g., available_times or bookings)
$username = 'root';         // MySQL username (default is 'root' for WAMP/XAMPP)
$password = '';             // MySQL password (default is '' for WAMP/XAMPP)

// Set the DSN (Data Source Name) for PDO
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

try {
    // Create a new PDO instance
    $pdo = new PDO($dsn, $username, $password);

    // Set PDO error mode to exception (this will throw exceptions if there are any issues)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: You can echo a message when the connection is successful (for debugging)
    // echo "Connected to the database successfully!";
} catch (PDOException $e) {
    // Catch any exceptions/errors and display the error message
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>
