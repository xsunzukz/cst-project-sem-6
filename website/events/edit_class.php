<?php
// Include the database connection file
include '../connection-files/db_classes_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $edit_id = $_POST['edit_id'];
    $edit_topic = $_POST['edit_topic'];
    $edit_teacher = $_POST['edit_teacher'];
    $edit_description = $_POST['edit_description'];
    $edit_dept = $_POST['edit_dept'];
    $edit_start_time = $_POST['edit_start_time'];
    $edit_end_time = $_POST['edit_end_time'];

    // Update the data in the database
    $sql = "UPDATE classes SET topic='$edit_topic', teacher='$edit_teacher', description='$edit_description', DEPT='$edit_dept', start_time='$edit_start_time', end_time='$edit_end_time' WHERE id=$edit_id";

    if (mysqli_query($conn, $sql)) {
        // Redirect to the confirmation page
        header("Location: ../confirmationPages/confirmation_edited.php");
        exit;
    } else {
        echo "<p>Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
    }
    // Close the database connection
    mysqli_close($conn);
}
?>
