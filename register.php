<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Body Styling */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f5f5f5;
        }

        /* Form Container */
        .form-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Form Title */
        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        /* Form Inputs */
        label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        /* Submit Button */
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Login Link */
        p {
            margin-top: 20px;
            font-size: 0.9em;
        }

        p a {
            color: #4CAF50;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }

        /* Error Message */
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 10px;
        }

        /* Success Message */
        .success-message {
            color: green;
            font-size: 0.9em;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Register Form</h2>
        <form action="register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Register">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "signupforms";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $user = $_POST['username'];
            $email = $_POST['email'];
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $check_stmt->bind_param("ss", $user, $email);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows > 0) {
                echo "<p class='error-message'>Username or email already taken!</p>";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $user, $hashed_password, $email);

                if ($stmt->execute()) {
                    echo "<p class='success-message'>Registration successful! Redirecting to login...</p>";
                    header("refresh:2;url=login.php");
                    exit();
                } else {
                    echo "<p class='error-message'>Error: " . $stmt->error . "</p>";
                }

                $stmt->close();
            }

            $check_stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>