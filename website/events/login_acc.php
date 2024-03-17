<?php
include '../connection-files/db_classes_conn.php'; // Make sure to include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Retrieve form data
    $email = $_GET['email'];
    $password = $_GET['password'];
    
    // Retrieve the stored password for the given email
    $sql = "SELECT * FROM student_info WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];

        // Verify the password
        if ($password === $storedPassword) {
            
            setcookie("user_email", $email, time() + (86400 * 30), "/"); // 30 days expiration
            setcookie("user_password", $password, time() + (86400 * 30), "/"); // 30 days expiration
            header("Location: ../confirmationPages/confirmation_login_success.php");
            echo json_encode(['success' => true]);
            exit;
        } else {
            // Incorrect password
            echo "<p style='background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>
                    Incorrect password. Please try again.
                  </p>";
            echo "<button onclick='goBack()' style='background-color: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;'>Go Back</button>";
        }
    } else {
        // Email not found
        echo "<p style='background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>
                Email not found. Please check your credentials.
              </p>";
        echo "<button onclick='goBack()' style='background-color: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;'>Go Back</button>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

?>

<script>
    function goBack() {
        window.history.back();
    }
</script>
