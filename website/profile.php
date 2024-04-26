<?php
// Destroy the cookies and redirect to login page if the user is not logged in
if (!isset($_COOKIE['user_email']) || !isset($_COOKIE['user_password'])) {
    header("Location: ./login.php");
    exit(); // Make sure to exit after redirection
}

// Logout functionality
if (isset($_POST['logout'])) {
    // Unset cookies
    setcookie('user_email', '', time() - 3600, '/');
    setcookie('user_password', '', time() - 3600, '/');
    
    // Redirect to login page
    header("Location: ./login.php");
    exit(); // Make sure to exit after redirection
}

include './connection-files/db_classes_conn.php';
$mail = $_COOKIE['user_email'];
$sql = "SELECT * FROM student_info WHERE email = '$mail'";
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $dept = $row['department'];
    $sem = $row['semester'];
    $reg_no = $row['registration_number'];
    $id = $row['id'];
    $photo_path = $row['photos'];
} else {
    $name = "Name not found"; // Or handle the case where the name is not found
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="./css/profile.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
</head>
<body>
    <header>
        <div class="nav">
            <h1><a href="./index.php" style="text-decoration: none;">SmartTrack</a></h1>
            <div class="nav-items">
                <ul>
                    <li><a href="./index.php">Home</a></li>
                    <li><a href="./index.php">Classes</a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="main-content">
        <div class="page-name">
            <p>Home /<span> My Profile</span></p>
        </div>
        <div class="greetings">
            <p><i class="fa-solid fa-sun-cloud"></i>   Hi, Greetings for the day!</p>
        </div>
        <div class="cols-warper">
            <div class="column">
                <div class="cols-style" id="view-profile-btn">
                    <p class="usr-name"><i class="fa-solid fa-user"></i><?php echo ' ' . $name?></p>
                </div>
                <div class="cols-style" id="edit-profile-btn">
                    <p class="edit-profile"><i class="fa-solid fa-user-pen"></i> Edit Profile</p>
                </div>
                <div class="cols-style" id="upload-photo-btn">
                    <p class="edit-profile"><i class="fa-solid fa-user-pen"></i>Upload Photo</p>
                </div>
                <div class="cols-style" id="logout">
                    <form action="" method="post">
                        <input type="hidden" name="logout" value="1">
                        <button type="submit" class="logout-btn"><i class="fa-regular fa-arrow-right-from-bracket"></i> Logout</button>
                    </form>
                </div>

            </div>
            <div class="column">
                <div class="cols-main-style" id="view-profile">
                    <h4 class="user-profile">My User Profile</h4>
                    <div class="main-details">
                        <style>
                            .profile-photo {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #ff8800;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease;
}

.profile-photo:hover {
    transform: scale(1.1);
}
                        </style>
                    <?php if (!empty($photo_path)): ?>
                        <img src="<?php echo $photo_path ?>" alt="Profile Photo" class="profile-photo">
                        <?php endif; ?>


                        <p class="details">Name: <span class="details-info"><?php echo $name ?></span></p>
                        <p class="details">Email: <span class="details-info"><?php echo $mail ?></span></p>
                        <p class="details">DEPT: <span class="details-info"><?php echo $dept ?></span></p>
                        <p class="details">Semester: <span class="details-info"><?php echo $sem ?></span></p>
                        <p class="details">Reg. No. : <span class="details-info"><?php echo $reg_no ?></span></p>
                    </div>
                </div>
                <div class="cols-main-style" id="edit-profile" style="display: none;">
                    <h4 class="user-profile">Edit Profile</h4>
                    <form action="./events/update_profile.php" method="POST">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo $name ?>"><br><br>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo $mail ?>" readonly><br><br>
                        <label for="dept">DEPT:</label>
                        <select name="dept" required>
                            <option value="">Select Department</option>
                            <option value="CST" <?php if ($dept == "CST") echo "selected"; ?>>CST</option>
                            <option value="CFS" <?php if ($dept == "CFS") echo "selected"; ?>>CFS</option>
                            <option value="ID" <?php if ($dept == "ID") echo "selected"; ?>>ID</option>
                            <option value="ELECTRICAL" <?php if ($dept == "ELECTRICAL") echo "selected"; ?>>ELECTRICAL</option>
                            <option value="MECHATRONICS" <?php if ($dept == "MECHATRONICS") echo "selected"; ?>>MECHATRONICS</option>
                        </select>
                        <label for="semester">Semester:</label>
                        <input type="text" id="semester" name="semester" value="<?php echo $sem ?>"><br><br>
                        <label for="reg_no">Reg. No.:</label>
                        <input type="text" id="reg_no" name="reg_no" value="<?php echo $reg_no ?>"><br><br>
                        <input type="submit" value="Save Changes">
                    </form>
                </div>
                <div class="cols-main-style" id="update-profile-photo" style="display: none;">
                    <h1>Profile Photo Uploader</h1>
                    <p>Remember You can only update your photo once when it's updated you can't change it!</p>
                    <label for="fileInput" id="custom-file-upload">Choose File</label>
                    <input type="file" id="fileInput" onchange="previewPhoto()" style="display: none;">
                    <div id="previewContainer"></div>
                    <button onclick="uploadPhoto()" id="custom-file-upload">Upload Photo</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        console.log(<?php echo $id; ?>);

        const edit_btn = document.getElementById('edit-profile-btn');
        const view_btn = document.getElementById('view-profile-btn');
        const view_profile = document.getElementById('view-profile');
        const edit_profile = document.getElementById('edit-profile');
        const photo_update_btn = document.getElementById('upload-photo-btn');
        const photo_update = document.getElementById('update-profile-photo');

        // Add the 'btn-active' class to view_btn on document load
        view_btn.classList.add('btn-active');
        const photoPathValue = "<?php echo $photo_path; ?>";

        // If $photo_path has a value, hide the photo upload section
        if (photoPathValue.trim() !== '') {
            photo_update_btn.style.display = 'none';
        }

        // Event listener for the 'Edit Profile' button
        edit_btn.addEventListener('click', function() {
            view_profile.style.display = 'none';
            photo_update.style.display = 'none';
            edit_btn.classList.add('btn-active');
            view_btn.classList.remove('btn-active');
            photo_update_btn.classList.remove('btn-active')
            edit_profile.style.display = 'block'
        });

        // Event listener for the 'View Profile' button
        view_btn.addEventListener('click', function() {
            view_profile.style.display = 'block';
            photo_update.style.display = 'none';
            edit_btn.classList.remove('btn-active');
            photo_update_btn.classList.remove('btn-active');
            view_btn.classList.add('btn-active');
            edit_profile.style.display = 'none'
        });

        photo_update_btn.addEventListener('click', function(){
            view_profile.style.display = 'none';
            edit_profile.style.display = 'none';
            photo_update.style.display = 'block'
            photo_update_btn.classList.add('btn-active')
            edit_btn.classList.remove('btn-active')
            view_btn.classList.remove('btn-active')    
        })

        function previewPhoto() {
            const fileInput = document.getElementById('fileInput');
            const previewContainer = document.getElementById('previewContainer');
            const file = fileInput.files[0];

            if (!file) {
                previewContainer.innerHTML = '<p>No image selected</p>';
                return;
            }

            const reader = new FileReader();
            reader.onload = function() {
                const imgElement = document.createElement('img');
                imgElement.setAttribute('src', reader.result);
                imgElement.setAttribute('alt', 'Preview');
                imgElement.style.maxWidth = '100px';
                previewContainer.innerHTML = '';
                previewContainer.appendChild(imgElement);
            }
            reader.readAsDataURL(file);
        }

        function uploadPhoto() {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];
    const id = <?php echo $id; ?>; // Assuming $id is available in your PHP code
    const userEmail = "<?php echo $mail ?>"; // Get the user's email

    // Check if a file is selected
    if (!file) {
        alert('Please select a file.');
        return;
    }

    // Extract the file extension
    const extension = file.name.split('.').pop(); // Get the original file extension

    // Generate a dynamic filename (e.g., using current timestamp)
    const filename = userEmail + '.' + extension; // Include the user's email and extension in the filename

    const formData = new FormData();
    formData.append('file', file, filename); // Include the dynamically generated filename
    formData.append('id', id); // Append the id to the FormData object
    formData.append('email', userEmail); // Append the user's email to the FormData object

    // Send the file to the server using AJAX
    fetch('./events/upload_profile_photo.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // Display the response message
        window.location.href = './profile.php'; // Adjust the URL as needed
    })
    .catch(error => {
        console.error('Error:', error);
    });
}


    </script>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
