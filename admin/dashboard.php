<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel UI</title>
  <link rel="stylesheet" href="styles.css">
</head>
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    background-color: #1e1e1e;
    color: #fff;
  }

  .container {
    display: flex;
    justify-content: space-between;
    width: 90%;
    margin: 50px auto;
  }

  .admin-panel {
    width: 45%;
    padding: 20px;
    background-color: #2c2c2c;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
  }

  h2 {
    text-align: center;
    margin-bottom: 20px;
  }

  .url-entry,
  .status-select {
    margin-bottom: 20px;
  }

  .url-entry label,
  .status-select label {
    display: block;
    margin-bottom: 8px;
  }

  .url-entry input[type="text"],
  .status-select select {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    outline: none;
    background-color: #444;
    color: #fff;
  }

  .actions {
    display: flex;
    justify-content: space-between;
  }

  .action-btn {
    background-color: #555;
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s;
    font-size: 1rem;
  }

  .action-btn:hover {
    background-color: #777;
  }

  .logout-btn {
    background-color: #ff5555;
  }

  .logout-btn:hover {
    background-color: #ff7777;
  }

  .url-list {
    width: 45%;
    padding: 20px;
    background-color: #2c2c2c;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
  }

  .url-list table {
    width: 100%;
    text-align: left;
    border-collapse: collapse;
    margin-bottom: 20px;
  }

  .url-list th,
  .url-list td {
    padding: 10px;
    border-bottom: 1px solid #444;
  }

  .url-list a {
    color: #ff5555;
    text-decoration: none;
  }

  .url-list a:hover {
    text-decoration: underline;
  }

  /* Modal styling */
  .modal {
    display: none;
    position: fixed;
    z-index: 999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    justify-content: center;
    align-items: center;
  }

  .modal-content {
    background-color: #2c2c2c;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    width: 300px;
    color: #fff;
  }

  .modal-buttons {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
  }

  .modal-btn {
    padding: 10px 20px;
    background-color: #ff5555;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    color: #fff;
  }

  .modal-btn.cancel {
    background-color: #555;
  }

  .modal-btn:hover {
    background-color: #ff7777;
  }
</style>

<body>
  <?php
  // Database connection details
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "recipeweb";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Initialize variables
  $url = $status = "";
  $edit_id = 0;
  $is_editing = false;

  // Handle form submission
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $url = $conn->real_escape_string($_POST['url']);
    $status = $conn->real_escape_string($_POST['status']);

    if (isset($_POST['edit_id']) && $_POST['edit_id'] != 0) {
      // Update record
      $edit_id = $_POST['edit_id'];
      $sql = "UPDATE urllist SET url='$url', status='$status' WHERE id=$edit_id";
      $conn->query($sql);
    } else {
      // Insert new record
      $sql = "INSERT INTO urllist (url, status, createdDt) VALUES ('$url', '$status', now())";
      $conn->query($sql);
    }

    // Redirect to avoid resubmission
    header("Location: dashboard");
    exit();
  }

  // Handle edit
  if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM urllist WHERE id=$edit_id");
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $url = $row['url'];
      $status = $row['status'];
      $is_editing = true;
    }
  }

  // Handle delete
  if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $conn->query("DELETE FROM urllist WHERE id=$delete_id");
    header("Location: dashboard");
    exit();
  }
  ?>

  <div class="container">
    <div class="admin-panel">
      <h2><?php echo $is_editing ? "Edit URL" : "Add URL"; ?></h2>

      <form action="" method="POST">
        <div class="url-entry">
          <label for="url-input">Enter URL:</label>
          <input type="text" id="url-input" name="url" placeholder="https://example.com" value="<?php echo $url; ?>">
        </div>

        <div class="status-select">
          <label for="status-dropdown">Status:</label>
          <select id="status-dropdown" name="status">
            <option value="active" <?php if ($status == 'active') echo 'selected'; ?>>Active</option>
            <option value="inactive" <?php if ($status == 'inactive') echo 'selected'; ?>>Inactive</option>
          </select>
        </div>

        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">

        <div class="actions">
          <button class="action-btn" type="submit"><?php echo $is_editing ? "Update" : "Add"; ?></button>
          <button class="action-btn logout-btn" id="logout-btn" type="button">Logout</button>
        </div>
      </form>
    </div>

    <div class="url-list">
      <h2>URL List</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>URL</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $result = $conn->query("SELECT * FROM urllist ORDER BY createdDt DESC");
          if ($result->num_rows > 0) {
            $serial_number = 1; // Initialize the serial number counter
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
              <td>" . $serial_number++ . "</td> <!-- Serial Number -->
              <td>{$row['url']}</td>
              <td>{$row['status']}</td>
              <td>
                <a href='?edit={$row['id']}'>Edit</a> | 
                <a href='#' onclick='confirmDelete({$row['id']})'>Delete</a>
              </td>
            </tr>";
            }
          } else {
            echo "<tr><td colspan='4' style='text-align: center; padding: 20px;'>No URLs found.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal structure -->
  <div id="deleteModal" class="modal">
    <div class="modal-content">
      <p>Are you sure you want to delete this URL?</p>
      <div class="modal-buttons">
        <button id="confirmDeleteBtn" class="modal-btn">Yes</button>
        <button class="modal-btn cancel" onclick="closeModal()">No</button>
      </div>
    </div>
  </div>

  <script>
    let deleteId = 0;

    function confirmDelete(id) {
      deleteId = id;
      document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('deleteModal').style.display = 'none';
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
      window.location.href = '?delete=' + deleteId;
    });

    // JavaScript for Logout button
    const logoutButton = document.getElementById('logout-btn');
    logoutButton.addEventListener('click', () => {
      window.location.href = 'index'; // Redirect to index.php
    });
  </script>
</body>

</html>