<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // If not logged in, show login form
    $loginDisplayStyle = "block";
    $contentDisplayStyle = "none";
} else {
    // If logged in, hide login form and show content
    $loginDisplayStyle = "none";
    $contentDisplayStyle = "block";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/admin.css">
    <title>Login - Admin Panel</title>
</head>
<body>
    <div class="login-warper">
    <div id="login-container" style="display: <?php echo $loginDisplayStyle; ?>;">
    <h2>Login</h2>
    <form id="login-form">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <input type="submit" value="Login">
    </form>
    <div id="error-message" style="display: none; color: red;"></div>
</div>

    </div>

<div id="content" style="display: none;" style="display: <?php echo $contentDisplayStyle; ?>;">
    <div class="button-container">
    <button id="getdata-btn">Get Data</button>
        <button id="create-btn">Create</button>
        <button id="edit-btn">Edit</button>
        <button id="delete-btn">Delete</button>
    </div>
    <div class="form-warper">
<div class="upload-form" style="display: block;">
    <h2>Create Class</h2>
    <form action="./events/upload_class.php" method="post" onsubmit="return validateTime('start_time', 'end_time');">
        <label for="topic">Topic:</label>
        <input type="text" id="topic" name="topic" required>
        
        <label for="teacher">Teacher:</label>
        <input type="text" id="teacher" name="teacher" required>
        
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        
        <label for="dept">Department:</label>
        <select id="dept" name="dept" required >
            <option value="">Select Department</option>
            <option value="CST">CST</option>
            <option value="CFS">CFS</option>
            <option value="ID">ID</option>
            <option value="ELECTRICAL">ELECTRICAL</option>
            <option value="MECHATRONICS">MECHATRONICS</option>
        </select>

        
        <label for="start_time">Start Time:</label>
        <input type="datetime-local" id="start_time" name="start_time" required>
        
        <label for="end_time">End Time:</label>
        <input type="datetime-local" id="end_time" name="end_time" required>
        
        <input type="submit" value="Upload">
    </form>
</div>
<div class="delete-form">
    <h2>Delete Class</h2>
    <form action="./events/delete_class.php" method="post">
        <label for="delete_by">Delete by:</label>
        <select id="delete_by" name="delete_by" required>
            <option value="id">ID</option>
        </select>

        <div id="delete_id_field">
            <label for="id">ID:</label>
            <input type="number" id="id" name="id">
        </div>
        
        <input type="submit" value="Delete">
    </form>
</div>
<div class="edit-form">
    <h2>Edit Class</h2>
    <form action="./events/edit_class.php" method="post" onsubmit="return validateTime('edit_start_time', 'edit_end_time');">
        <label for="edit_id">Class ID:</label>
        <input type="number" id="edit_id" name="edit_id" required>
        
        <label for="edit_topic">Topic:</label>
        <input type="text" id="edit_topic" name="edit_topic" required>
        
        <label for="edit_teacher">Teacher:</label>
        <input type="text" id="edit_teacher" name="edit_teacher" required>
        
        <label for="edit_description">Description:</label>
        <textarea id="edit_description" name="edit_description" required></textarea>
        <select id="edit_dept" name="edit_dept" required>
            <option value="">Select Department</option>
            <option value="CST">CST</option>
            <option value="CFS">CFS</option>
            <option value="ID">ID</option>
            <option value="ELECTRICAL">ELECTRICAL</option>
            <option value="MECHATRONICS">MECHATRONICS</option>
        </select>

        <label for="edit_start_time">Start Time:</label>
        <input type="datetime-local" id="edit_start_time" name="edit_start_time" required>
        
        <label for="edit_end_time">End Time:</label>
        <input type="datetime-local" id="edit_end_time" name="edit_end_time" required>
        <label for="notes">Update Notes</label>
        <input type = "text" id = "notes" name = "notes">
        
        <input type="submit" value="Save Changes">
    </form>
</div>
</div>
<div class="get-data">
<div class="container" id = 'get-data'>
        <h2>Get Data:</h2>
        <form method="post" action="">
            <label for="class_id">Class ID:</label>
            <input type="number" id="class_id" name="class_id" required>
            <label for="dept_name">Department:</label>
            <select id="dept_name" name="dept_name" required>
                <option value="">Select Department</option>
                <option value="CST">CST</option>
                <option value="CFS">CFS</option>
                <option value="ID">ID</option>
                <option value="ELECTRICAL">ELECTRICAL</option>
                <option value="MECHATRONICS">MECHATRONICS</option>
            </select>
            <input type="submit" value="Get Info">
        </form>
    </div>

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
                echo '<table>';
                echo '<tr><th>ID</th><th>Name</th><th>Attendance Status</th></tr>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['id'] . '</td>';
                    echo '<td>' . $row['email'] . '</td>';
                    echo '<td>' . $row['status_attend'] . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<div class="error-box"><p>No data found for the specified class</p></div>';
            }
        } else {
            echo '<div class="error-box"><p>The specified class does not exist</p></div>';
        }

        // Close the database connection
        $conn->close();
    }
    ?>
