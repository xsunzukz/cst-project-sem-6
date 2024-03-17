<?php
include '../connection-files/db_classes_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $name = $_POST['name'];
    $semester = $_POST['semester'];
    $department = $_POST['dept'];
    $registrationNumber = $_POST['reg_no'];

    // Prepare SQL statement
    $sql = "UPDATE student_info SET name=?, semester=?, department=?, registration_number=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $semester, $department, $registrationNumber, $email);
    
    try {
        if ($stmt->execute()) {
            // Profile updated successfully
            header("Location: ../confirmationPages/profile_updated.php");
            exit;
        } else {
            // Error handling
            echo "<p style='background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>
                    Error: " . $stmt->error . "
                  </p>";
            echo "<button onclick='goBack()' style='background-color: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;'>Go Back</button>";
        }
    } catch (mysqli_sql_exception $e) {
        // Exception handling
        echo "<p style='background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>
                Error: " . $e->getMessage() . "
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
