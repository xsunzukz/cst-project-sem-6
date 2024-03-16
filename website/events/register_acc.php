<?php
include '../connection-files/db_classes_conn.php';
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Retrieve form data
    $email = $_GET['email'];
    $password = $_GET['password'];
    $confirmPassword = $_GET['confirmPassword'];
    $name = $_GET['name'];
    $semester = $_GET['semester'];
    $department = $_GET['department'];
    $registrationNumber = $_GET['registrationNumber'];
    
    $sql = "INSERT INTO student_info (email, password, name, semester, department, registration_number) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $email, $password, $name, $semester, $department, $registrationNumber);
    
    try {
        if ($stmt->execute()) {
            // Get the ID of the last inserted record
            header("Location: ../confirmationPages/confirmation_acc_created.php");
            exit;
        } else {
            echo "<p style='background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>
                    Error: " . $stmt->error . "
                  </p>";
            echo "<button onclick='goBack()' style='background-color: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;'>Go Back</button>";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { // 1062 is the error code for duplicate entry
            echo "<div style='background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>
                    <p style='margin: 0; font-size: 16px;'>
                        An account with the email address <strong>$email</strong> already exists.
                    </p>
                  </div>";
            echo "<button onclick='goBack()' style='background-color: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;'>Go Back</button>";
        } else {
            echo "<p style='background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;'>
                    Error: " . $e->getMessage() . "
                  </p>";
            echo "<button onclick='goBack()' style='background-color: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;'>Go Back</button>";
        }
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
