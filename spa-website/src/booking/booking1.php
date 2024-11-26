<?php
session_start();

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Only process the form if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate form data
    $full_name = filter_var($_POST['full_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $postal_code = filter_var($_POST['postal_code'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $date = $_POST['date']; // Make sure the date is in correct format
    $num_visitors = filter_var($_POST['num_visitors'], FILTER_SANITIZE_NUMBER_INT);

    // Validate if required data is available
    if (empty($full_name) || empty($email) || empty($phone) || empty($postal_code) || empty($date) || empty($num_visitors)) {
        die("Error: Missing required fields. Please go back and complete all fields.");
    }

    // Store form data in session
    $_SESSION['full_name'] = $full_name;
    $_SESSION['email'] = $email;
    $_SESSION['phone'] = $phone;
    $_SESSION['postal_code'] = $postal_code;
    $_SESSION['date'] = $date;
    $_SESSION['num_visitors'] = $num_visitors;

    // Optional: Store accommodations data if exists
    if (isset($_POST['accommodation']) && !empty($_POST['accommodation'])) {
        $_SESSION['accommodations'] = filter_var($_POST['accommodation'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    // Redirect to booking2.html
    header("Location: booking2.html");
    exit; // Make sure to exit after the redirect
} else {
    // For debugging: This should not be accessed via GET.
    die("Invalid request method. Please submit the form first.");
}
?>