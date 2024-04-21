<?php 
if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    $student_id = $_GET['id'];
}
if (!$student_id) {
    header("Location: ./index.php");
    exit; // Stop further execution
}

$mail = isset($_COOKIE['user_email']) ? $_COOKIE['user_email'] : '';
include './connection-files/db_classes_conn.php';
// Using prepared statements to avoid SQL injection
$stmt = $conn->prepare("SELECT * FROM student_info WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

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
    <h1> <a href="./index.php" style="text-decoration: none;">SmartTrack</a></h1>
        <div class="nav-items">
            <ul>
                <li><a href="">Home</a></li>
                <li><a href="#classes">Classes</a></li>
                <li><a href="">Notes</a></li>
                <?php
                if(!isset($_COOKIE['user_email']) || !isset($_COOKIE['user_password'])) {
                    echo '<li><a href="./login.php" id="btn-profile">Login</a></li>';
                } else {
                    echo '<li><a href="./profile.php" id="btn-profile">Profile</a></li>';
                }
                ?>
            </ul>
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
                <p class="classes-btn"><i class="fa-duotone fa-graduation-cap"></i> STUDENT</p>
            </div>
            <div class="col1-items">
                <p class="notes-btn"><i class="fa-solid fa-notes"></i> OTHER INFO</p>
            </div>
        </div>
        <div class="column">
            <div class="col2-items">
                <div class="class-dtls">
                    <h1>STUDENT DETAILS</h1>
                    <p><span>Topic: </span><?php echo $name ?></p>
                    <p><span>Description: </span><?php echo $desc ?></p>
                    <p><span>DEPT: </span><?php echo $dept ?></p>
                    <p><span>Registration Number: </span><?php echo $t_start ?></p>
                    <p><span>Email </span><?php echo $t_end ?></p>
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
    <h1>More Students</h1>
    <?php
    include './connection-files/db_classes_conn.php';
    if(!$dept){
        $sql = "SELECT * FROM student_info";
    } else {
        $sql = "SELECT * FROM student_info WHERE department = '$dept' and id != $student_id";
    }
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<a href="student.php?id=' . $row['id'] . '" class="card">';
            echo '<p><span>'. $row['name'] . '</span> </p>';
            echo '<p><span>Semester:</span> ' . $row['semester'] . '</p>';
            echo '<p><span>DEPT:</span> ' . $row['department'] . '</p>';
            echo '</a>';
        }
    } else {
        echo '<p>No Students found from this dept if more student data exists and not showing up contact admin!</p>';
    }
    mysqli_close($conn);
    ?>
</div>
<script>
    const class_btn = document.querySelector('.classes-btn');
    const notes_btn = document.querySelector('.notes-btn');
    const class_dtls = document.querySelector('.class-dtls');
    const notes = document.querySelector('.notes');
    class_btn.classList.add('btn-active');
    notes.style.display = 'none';
    class_btn.addEventListener('click', function(){
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
    });
</script>
</body>
</html>
