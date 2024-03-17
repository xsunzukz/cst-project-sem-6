<?php
// Include the database connection file
include '../connection-files/db_classes_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $delete_by = $_POST['delete_by'];

    if ($delete_by === "name") {
        $name = $_POST['name'];
        $sql_select = "SELECT id FROM classes WHERE teacher='$name'";
    } else {
        $id = $_POST['id'];
        $sql_select = "SELECT * FROM classes WHERE id=$id";
    }

    // Execute the select query to get the class ID
    $result = mysqli_query($conn, $sql_select);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $class_id = $row['id'];
        $dept = $row['DEPT'];

        // Construct the table name
        $table_name = "$dept _class_$class_id";

        // Construct the delete query for the classes table
        if ($delete_by === "name") {
            $sql_delete = "DELETE FROM classes WHERE teacher='$name'";
        } else {
            $sql_delete = "DELETE FROM classes WHERE id=$id";
        }

        // Construct the drop table query for the dynamic table
        $sql_drop_table = "DROP TABLE  `$table_name`";

        // Execute the delete query for the classes table
        if (mysqli_query($conn, $sql_delete)) {
            // Execute the drop table query for the dynamic table
            if (mysqli_query($conn, $sql_drop_table)) {
                // Redirect to the confirmation page
                header("Location: ../confirmationPages/confirmation_delete.php");
                exit;
            } else {
                echo "<p>Error dropping table: " . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p>Error deleting from classes table: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p>No class found.</p>";
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
