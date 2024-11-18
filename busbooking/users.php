<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "BusBooking");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Insert User
if (isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $conn->query("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");
}

// Update User
if (isset($_POST['edit_user'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $conn->query("UPDATE users SET name='$name', email='$email' WHERE id=$id");
}

// Delete User
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
}

$users = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include 'navbar.php'; ?>
  <div class="container mt-5">
    <h2>Manage Users</h2>
    <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addModal">Add User</button>
    
    <!-- Users Table -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $users->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td>
              <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
              <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>
            </td>
          </tr>

          <!-- Edit Modal -->
          <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
              <form method="POST" action="">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <div class="mb-3">
                      <label>Name</label>
                      <input type="text" name="name" class="form-control" value="<?= $row['name'] ?>" required>
                    </div>
                    <div class="mb-3">
                      <label>Email</label>
                      <input type="email" name="email" class="form-control" value="<?= $row['email'] ?>" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="edit_user" class="btn btn-success">Save Changes</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Add Modal -->
  <div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
      <form method="POST" action="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label>Name</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
