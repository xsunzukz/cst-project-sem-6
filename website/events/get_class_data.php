<?php
// Include your database connection or configuration file here
require_once '../connection-files/db_admin_fill.php';

// Check if class_id is set in the POST request
if (isset($_POST['class_id'])) {
    $classId = $_POST['class_id'];

    // Perform a query to select data from the "classes" table based on the ID
    // Adjust the query as per your database schema
    $query = "SELECT * FROM classes WHERE id = :class_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':class_id', $classId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the query was successful
    if ($result) {
        // Return the result as JSON
        header('Content-Type: application/json');
        echo json_encode($result);
        exit; // Exit to prevent any additional output
    } else {
        // Return an error message as JSON
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Class not found']);
        exit; // Exit to prevent any additional output
    }
} else {
    // Return an error message as JSON if class_id is not set
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request']);
    exit; // Exit to prevent any additional output
}
?>
