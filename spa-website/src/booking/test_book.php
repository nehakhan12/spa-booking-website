<?php
include 'connect.php';  // Include your database connection

// Simulate customer information (replace with actual test values)
$customerName = 'John Doe';
$phoneNumber = '123-456-7890';
$email = 'john.doe@example.com';
$postalCode = '12345';
$date = '2024-11-25'; // Example booking date
$numberOfVisitors = 2;
$accommodations = 'No';
$treatment = 'facial'; // Example treatment
$serviceBooked = 'anti-aging-facial'; // Example service
$timeBooked = '10:30'; // Example time

// Begin a transaction to ensure data integrity
$pdo->beginTransaction();

try {
    // Step 1: Insert the booking into the bookings table
    $stmt = $pdo->prepare("
        INSERT INTO bookings (full_name, phone, email, postal_code, date, num_visitors, accommodations, treatment, service, time, timestamp)
        VALUES (:full_name, :phone, :email, :postal_code, :date, :num_visitors, :accommodations, :treatment, :service, :time, NOW())
    ");
    $stmt->execute([
        ':full_name' => $customerName,
        ':phone' => $phoneNumber,
        ':email' => $email,
        ':postal_code' => $postalCode,
        ':date' => $date,
        ':num_visitors' => $numberOfVisitors,
        ':accommodations' => $accommodations,
        ':treatment' => $treatment,
        ':service' => $serviceBooked,
        ':time' => $timeBooked
    ]);

    // Step 2: Update the available_times table to mark the selected time as booked
    $stmt = $pdo->prepare("
        UPDATE available_times
        SET is_booked = 1
        WHERE treatment = :treatment AND service = :service AND time = :time AND is_booked = 0
    ");
    $stmt->execute([
        ':treatment' => $treatment,
        ':service' => $serviceBooked,
        ':time' => $timeBooked
    ]);

    // Commit the transaction
    $pdo->commit();

    echo "Booking successful! Your selected time has been reserved.";
} catch (Exception $e) {
    // Rollback the transaction if something goes wrong
    $pdo->rollBack();
    echo "Booking failed: " . $e->getMessage();
}
?>
