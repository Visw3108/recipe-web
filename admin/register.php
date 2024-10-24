<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Page</title>
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
      background-color: #f5f5f5;
    }

    .form-container {
      width: 400px;
      padding: 40px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      text-align: center;
      transition: all 0.3s ease-in-out;
    }

    .form-container:hover {
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    h2 {
      margin-bottom: 20px;
      font-size: 26px;
      color: #333;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ddd;
      background-color: #f9f9f9;
      color: #333;
      border-radius: 8px;
      outline: none;
      font-size: 16px;
      transition: border-color 0.3s, background-color 0.3s;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
      border-color: #7289da;
      background-color: #fff;
    }

    button {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      background-color: #7289da;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #5a7ae6;
    }

    .links {
      margin-top: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 10px;
    }

    .links a {
      color: #7289da;
      font-size: 14px;
      text-decoration: none;
      transition: color 0.3s ease, transform 0.3s ease;
    }

    .links a:hover {
      color: #5a7ae6;
      text-decoration: underline;
      transform: scale(1.05);
    }
  </style>
</head>

<body>
  <div class="login-container">
    <!-- Form for registration -->
    <form id="register-form" method="POST" class="form-container">
      <h2>Sign Up</h2>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Sign Up</button>
      <div class="links">
        <a href="../admin">Already have an account? Log In</a>
        <a href="../../recipe-web">Back to Home</a>
      </div>
    </form>


    <?php
    include "../config.php";

    // Handling form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $email = $_POST['email'];
      $password = $_POST['password'];

      // Basic validation to ensure inputs are not empty
      if (!empty($email) && !empty($password)) {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO user (emailid, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
          echo "<p style='color: #28a745; 
               background-color: #e6f9ed; 
               padding: 15px; 
               border-radius: 8px; 
               border: 1px solid #28a745; 
               font-size: 16px; 
               text-align: center; 
               max-width: 400px; 
               margin: 20px auto;'>
        Registration successful!
      </p>";
          echo "<p style='font-size: 16px; text-align: center; max-width: 400px; margin: 20px auto;'>
        <a href='../admin' style='color: #7289da; text-decoration: none;'>Go to Login Page</a>
      </p>";
          // header("Location: index");
          // exit();
        } else {
          echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }

        // Close the statement
        $stmt->close();
      } else {
        echo "<p style='color: red;'>Please fill all fields.</p>";
      }
    }

    // Close the database connection
    $conn->close();
    ?>
  </div>
</body>

</html>