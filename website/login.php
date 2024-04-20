<?php
// Check if user is already logged in
if(isset($_COOKIE['user_email']) && isset($_COOKIE['user_password'])) {
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
    <title>SmartTrack - Login</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <div class="container">
        <form id="LoginForm">

            <h3>LOGIN</h3>
            <div class="input-container">
                <input type="text" name="email" placeholder="Email" required>
                <span class="icon"><ion-icon name="mail-outline"></ion-icon></span>
            </div>
            <div class="input-container">
                <input type="password" name="password" placeholder="Password" id="password" required>
                <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
            </div>
            
            <div class="btn-box">
    <button id="submitBtn">Submit</button>
</div>

<p class="register-link">Don't have an account? <a href="./register.php">Register</a></p>
</form>
</div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script>
        document.getElementById('submitBtn').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission
            var email = document.querySelector('input[name="email"]').value;
            var password = document.getElementById('password').value;

            // Encrypt password using SHA-256 hashing algorithm
            sha256(password).then(function(encryptedPassword) {
                // Constructing the URL string
                var urlParams = `?email=${encodeURIComponent(email)}&password=${encodeURIComponent(encryptedPassword)}`;

                // Redirecting to index2.php with URL parameters
                window.location.href = './events/login_acc.php' + urlParams;
            });
        });

        // Function to perform SHA-256 hashing
        async function sha256(plain) {
            const encoder = new TextEncoder();
            const data = encoder.encode(plain);
            const hash = await crypto.subtle.digest('SHA-256', data);
            return Array.from(new Uint8Array(hash)).map(b => b.toString(16).padStart(2, '0')).join('');
        }
    </script>
</body>
</html>
