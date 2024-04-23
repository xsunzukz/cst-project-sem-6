<?php
include './connection-files/db_classes_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $classId = $_POST['class_id'];
    $deptName = $_POST['dept_name'];
    $tableName = $deptName . ' _class_' . $classId;

    $checkTableSql = "SHOW TABLES LIKE '$tableName'";
    $tableExists = $conn->query($checkTableSql);

    if ($tableExists->num_rows > 0) {
        $sql = "SELECT * FROM `$tableName`";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Create a temporary CSV file
            $csvFile = 'temp.csv';
            $csvHandler = fopen($csvFile, 'w');

            // Write CSV header
            fputcsv($csvHandler, ['ID', 'Name', 'Attendance Status']);

            // Write CSV data
            while ($row = $result->fetch_assoc()) {
                fputcsv($csvHandler, [$row['id'], $row['email'], $row['status_attend']]);
            }

            // Close CSV file
            fclose($csvHandler);

            // Download the CSV file
            header('Content-Type: text/csv');
            // Construct the filename with the class name
            $filename = 'attendance_data_' . $deptName . ' _class_' . $classId . '.csv';
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            readfile($csvFile);

            // Delete the temporary CSV file
            unlink($csvFile);
        } else {
            echo 'No data found for the specified class';
        }
    } else {
        echo 'The specified class does not exist';
    }

    $conn->close();
} else {
    echo 'Invalid request';
}
?>