</div>
<script>
    document.getElementById('edit_dept').style.display = "none";
    if (document.cookie.includes('loggedin=true')) {
        document.getElementById('login-container').style.display = 'none';
        document.getElementById('content').style.display = 'block';
        document.querySelector('.login-warper').style.display = 'none';
        document.querySelector(".upload-form").style.display = "none";
        document.querySelector(".get-data").style.display = "block";
    }

    document.getElementById("login-form").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent the form from submitting
        var formData = new FormData(document.getElementById("login-form"));

        // Send the credentials to the server for authentication
        fetch('./events/admin_login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        // Inside the fetch function where the user logs in successfully
.then(data => {
    if (data.success) {
        // Set cookie and show content
        document.cookie = "loggedin=true; expires=" + new Date(new Date().getTime() + 30 * 24 * 60 * 60 * 1000).toUTCString() + "; path=/";
        document.getElementById('login-container').style.display = 'none';
        document.getElementById('content').style.display = 'block';
        document.querySelector('.login-warper').style.display = 'none';
        document.querySelector(".get-data").style.display = "block";

        // Hide the upload form when logged in
        document.querySelector(".upload-form").style.display = "none";
    } else {
        document.getElementById('error-message').innerText = "Invalid username or password. Please try again.";
        document.getElementById('error-message').style.display = 'block';
    }
})

    });
    // Prevent form submission on Enter key press
    document.getElementById("login-form").addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
        }
    });

    document.getElementById("create-btn").addEventListener("click", function() {
        document.querySelector(".upload-form").style.display = "block";
        document.querySelector(".edit-form").style.display = "none";
        document.querySelector(".delete-form").style.display = "none";
        document.querySelector(".get-data").style.display = "none";
        document.title = "Create Class - Admin Panel";
    });

    document.getElementById("edit-btn").addEventListener("click", function() {
        document.querySelector(".upload-form").style.display = "none";
        document.querySelector(".edit-form").style.display = "block";
        document.querySelector(".delete-form").style.display = "none";
        document.querySelector(".get-data").style.display = "none";
        document.title = "Edit Class - Admin Panel";
    });

    document.getElementById("delete-btn").addEventListener("click", function() {
        document.querySelector(".upload-form").style.display = "none";
        document.querySelector(".edit-form").style.display = "none";
        document.querySelector(".delete-form").style.display = "block";
        document.querySelector(".get-data").style.display = "none";
        document.title = "Delete Class - Admin Panel";
    });
    document.getElementById("getdata-btn").addEventListener("click", function() {
        document.querySelector(".get-data").style.display = "block";
        document.querySelector(".upload-form").style.display = "none";
        document.querySelector(".edit-form").style.display = "none";
        document.querySelector(".delete-form").style.display = "none";
        document.title = "Get Data of Classes - Admin Panel";
    });
    document.getElementById("edit_id").addEventListener("input", handleClassIdChange);

    function handleClassIdChange(event) {
    const classId = event.target.value;

    // Create a new XMLHttpRequest object
    const xhr = new XMLHttpRequest();

    // Configure the AJAX request
    xhr.open('POST', './events/get_class_data.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Define the callback function to handle the AJAX response
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            // Request was successful
            const responseData = JSON.parse(xhr.responseText);
            // Populate the form fields with the retrieved data
            populateEditForm(responseData);
        } else {
            // Request failed
            console.error('Request failed:', xhr.statusText);
        }
    };

    // Send the AJAX request with the class ID as data
    xhr.send('class_id=' + encodeURIComponent(classId));
}

// Function to populate the edit form fields with data
// Function to populate the edit form fields with data or display a message if no data is found
function populateEditForm(data) {
    if (data) {
        document.getElementById('edit_topic').value = data.topic || 'No class data found';
        document.getElementById('edit_teacher').value = data.teacher || 'No class data found';
        document.getElementById('edit_description').value = data.description || 'No class data found';
        document.getElementById('edit_dept').value = data.DEPT || 'No class data found';
        document.getElementById('edit_start_time').value = data.start_time || 'No class data found';
        document.getElementById('edit_end_time').value = data.end_time || 'No class data found';
        document.getElementById('notes').value = data.notes || 'Notes are not found for this class';
        document.getElementById('edit_dept').style.display = "none";

    } else {
        // If no data is found, display a message in the form fields
        document.getElementById('edit_topic').value = 'No class data found';
        document.getElementById('edit_teacher').value = 'No class data found';
        document.getElementById('edit_description').value = 'No class data found';
        document.getElementById('edit_dept').value = 'No class data found';
        document.getElementById('edit_start_time').value = 'No class data found';
        document.getElementById('edit_end_time').value = 'No class data found';
        document.getElementById('notes').value = 'No class data found';
    }
}

function validateTime(startTimeId, endTimeId) {
    // Get the start time and end time values
    const startTime = document.getElementById(startTimeId).value;
    const endTime = document.getElementById(endTimeId).value;

    // Convert the time strings to Date objects for comparison
    const startDate = new Date(startTime);
    const endDate = new Date(endTime);
    const today = new Date(); // Current date and time

    // Check if end time is greater than start time
    if (endDate <= startDate) {
        alert('End time must be greater than start time.');
        return false; // Prevent form submission
    }

    // Check if start time is greater than today's time
    if (startDate <= today) {
        alert('Start time must be greater than the current time.');
        return false; // Prevent form submission
    }

    return true; // Allow form submission if validation passes
}


</script>



</body>
</html>
