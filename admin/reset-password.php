<?php
include '../config.php';
if (isset($_GET['token'])) {
  $token = $_GET['token'];

  // Check if the token exists and is valid
  $query = "SELECT * FROM user WHERE password_reset_token = ? AND token_expiry > NOW()";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $token);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    if (isset($_POST['reset_password'])) {
      $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

      // Update password and clear token
      $update_query = "UPDATE user SET password = ?, password_reset_token = NULL, token_expiry = NULL WHERE password_reset_token = ?";
      $stmt = $conn->prepare($update_query);
      $stmt->bind_param("ss", $new_password, $token);
      $stmt->execute();
      // Redirect to login page after successful reset
      header("Location: index");
      exit();

      echo "<div class='success'>Password has been reset successfully!</div>";
    }
  } else {
    echo "<div class='error'>Invalid or expired token.</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .container {
      background-color: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }

    .form-group {
      position: relative;
      margin-bottom: 20px;
    }

    input[type="password"] {
      width: 100%;
      padding: 12px 40px 12px 15px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    .form-group i {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #5cb85c;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #4cae4c;
    }

    .success {
      color: #28a745;
      text-align: center;
      margin-bottom: 20px;
    }

    .error {
      color: #dc3545;
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Reset Password</h2>
    <form method="post">
      <div class="form-group">
        <input type="password" name="new_password" id="new_password" placeholder="Enter new password" required>
        <i class="toggle-password" onclick="togglePassword()">👁️</i>
      </div>
      <button type="submit" name="reset_password">Reset Password</button>
    </form>
  </div>

  <script>
    function togglePassword() {
      const passwordField = document.getElementById("new_password");
      const icon = document.querySelector(".toggle-password");
      if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.textContent = "🙈"; // Change icon to hide
      } else {
        passwordField.type = "password";
        icon.textContent = "👁️"; // Change icon to show
      }
    }
  </script>
</body>

</html>