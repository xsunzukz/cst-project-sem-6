<?php 
if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    $class_id = $_GET['id'];
} if(!$class_id){
    header("Location: ./index.php");
}

include './connection-files/db_classes_conn.php';
$sql = "SELECT * FROM classes where id = $class_id";
$result = mysqli_query($conn, $sql);
$data = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $name = $row['topic'];
        $desc = $row['description'];
        $dept = $row['DEPT'];
        $teach = $row['teacher'];
        $t_start = $row['start_time'];
        $t_end = $row['end_time'];
        $notes = $row['notes'];
    }
}
if (isset($_COOKIE['user_email'])) {
    $mail = $_COOKIE['user_email'];
    $tablename = $dept . ' '. '_class_' . $class_id; // corrected table name format
    $sql = "SELECT * FROM `$tablename` WHERE email = '$mail'";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $status = $row['status_attend'];
    } else {
        $status = "Unknown";
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/class.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
    <title>SmartTrack - Class</title>
</head>
<body>
<header>
        <div class="nav">
            <h1> <a href="./index.php" style="text-decoration: none;">SmartTrack</a></h1>
            <div class="nav-items">
                <ul>
                    <li><a href="./index.php">Home</a></li>
                    <li><a href="#classes">Classes</a></li>
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
            <p>Home /<span> Classes</span></p>
        </div>
    <div class="greeting">
            <h4><i class="fa-solid fa-sun-cloud"></i> Hello, We hope you are having a good day!</h4>
        </div>
    <div class="main-content">
    <div class="cols-warper">
    <div class="column">
        <div class="col1-items">
            <p class="classes-btn"><i class="fa-solid fa-screen-users"></i></i> CLASS</p>
        </div>
        <div class="col1-items">
            <p class="notes-btn"><i class="fa-solid fa-notes"></i> NOTES</p>
        </div>
    </div>
    <div class="column">
        <div class="col2-items">
        <div class="class-dtls" id="classes">
    <h1>CLASS DETAILS</h1>
    <p></p><span>Topic: </span><?php echo $name ?></p>
    <p></p><span>Description: </span><?php echo $desc ?></p>
    <p></p><span>DEPT: </span><?php echo $dept ?></p>
    <p></p><span>Teacher: </span><?php echo $teach ?></p>
    <p></p><span>Starting Form: </span><?php echo $t_start ?></p>
    <p></p><span>End Time: </span><?php echo $t_end ?></p>
    <p></p><span>Class ID: </span><?php echo $class_id ?></p>
    <?php if(isset($_COOKIE['user_email'])) { ?>
        <p></p><span>Attendence Status: </span><?php echo $status ?></p>
    <?php } ?>
</div>

        </div>
        <div class="col2-items">
        <div class="notes">
    <h1>Notes</h1>
    <?php if ($notes !== null) { ?>
        <a href="<?php echo $notes ?>" target="_blank">Download</a>
    <?php } else { ?>
        <p>No notes found for this class.</p>
    <?php } ?>
</div>

        </div>
    </div>
</div>
    </div>
    <div class="all-classes">
        <h1>All Classes</h1>
        <?php
include './connection-files/db_classes_conn.php';

$sql = "SELECT * FROM classes WHERE  DEPT = '$dept' And id != $class_id ";

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
    echo '<p>No classes found!</p>';
}
mysqli_close($conn);
?>
    </div>
<script>
    const class_btn = document.querySelector('.classes-btn');
    const notes_btn = document.querySelector('.notes-btn');
    const class_dtls = document.querySelector('.class-dtls')
    const notes = document.querySelector('.notes')
    console.log('<?php echo $notes?>');
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
</body>
</html>

