<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border: 2px solid #ff7300;
            border-radius: 8px;
        }

        h2 {
            color: #ff7300;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #ff7300;
        }

        input[type="number"],
        select,
        input[type="submit"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ff7300;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #ff7300;
            color: white;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ff7300;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #ff7300;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #ffe5cc; /* Light #ff7300 */
        }

        .error-box {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border: 2px solid red;
            border-radius: 8px;
            background-color: #ffe6e6; /* Light red background */
            color: red;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
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
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['class_id']) && isset($_POST['dept_name'])) {
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
    <script>
        function getData() {
            var form = document.getElementById('getDataForm');
            var formData = new FormData(form);

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        document.getElementById('resultContainer').innerHTML = xhr.responseText;
                    } else {
                        document.getElementById('resultContainer').innerHTML = '<div class="error-box"><p>Error fetching data</p></div>';
                    }
                }
            };

            xhr.open('POST', '', true);
            xhr.send(formData);
        }
    </script>

    <div id="resultContainer"></div>
</body>

</html>
