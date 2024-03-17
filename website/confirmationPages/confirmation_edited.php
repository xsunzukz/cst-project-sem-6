<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="confirmation_styles.css">
            <title>Confirmation</title>
        </head>
        <body>
        <div class="confirmation-container">
            <div class="confirmation-content">
                <h2>Class Information Edited Successfully</h2>
                <p>You have successfully edited the class information.</p>
                <a href="../admin.php" class="btn">Go Back</a>
            </div>
        </div>
        <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        
        .confirmation-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .confirmation-content {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
        }
        
        .confirmation-content h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        
        .confirmation-content p {
            color: #555;
            font-size: 18px;
            margin-bottom: 20px;
        }
        
        .btn {
            background-color: #ff7300;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background-color: #c45800;
        }
        </style>        
        </body>
        </html>