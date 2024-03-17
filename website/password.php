<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Hash Generator</title>
</head>
<body>
    <h2>Password Hash Generator</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <input type="submit" value="Generate Hash">
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $password = $_POST["password"];
        $hash = password_hash($password, PASSWORD_DEFAULT);
        echo "<p><strong>Generated Hash:</strong> $hash</p>";
    }
    ?>
</body>
</html>
