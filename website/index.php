<?php if(isset($_COOKIE['user_email']) && isset($_COOKIE['user_password'])){
    include './connection-files/db_classes_conn.php';
    $mail = $_COOKIE['user_email'];
    $sql = "SELECT * FROM student_info WHERE email = '$mail'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $dept = $row['department'];
    } else {
        $name = "Name not found"; // Or handle the case where the name is not found
    }
    mysqli_close($conn);
}else{
    $dept = [];
}

?>

                        
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <title>Website Title</title>
</head>
<body>
    <!--
        Before you Start Read This carefully!
        Please mark all the divs and eliments using comment tags.
        Properly Name things!
        Only add one elements in one post(example: you made the form we need the form alone should be in the html don't add the navs and other things me/!Just a helper will
        put all this things together.)
        Don't edit the code bellow!
        Remember the shortcut to add comment tags quickly is crtl + /
    -->
    <header>
        <div class="nav">
            <h1>SmartTrack</h1>
            <div class="nav-items">
                <ul>
                    <li><a href="">Home</a></li>
                    <li><a href="#classes">Classes</a></li>
                    <li><a href="#students">Students</a></li>
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
    <div class="slider">
        <div class="slider-warper">
            <h1 class="notes">Welcome to SmartTrack</h1>
        <p class="notes-text">Embark on an educational revolution with our AI-driven platform. Seamlessly access academic data, automate attendance, and receive personalized learning recommendations. Empower students with tailored support, fostering engagement and success. Join us in shaping the future of education, where innovation meets excellence.</p>
        <br>
        <a href="#classes">Explore More</a>
        </div>
    </div>
    <div class="classes" id="classes">
        <h1 class="classes-head">
            Classes
        </h1>
        <div class="classes-container">
        <?php
include './connection-files/db_classes_conn.php';
$currentDateTime = date('Y-m-d H:i:s');
if(!$dept){
    $sql = "SELECT * FROM classes WHERE end_time > '$currentDateTime'";
}else{
    $sql = "SELECT * FROM classes WHERE end_time > '$currentDateTime' and DEPT = '$dept'";
}
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Start the anchor tag here
        echo '<a href="class.php?id=' . $row['id'] . '" class="card">';
        echo '<p><span>'. $row['topic'] . '</span> </p>';
        echo '<p><span>DEPT:</span> ' . $row['DEPT'] . '</p>';
        // Close the anchor tag here
        echo '</a>';
    }
} else {
    echo '<p>No upcoming classes found</p>';
}
mysqli_close($conn);
?>

        </div>
    <div class="students-container">
        <h1 class="students-head" id="students">Students!</h1>
        <div class="student-details">
        <?php
include './connection-files/db_classes_conn.php';
if(!$dept){
    $sql = "SELECT * FROM student_info";
}else{
    $sql = "SELECT * FROM student_info WHERE department = '$dept' and email != '$mail'";
}
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
    </div>
    </body>
<script>
     document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
</script>
<script src="js/index.js"></script>
</html>