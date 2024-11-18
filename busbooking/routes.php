<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "BusBooking");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Add a new route
if (isset($_POST['add_route'])) {
    $source = $_POST['source'];
    $destination = $_POST['destination'];
    $distance = $_POST['distance'];
    $conn->query("INSERT INTO routes (source, destination, distance) VALUES ('$source', '$destination', $distance)");
}

// Edit a route
if (isset($_POST['edit_route'])) {
    $id = $_POST['id'];
    $source = $_POST['source'];
    $destination = $_POST['destination'];
    $distance = $_POST['distance'];
    $conn->query("UPDATE routes SET source='$source', destination='$destination', distance=$distance WHERE id=$id");
}

// Delete a route
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM routes WHERE id=$id");
}

// Fetch all routes
$routes = $conn->query("SELECT * FROM routes");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Routes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include 'navbar.php'; ?>
  <div class="container mt-5">
    <h2>Manage Routes</h2>
    <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addModal">Add Route</button>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Source</th>
          <th>Destination</th>
          <th>Distance (km)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $routes->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['source'] ?></td>
            <td><?= $row['destination'] ?></td>
            <td><?= $row['distance'] ?></td>
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
                    <h5 class="modal-title">Edit Route</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <div class="mb-3">
                      <label>Source</label>
                      <input type="text" name="source" class="form-control" value="<?= $row['source'] ?>" required>
                    </div>
                    <div class="mb-3">
                      <label>Destination</label>
                      <input type="text" name="destination" class="form-control" value="<?= $row['destination'] ?>" required>
                    </div>
                    <div class="mb-3">
                      <label>Distance (km)</label>
                      <input type="number" name="distance" class="form-control" value="<?= $row['distance'] ?>" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="edit_route" class="btn btn-success">Save Changes</button>
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
            <h5 class="modal-title">Add Route</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label>Source</label>
              <input type="text" name="source" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Destination</label>
              <input type="text" name="destination" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Distance (km)</label>
              <input type="number" name="distance" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="add_route" class="btn btn-primary">Add Route</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
