<?php
include '../config.php';
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load the PHPMailer classes from the src folder
require_once '../phpmailer/src/Exception.php';
require_once '../phpmailer/src/PHPMailer.php';
require_once '../phpmailer/src/SMTP.php';

// Set timezone to Asia/Kolkata
date_default_timezone_set('Asia/Kolkata');

if (isset($_POST['submit'])) {
  $email = $_POST['email'];

  // Connect to the database and check if email exists
  $query = "SELECT * FROM user WHERE emailid = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Email exists, generate reset token
    $token = bin2hex(random_bytes(50)); // Generate a token
    $token_expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

    // Store token and expiry in the database
    $update_query = "UPDATE user SET password_reset_token = ?, token_expiry = ? WHERE emailid = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sss", $token, $token_expiry, $email);
    $stmt->execute();

    // Send password reset email using PHPMailer
    $reset_link = "http://localhost/recipe-web/admin/reset-password.php?token=" . $token;

    $mail = new PHPMailer(true);
    try {
      //Server settings
      $mail->isSMTP();                                            // Send using SMTP
      $mail->Host       = 'smtp.gmail.com';       // Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                 // Enable SMTP authentication
      $mail->Username   = 'rajat.web71@gmail.com';             // SMTP username
      $mail->Password   = 'ctwh vyny rrdh nwcu';                // SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      // Enable TLS encryption
      $mail->Port       = 587;                                  // TCP port to connect to

      //Recipients
      $mail->setFrom('no-reply@gmail.com', 'Tecknify');
      $mail->addAddress($email);                                // Add a recipient

      // Content
      $mail->isHTML(true);                                      // Set email format to HTML
      $mail->Subject = 'Password Reset Request';
      $mail->Body    = "Click on the link to reset your password: <a href='$reset_link'>$reset_link</a>";
      $mail->AltBody = "Click on the link to reset your password: $reset_link"; // Plain text for non-HTML clients

      $mail->send();
      echo "A password reset link has been sent to your email.";
    } catch (Exception $e) {
      echo "Failed to send email. Mailer Error: {$mail->ErrorInfo}";
    }
  } else {
    echo "Email not found.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Reset</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .container {
      background-color: white;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #333;
    }

    input[type="email"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #5cb85c;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #4cae4c;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Password Reset</h2>
    <form method="post">
      <input type="email" name="email" placeholder="Enter registered email" required>
      <button type="submit" name="submit">Send Reset Link</button>
    </form>
    
    <!-- Go Back Button -->
    <button onclick="window.history.back();" style="margin-top: 10px; padding: 10px; width: 100%; background-color: #d9534f; color: white; border: none; border-radius: 5px; cursor: pointer;">
      Go Back
    </button>
  </div>
</body>

</html>