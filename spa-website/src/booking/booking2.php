<?php
session_start();
include 'connect.php'; // Include your database connection file

// Check if the session data from booking1.php exists
if (!isset($_SESSION['full_name'], $_SESSION['email'], $_SESSION['phone'], $_SESSION['postal_code'], $_SESSION['date'], $_SESSION['num_visitors'])) {
    die("Error: Missing personal information from the first booking page. Please restart the booking process.");
}

// Check if form data from booking2.html is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data from booking2.html
    $treatment = $_POST['treatment'] ?? null;
    $service = $_POST['service'] ?? null;
    $time = $_POST['time'] ?? null;

    // Validate the form data
    if (!$treatment || !$service || !$time) {
        die("Error: Missing treatment, service, or time. Please go back and select the appropriate options.");
    }

    // Retrieve session data from booking1.php
    $fullName = $_SESSION['full_name'];
    $email = $_SESSION['email'];
    $phone = $_SESSION['phone'];
    $postalCode = $_SESSION['postal_code'];
    $date = $_SESSION['date'];
    $numVisitors = $_SESSION['num_visitors'];
    $accommodations = $_SESSION['accommodations'] ?? 'None'; // Default if no accommodations were provided

    // Start a transaction to ensure data consistency
    $pdo->beginTransaction();

    try {
        // Insert the booking into the bookings table
        $stmt = $pdo->prepare("INSERT INTO bookings (full_name, phone, email, postal_code, date, treatment, service, time, num_visitors, accommodations)
                               VALUES (:full_name, :phone, :email, :postal_code, :date, :treatment, :service, :time, :num_visitors, :accommodations)");
        $stmt->execute([
            ':full_name' => $fullName,
            ':phone' => $phone,
            ':email' => $email,
            ':postal_code' => $postalCode,
            ':date' => $date,
            ':treatment' => $treatment,
            ':service' => $service,
            ':time' => $time,
            ':num_visitors' => $numVisitors,
            ':accommodations' => $accommodations
        ]);

        // Update the available_times table to mark the selected time as booked
        $stmt = $pdo->prepare("UPDATE available_times
                               SET is_booked = 1
                               WHERE treatment = :treatment AND service = :service AND time = :time AND is_booked = 0");
        $stmt->execute([
            ':treatment' => $treatment,
            ':service' => $service,
            ':time' => $time
        ]);

        // Commit the transaction
        $pdo->commit();

        // Inform the user of success
        echo "Booking successful! Thank you, $fullName, for reserving your spot.";
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $pdo->rollBack();
        echo "Booking failed: " . $e->getMessage();
    }

} else {
    // If the request method isn't POST, show an error message
    echo "Invalid request method.";
}
?>
