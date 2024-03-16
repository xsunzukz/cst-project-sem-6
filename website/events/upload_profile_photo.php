<?php
// Include the database connection
include '../connection-files/db_classes_conn.php';

// File upload handling
if ($_FILES['file'] && isset($_COOKIE['user_email'])) {
    $file = $_FILES['file'];
    $email = $_COOKIE['user_email']; // Retrieve the user's email

    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileError = $file['error'];

    if ($fileError === 0) {
        // Define the upload directory
        $uploadDirectory = '../pfp-photos/';

        // Check if the directory exists, if not, create it
        if (!file_exists($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        // Move the file to the specified directory
        $fileDestination = $uploadDirectory . $fileName;
        move_uploaded_file($fileTmpName, $fileDestination);

        // Save file path to MySQL database
        $filePathInDatabase = mysqli_real_escape_string($conn, $fileDestination); // Sanitize input
        
        // Retrieve the user's ID based on their email
        $sql_select_id = "SELECT id FROM student_info WHERE email = ?";
        $stmt_select_id = $conn->prepare($sql_select_id);
        $stmt_select_id->bind_param("s", $email);
        $stmt_select_id->execute();
        $result_select_id = $stmt_select_id->get_result();

        if ($result_select_id->num_rows > 0) {
            $row = $result_select_id->fetch_assoc();
            $id = $row['id'];

            // Prepare SQL statement to update the photo path
            $sql_update_photo = "UPDATE student_info SET photos = ? WHERE id = ?";
            $stmt_update_photo = $conn->prepare($sql_update_photo);
            $stmt_update_photo->bind_param("si", $filePathInDatabase, $id);

            // Execute the update statement
            if ($stmt_update_photo->execute()) {
                echo "File uploaded successfully.";
            } else {
                echo "Error: " . $sql_update_photo . "<br>" . $conn->error;
            }

            // Close update statement
            $stmt_update_photo->close();
        } else {
            echo "User not found.";
        }

        // Close select statement
        $stmt_select_id->close();
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "ID not provided.";
}

// Close connection
$conn->close();
?>
