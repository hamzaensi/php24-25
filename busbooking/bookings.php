<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "BusBooking");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Add a new booking
if (isset($_POST['add_booking'])) {
    $user_id = $_POST['user_id'];
    $bus_id = $_POST['bus_id'];
    $route_id = $_POST['route_id'];
    $date = $_POST['date'];
    $seats = $_POST['seats'];
    $conn->query("INSERT INTO bookings (user_id, bus_id, route_id, date, seats) VALUES ($user_id, $bus_id, $route_id, '$date', $seats)");
}

// Edit a booking
if (isset($_POST['edit_booking'])) {
    $id = $_POST['id'];
    $user_id = $_POST['user_id'];
    $bus_id = $_POST['bus_id'];
    $route_id = $_POST['route_id'];
    $date = $_POST['date'];
    $seats = $_POST['seats'];
    $conn->query("UPDATE bookings SET user_id=$user_id, bus_id=$bus_id, route_id=$route_id, date='$date', seats=$seats WHERE id=$id");
}

// Delete a booking
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM bookings WHERE id=$id");
}

// Fetch all bookings
$bookings = $conn->query("SELECT bookings.*, users.name AS user_name, buses.bus_name, routes.source, routes.destination 
                          FROM bookings 
                          JOIN users ON bookings.user_id = users.id 
                          JOIN buses ON bookings.bus_id = buses.id 
                          JOIN routes ON bookings.route_id = routes.id");

// Fetch related data
$users = $conn->query("SELECT * FROM users");
$buses = $conn->query("SELECT * FROM buses");
$routes = $conn->query("SELECT * FROM routes");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Bookings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include 'navbar.php'; ?>
  <div class="container mt-5">
    <h2>Manage Bookings</h2>
    <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addModal">Add Booking</button>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Bus</th>
          <th>Route</th>
          <th>Date</th>
          <th>Seats</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $bookings->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['user_name'] ?></td>
            <td><?= $row['bus_name'] ?></td>
            <td><?= $row['source'] . " to " . $row['destination'] ?></td>
            <td><?= $row['date'] ?></td>
            <td><?= $row['seats'] ?></td>
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
                    <h5 class="modal-title">Edit Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <div class="mb-3">
                      <label>User</label>
                      <select name="user_id" class="form-control" required>
                        <?php foreach ($users as $user): ?>
                          <option value="<?= $user['id'] ?>" <?= $user['id'] == $row['user_id'] ? 'selected' : '' ?>>
                            <?= $user['name'] ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label>Bus</label>
                      <select name="bus_id" class="form-control" required>
                        <?php foreach ($buses as $bus): ?>
                          <option value="<?= $bus['id'] ?>" <?= $bus['id'] == $row['bus_id'] ? 'selected' : '' ?>>
                            <?= $bus['bus_name'] ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label>Route</label>
                      <select name="route_id" class="form-control" required>
                        <?php foreach ($routes as $route): ?>
                          <option value="<?= $route['id'] ?>" <?= $route['id'] == $row['route_id'] ? 'selected' : '' ?>>
                            <?= $route['source'] . " to " . $route['destination'] ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label>Date</label>
                      <input type="date" name="date" class="form-control" value="<?= $row['date'] ?>" required>
                    </div>
                    <div class="mb-3">
                      <label>Seats</label>
                      <input type="number" name="seats" class="form-control" value="<?= $row['seats'] ?>" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="edit_booking" class="btn btn-success">Save Changes</button>
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
            <h5 class="modal-title">Add Booking</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label>User</label>
              <select name="user_id" class="form-control" required>
                <?php foreach ($users as $user): ?>
                  <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label>Bus</label>
              <select name="bus_id" class="form-control" required>
                <?php foreach ($buses as $bus): ?>
                  <option value="<?= $bus['id'] ?>"><?= $bus['bus_name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label>Route</label>
              <select name="route_id" class="form-control" required>
                <?php foreach ($routes as $route): ?>
                  <option value="<?= $route['id'] ?>"><?= $route['source'] . " to " . $route['destination'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label>Date</label>
              <input type="date" name="date" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Seats</label>
              <input type="number" name="seats" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="add_booking" class="btn btn-primary">Add Booking</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
