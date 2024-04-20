<?php if(isset($_COOKIE['user_email']) && isset($_COOKIE['user_password'])) {
    // Redirect to index.php
    header("Location: ./index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartTrack - Register</title>
    <link rel="stylesheet" href="./css/register.css">
</head>
<body>
    <div class="container">
        <form id="Form1" action="index2.php" method="post">
            <h3>REGISTER</h3>
            <div class="input-container">
                <input type="text" placeholder="Email" name="email" required>
                <span class="icon"><ion-icon name="mail-outline"></ion-icon></span>
            </div>
            <div class="input-container">
                <input type="password" placeholder="Password" id="password" name="password" required>
                <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
            </div>
            <div class="input-container">
                <input type="password" placeholder="Confirm Password" name="confirmPassword" required>
                <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
            </div>
            <div class="btn-box">
                <button type="button" id="Next1">Submit</button>
            </div>
        </form>
        <form id="Form2">
            <h3>COLLEGE INFO</h3>
            <div class="input-container">
                <input type="text" placeholder="Name" name="name" required>
                <span class="icon"><ion-icon name="person-circle-outline"></ion-icon></span>
            </div>
            <div class="input-container">
                <select name="semester" required>
                    <option value="">Select Semester</option>
                    <option value="1">1st Semester</option>
                    <option value="2">2nd Semester</option>
                    <option value="3">3rd Semester</option>
                    <option value="4">4th Semester</option>
                    <option value="5">5th Semester</option>
                    <option value="6">6th Semester</option>
                </select>
            </div>
            <div class="input-container">
                <select name="department" required>
                    <option value="">Select Department</option>
                    <option value="CST">CST</option>
                    <option value="CFS">CFS</option>
                    <option value="ID">ID</option>
                    <option value="ELECTRICAL">ELECTRICAL</option>
                    <option value="MECHATRONICS">MECHATRONICS</option>
                </select>
            </div>
            <div class="input-container">
                <input type="text" placeholder="Registration Number" name="registrationNumber" required>
                <span class="icon"><ion-icon name="albums-outline"></ion-icon></span>
            </div>
            <div class="btn-box">
                <button type="button" id="Back1">Back</button>
                <button type="button" id="submitAll">Submit</button>
            </div>
        </form>
        <div class="step-row">
            <div id="progress"></div>
            <div class="step-col"><small>Step 1</small></div>
            <div class="step-col"><small>Step 2</small></div>
        </div>
        </div>
        <p class="login-link">Already have an account? <a href="./login.php">Login</a></p>
    </div>
    </div>

    <script>
    window.onload = function() {
        var Form1 = document.getElementById("Form1");
        var Form2 = document.getElementById("Form2");
        var Next1 = document.getElementById("Next1");
        var Back1 = document.getElementById("Back1");
        var submitAll = document.getElementById("submitAll");
        var progress = document.getElementById("progress");
        var loginLink = document.querySelector(".login-link");

        Next1.onclick = function() {
            if (validateForm1()) {
                Form1.style.left = "-450px";
                Form2.style.left = "40px";
                progress.style.width = "360px";
                loginLink.style.display = "none";
            }
        };

        Back1.onclick = function() {
            Form1.style.left = "40px";
            Form2.style.left = "450px";
            progress.style.width = "178px";
            loginLink.style.display = "block";
        };

        submitAll.onclick = function() {
            if (validateForm1() && validateForm2()) {
                submitFormData();
            }
        };

        function validateForm1() {
    var email = Form1.querySelector('input[type="text"]').value;
    var password = document.getElementById('password').value;
    var confirmPassword = Form1.querySelectorAll('input[type="password"]')[1].value;

    // Regular expression to check for the presence of "@" in the email
    var emailPattern = /\S+@\S+\.\S+/;

    if (email === "" || password === "" || confirmPassword === "") {
        alert("Please fill in all fields in Step 1");
        return false;
    }

    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address");
        return false;
    }

    if (password.length < 8) {
        alert("Password must be at least 8 characters long");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Password and Confirm Password do not match");
        return false;
    }

    return true;
}




        function validateForm2() {
            var name = Form2.querySelector('input[name="name"]').value;
            var semester = Form2.querySelector('select[name="semester"]').value;
            var department = Form2.querySelector('select[name="department"]').value;
            var registrationNumber = Form2.querySelector('input[name="registrationNumber"]').value;

            if (name === "" || semester === "" || department === "" || registrationNumber === "") {
                alert("Please fill in all fields in Step 2");
                return false;
            }

            return true;
        }

        function submitFormData() {
            var email = Form1.querySelector('input[type="text"]').value;
            var password = document.getElementById('password').value; // Get password from input
            var confirmPassword = Form1.querySelectorAll('input[type="password"]')[1].value;
            var name = Form2.querySelector('input[name="name"]').value;
            var semester = Form2.querySelector('select[name="semester"]').value;
            var department = Form2.querySelector('select[name="department"]').value;
            var registrationNumber = Form2.querySelector('input[name="registrationNumber"]').value;

            // Encrypt password using SHA-256 hashing algorithm
            sha256(password).then(function(encryptedPassword) {
                // Constructing the URL string
                var urlParams = `?email=${encodeURIComponent(email)}&password=${encodeURIComponent(encryptedPassword)}&confirmPassword=${encodeURIComponent(confirmPassword)}&name=${encodeURIComponent(name)}&semester=${encodeURIComponent(semester)}&department=${encodeURIComponent(department)}&registrationNumber=${encodeURIComponent(registrationNumber)}`;

                // Redirecting to index2.php with URL parameters
                window.location.href = './events/register_acc.php' + urlParams;
            });
        }

        // Function to perform SHA-256 hashing
        async function sha256(plain) {
            const encoder = new TextEncoder();
            const data = encoder.encode(plain);
            const hash = await crypto.subtle.digest('SHA-256', data);
            return Array.from(new Uint8Array(hash)).map(b => b.toString(16).padStart(2, '0')).join('');
        }
    };
</script>


    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
