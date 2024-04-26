<?php
// Include your database connection file
include './connection-files/db_classes_conn.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $classId = $_POST['class_id'];
    $deptName = $_POST['dept_name'];

    // Construct the table name based on the input values
    $tableName = $deptName . ' _class_' . $classId;

    // Query to check if the table exists
    $checkTableSql = "SHOW TABLES LIKE '$tableName'";
    $tableExists = $conn->query($checkTableSql);

    if ($tableExists->num_rows > 0) {
        // Query to fetch data from the existing table
        $sql = "SELECT * FROM `$tableName`"; // Enclose table name in backticks to handle spaces
        $result = $conn->query($sql);

        // Display the fetched data in a table
        if ($result->num_rows > 0) {
            // Create a temporary CSV file for download
            $csvFile = 'temp.csv';
            $csvHandler = fopen($csvFile, 'w');

            // Write CSV header
            fputcsv($csvHandler, ['ID', 'Email', 'Name', 'Registration Number', 'Attendance Status']);

            // Fetch and write CSV data
            while ($row = $result->fetch_assoc()) {
                // Fetch name, email, and registration number from student_info based on email
                $email = $row['email'];
                $nameQuery = "SELECT name, registration_number FROM student_info WHERE email = '$email'";
                $nameResult = $conn->query($nameQuery);

                if ($nameResult->num_rows > 0) {
                    $nameRow = $nameResult->fetch_assoc();
                    $name = $nameRow['name'];
                    $reg_no = $nameRow['registration_number'];
                } else {
                    $name = 'N/A'; // Set a default value if name is not found
                    $reg_no = 'N/A';
                }

                // Write CSV data
                fputcsv($csvHandler, [$row['id'], $email, $name, $reg_no, $row['status_attend']]);
            }

            // Close CSV file
            fclose($csvHandler);

            // Set headers for CSV download
            header('Content-Type: text/csv');
            // Construct the filename with the class name
            $filename = 'attendance_data_' . $deptName . '_class_' . $classId . '.csv';
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            // Send the CSV file to the browser
            readfile($csvFile);

            // Delete the temporary CSV file
            unlink($csvFile);

            // Exit to prevent further execution
            exit();
        } else {
            echo '<div class="error-box"><p>No data found for the specified class</p></div>';
        }
    } else {
        echo '<div class="error-box"><p>The specified class does not exist</p></div>';
    }

    // Close the database connection
    $conn->close();
} else {
    echo 'Invalid request';
}
?>
