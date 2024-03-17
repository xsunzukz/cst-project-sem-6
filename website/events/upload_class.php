<?php
// Include the database connection file
include '../connection-files/db_classes_conn.php';

// Check if all form fields are set
if(isset($_POST['topic'], $_POST['teacher'], $_POST['description'], $_POST['dept'], $_POST['start_time'], $_POST['end_time'])) {
    // Retrieve data from the form and sanitize it
    $topic = mysqli_real_escape_string($conn, $_POST['topic']);
    $teacher = mysqli_real_escape_string($conn, $_POST['teacher']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $dept = mysqli_real_escape_string($conn, $_POST['dept']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);

    // Prepare the SQL statement using a prepared statement
    $sql = "INSERT INTO classes (topic, teacher, description, DEPT, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "ssssss", $topic, $teacher, $description, $dept, $start_time, $end_time);

    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Get the ID of the last inserted record
        $class_id = mysqli_insert_id($conn);
        
        // Construct the SQL query to create the table
        $createTableSQL = "CREATE TABLE `$dept _class_$class_id` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            dept VARCHAR(50) NOT NULL,
            reg_no VARCHAR(50) NOT NULL,
            name VARCHAR(255) NOT NULL,
            status_attend Varchar(50) not null
        )";


        // Execute the SQL query to create the table
        if (mysqli_query($conn, $createTableSQL)) {
            // Redirect to the confirmation page with class ID as a parameter
            header("Location: ../confirmationPages/confirmation_added.php?id=$class_id");
            exit;
        } else {
            echo "<p>Error creating table: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p>Error: " . mysqli_error($conn) . "</p>";
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
} else {
    echo "<p>All form fields are required.</p>";
}

// Close the database connection
mysqli_close($conn);
?>
