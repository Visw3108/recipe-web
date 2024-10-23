<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Arial', sans-serif;
    }

    body {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #2c2f33;
      color: #fff;
    }

    .login-container {
      width: 350px;
      padding: 40px;
      background: #23272a;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
      font-size: 24px;
      color: #fff;
    }

    .login-container input[type="text"],
    .login-container input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #444;
      background-color: #2c2f33;
      color: #fff;
      border-radius: 5px;
      outline: none;
      transition: border-color 0.3s;
    }

    .login-container input[type="text"]:focus,
    .login-container input[type="password"]:focus {
      border-color: #7289da;
    }

    .login-container button {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 5px;
      background-color: #7289da;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .login-container button:hover {
      background-color: #5a7ae6;
    }

    .login-container a {
      display: block;
      margin-top: 10px;
      text-decoration: none;
      color: #7289da;
      font-size: 14px;
    }

    .login-container a:hover {
      text-decoration: underline;
    }

    .links {
      margin-top: 15px;
      display: flex;
      justify-content: space-between;
    }

    .links a {
      color: #7289da;
    }
  </style>
</head>

<body>
  <div class="login-container">
    <h2>Login</h2>
    <!-- Login Form -->
    <form id="login-form" method="POST">
      <input type="text" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Log In</button>
      <a href="forget-password">Forgot Password?</a>
      <div class="links">
        <a href="register">Register</a>
        <a href="../../recipe-web">Back to Home</a>
      </div>
    </form>

    <?php
    session_start();
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = ""; // Your MySQL root password
    $dbname = "recipeweb";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Handling form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $email = $_POST['email'];
      $password = $_POST['password'];

      // Prepare statement to avoid SQL injection
      $stmt = $conn->prepare("SELECT * FROM user WHERE emailid = ?");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      // Check if user exists
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the hashed password
        if (password_verify($password, $row['password'])) {
          // Set session variables for logged-in user
          $_SESSION['email'] = $row['email'];

          // Redirect to dashboard
          header("Location: dashboard");
          exit();
        } else {
          echo "<p style='color: red;'>Incorrect password!</p>";
        }
      } else {
        echo "<p style='color: red;'>User does not exist!</p>";
      }

      // Close statement
      $stmt->close();
    }

    // Close connection
    $conn->close();
    ?>
  </div>
</body>

</html>