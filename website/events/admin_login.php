
<?php
require_once "../connection-files/db_classes_conn.php";

// Check if username and password were provided
if(isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the SQL query to check if the user exists
    $stmt = $conn->prepare("SELECT * FROM adminx WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the user exists, verify the password
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Authentication successful
            $_SESSION['authenticated'] = true;
            $_SESSION['username'] = $username;
            echo json_encode(['success' => true]);
            exit;
        }
    }
}

// If authentication fails or if username/password weren't provided
echo json_encode(['success' => false]);

// Close the database connection
$conn->close();
?>

