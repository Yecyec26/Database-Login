<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        /* Signup Link */
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login Form</h2>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
        <p>Don't have an account? <a href="register.php">Sign up</a>.</p>

        <?php
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $servername = "localhost";
            $db_username = "root";
            $db_password = "";
            $dbname = "signupforms";

            $conn = new mysqli($servername, $db_username, $db_password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($hashed_password);

            if ($stmt->fetch()) {
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['username'] = $username;
                    header("Location: dashboard.html");
                    exit();
                } else {
                    echo "<p class='error-message'>Invalid username or password.</p>";
                }
            } else {
                echo "<p class='error-message'>Invalid username or password.</p>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
