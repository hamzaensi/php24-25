<?php
$conn = new mysqli("localhost", "root", "", "BusBooking");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (isset($_POST['add_bus'])) {
    $bus_name = $_POST['bus_name'];
    $capacity = $_POST['capacity'];
    $conn->query("INSERT INTO buses (bus_name, capacity) VALUES ('$bus_name', $capacity)");
}

if (isset($_POST['edit_bus'])) {
    $id = $_POST['id'];
    $bus_name = $_POST['bus_name'];
    $capacity = $_POST['capacity'];
    $conn->query("UPDATE buses SET bus_name='$bus_name', capacity=$capacity WHERE id=$id");
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM buses WHERE id=$id");
}

$buses = $conn->query("SELECT * FROM buses");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Buses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include 'navbar.php'; ?>
  <div class="container mt-5">
    <h2>Manage Buses</h2>
    <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addModal">Add Bus</button>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Bus Name</th>
          <th>Capacity</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $buses->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['bus_name'] ?></td>
            <td><?= $row['capacity'] ?></td>
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
                    <h5 class="modal-title">Edit Bus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <div class="mb-3">
                      <label>Bus Name</label>
                      <input type="text" name="bus_name" class="form-control" value="<?= $row['bus_name'] ?>" required>
                    </div>
                    <div class="mb-3">
                      <label>Capacity</label>
                      <input type="number" name="capacity" class="form-control" value="<?= $row['capacity'] ?>" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="edit_bus" class="btn btn-success">Save Changes</button>
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
            <h5 class="modal-title">Add Bus</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label>Bus Name</label>
              <input type="text" name="bus_name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Capacity</label>
              <input type="number" name="capacity" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="add_bus" class="btn btn-primary">Add Bus</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
