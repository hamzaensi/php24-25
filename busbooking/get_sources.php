<?php
header("Content-Type: application/json");

// Allow from any origin
header("Access-Control-Allow-Origin: *");

// Allow specific HTTP methods
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow specific headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Exit early for OPTIONS requests (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "BusBooking");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Query to fetch all sources (if sources table exists)
//$query = "SELECT id, name FROM sources";

// Uncomment the following line if sources are stored in routes
$query = "SELECT DISTINCT source AS name FROM routes";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $sources = [];
    while ($row = $result->fetch_assoc()) {
        $sources[] = $row;
    }
    echo json_encode($sources);
} else {
    echo json_encode([]);
}

$conn->close();
?>
