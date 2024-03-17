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
        <button id="create-btn">Create</button>
        <button id="edit-btn">Edit</button>
        <button id="delete-btn">Delete</button>
    </div>
    <div class="form-warper">
<div class="upload-form" style="display: block;">
    <h2>Create Class</h2>
    <form action="./events/upload_class.php" method="post">
        <label for="topic">Topic:</label>
        <input type="text" id="topic" name="topic" required>
        
        <label for="teacher">Teacher:</label>
        <input type="text" id="teacher" name="teacher" required>
        
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        
        <label for="dept">Department:</label>
        <select id="dept" name="dept" required>
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
    <form action="./events/edit_class.php" method="post">
        <label for="edit_id">Class ID:</label>
        <input type="number" id="edit_id" name="edit_id" required>
        
        <label for="edit_topic">Topic:</label>
        <input type="text" id="edit_topic" name="edit_topic" required>
        
        <label for="edit_teacher">Teacher:</label>
        <input type="text" id="edit_teacher" name="edit_teacher" required>
        
        <label for="edit_description">Description:</label>
        <textarea id="edit_description" name="edit_description" required></textarea>
        
        <label for="edit_dept">Department:</label>
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
<<<<<<< HEAD
        <label for="notes">Update Notes</label>
        <input type = "text" id = "notes" name = "notes">
=======
        
>>>>>>> 90f0ad18c0512eb5e44d5d5d0d24f86e6451ed1c
        <input type="submit" value="Save Changes">
    </form>
</div>
</div>

<script>
    // Check if cookie exists and skip login form if it does
    if (document.cookie.includes('loggedin=true')) {
        document.getElementById('login-container').style.display = 'none';
        document.getElementById('content').style.display = 'block';
        document.querySelector('.login-warper').style.display = 'none'; // Add this line to hide the login wrapper
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
        .then(data => {
            if (data.success) {
                // Set cookie and show content
                document.cookie = "loggedin=true; expires=" + new Date(new Date().getTime() + 30 * 24 * 60 * 60 * 1000).toUTCString() + "; path=/";
                document.getElementById('login-container').style.display = 'none';
                document.getElementById('content').style.display = 'block';
                document.querySelector('.login-warper').style.display = 'none'; // Hide the login wrapper
            } else {
                document.getElementById('error-message').innerText = "Invalid username or password. Please try again.";
                document.getElementById('error-message').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
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
        document.title = "Create Class - Admin Panel"; // Change title
    });

    document.getElementById("edit-btn").addEventListener("click", function() {
        document.querySelector(".upload-form").style.display = "none";
        document.querySelector(".edit-form").style.display = "block";
        document.querySelector(".delete-form").style.display = "none";
        document.title = "Edit Class - Admin Panel"; // Change title
    });

    document.getElementById("delete-btn").addEventListener("click", function() {
        document.querySelector(".upload-form").style.display = "none";
        document.querySelector(".edit-form").style.display = "none";
        document.querySelector(".delete-form").style.display = "block";
        document.title = "Delete Class - Admin Panel"; // Change title
    });
</script>



</body>
</html>
