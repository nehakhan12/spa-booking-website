<?php
// Allow requests from any origin
header("Access-Control-Allow-Origin: *");
// Allow specific HTTP methods
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// Allow specific headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include 'connect.php'; // Include database connection

if (isset($_GET['treatment']) && isset($_GET['service'])) {
    $treatment = $_GET['treatment'];
    $service = $_GET['service'];

    try {
        $stmt = $pdo->prepare("
            SELECT time
            FROM available_times
            WHERE treatment = :treatment AND service = :service AND is_booked = 0
        ");
        $stmt->execute([':treatment' => $treatment, ':service' => $service]);
        $times = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($times as &$time) {
                    $time['time'] = date('H:i', strtotime($time['time'])); // Change the format here
                }

        // Return available times as JSON
        echo json_encode($times);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database query failed: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request parameters']);
}
?>
