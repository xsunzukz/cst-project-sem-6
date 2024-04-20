<?php 
if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    $student_id = $_GET['id'];
} if(!$student_id){
    header("Location: ./index.php");
}

include './connection-files/db_classes_conn.php';
$sql = "SELECT * FROM student_info where id = $student_id";
$result = mysqli_query($conn, $sql);
$data = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $name = $row['name'];
        $desc = $row['semester'];
        $dept = $row['department'];
        $t_start = $row['registration_number'];
        $t_end = $row['email'];
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/class.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
    <title>Student - Profile</title>
</head>
<body>
<header>
        <div class="nav">
            <h1>SmartTrack</h1>
            <div class="nav-items">
                <ul>
                    <li><a href="">Home</a></li>
                    <li><a href="#classes">Classes</a></li>
                    <li><a href="">Notes</a></li>
                    <?php
                    // Check if the cookie exists
                    if(!isset($_COOKIE['user_email']) || !isset($_COOKIE['user_password'])) {
                        // If the cookie doesn't exist, display the profile button
                        
                        echo '<li><a href="./login.php" id="btn-profile">Login</a></li>';
                    }else{
                        echo '<li><a href="./profile.php" id="btn-profile">Profile</a></li>';
                    }
                    ?>
                </ul>
                </div>
            </div>
        </div>
    </header>
    <div class="page-name">
            <p>Home /<span> Students</span></p>
        </div>
    <div class="greeting">
            <h4><i class="fa-solid fa-sun-cloud"></i> Hello, We hope you are having a good day!</h4>
        </div>
    <div class="main-content">
    <div class="cols-warper">
    <div class="column">
        <div class="col1-items">
            <p class="classes-btn"><i class="fa-duotone fa-graduation-cap" style="--fa-primary-color: #ff8800; --fa-secondary-color: #ff8800;"></i> STUDENT</p>
        </div>
        <div class="col1-items">
            <p class="notes-btn"><i class="fa-solid fa-notes"></i> OTHER INFO</p>
        </div>
    </div>
    <div class="column">
        <div class="col2-items">
            <div class="class-dtls">
            <h1>STUDENT DETAILS</h1>
            <p></p><span>Topic: </span><?php echo $name ?></p>
            <p></p><span>Description: </span><?php echo $desc ?></p>
            <p></p><span>DEPT: </span><?php echo $dept ?></p>
            <p></p><span>Registration Number: </span><?php echo $t_start ?></p>
            <p></p><span>Email </span><?php echo $t_end ?></p>
            </div>
        </div>
        <div class="col2-items">
        <div class="notes">
    <h1>Other Info</h1>
        <p>Opps! You found us, we are still working on this one.</p>
</div>

        </div>
    </div>
</div>
    </div>
    <div class="all-classes">
        <h1>All Classes</h1>
        <?php
include './connection-files/db_classes_conn.php';
    $sql = "SELECT * FROM student_info";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Start the anchor tag here
        echo '<a href="student.php?id=' . $row['id'] . '" class="card">';
        echo '<p><span>'. $row['name'] . '</span> </p>';
        echo '<p><span>Semester:</span> ' . $row['semester'] . '</p>';
        echo '<p><span>DEPT:</span> ' . $row['department'] . '</p>';
        // Close the anchor tag here
        echo '</a>';
    }
} else {
    echo '<p>No Students found!</p>';
}
mysqli_close($conn);
?>
    </div>
<script>
    const class_btn = document.querySelector('.classes-btn');
    const notes_btn = document.querySelector('.notes-btn');
    const class_dtls = document.querySelector('.class-dtls')
    const notes = document.querySelector('.notes')
    class_btn.classList.add('btn-active');
    notes.style.display = 'none';
    class_btn.addEventListener('click',function(){
        class_btn.classList.add('btn-active');
        notes_btn.classList.remove('btn-active');
        class_dtls.style.display = 'block';
        notes.style.display = 'none';
    });
    notes_btn.addEventListener('click', function(){
        class_btn.classList.remove('btn-active');
        notes_btn.classList.add('btn-active');
        class_dtls.style.display = 'none';
        notes.style.display = 'block';
    })
</script>
</body>
</html>

