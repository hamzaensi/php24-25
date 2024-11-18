<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "BusBooking");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Add a new ticket
if (isset($_POST['add_ticket'])) {
    $booking_id = $_POST['booking_id'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $conn->query("INSERT INTO tickets (booking_id, price, status) VALUES ($booking_id, $price, '$status')");
}

// Edit a ticket
if (isset($_POST['edit_ticket'])) {
    $id = $_POST['id'];
    $booking_id = $_POST['booking_id'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $conn->query("UPDATE tickets SET booking_id=$booking_id, price=$price, status='$status' WHERE id=$id");
}

// Delete a ticket
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM tickets WHERE id=$id");
}

// Fetch all tickets
$tickets = $conn->query("SELECT tickets.*, bookings.id AS booking_ref, bookings.date 
                         FROM tickets 
                         JOIN bookings ON tickets.booking_id = bookings.id");

// Fetch related bookings
$bookings = $conn->query("SELECT * FROM bookings");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Tickets</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include 'navbar.php'; ?>
  <div class="container mt-5">
    <h2>Manage Tickets</h2>
    <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addModal">Add Ticket</button>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Booking Reference</th>
          <th>Date</th>
          <th>Price</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $tickets->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['booking_ref'] ?></td>
            <td><?= $row['date'] ?></td>
            <td><?= $row['price'] ?></td>
            <td><?= $row['status'] ?></td>
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
                    <h5 class="modal-title">Edit Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <div class="mb-3">
                      <label>Booking</label>
                      <select name="booking_id" class="form-control" required>
                        <?php foreach ($bookings as $booking): ?>
                          <option value="<?= $booking['id'] ?>" <?= $booking['id'] == $row['booking_id'] ? 'selected' : '' ?>>
                            <?= "Booking #" . $booking['id'] . " - " . $booking['date'] ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label>Price</label>
                      <input type="number" name="price" class="form-control" value="<?= $row['price'] ?>" required>
                    </div>
                    <div class="mb-3">
                      <label>Status</label>
                      <select name="status" class="form-control" required>
                        <option value="Confirmed" <?= $row['status'] == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                        <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Cancelled" <?= $row['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                      </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="edit_ticket" class="btn btn-success">Save Changes</button>
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
            <h5 class="modal-title">Add Ticket</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label>Booking</label>
              <select name="booking_id" class="form-control" required>
                <?php foreach ($bookings as $booking): ?>
                  <option value="<?= $booking['id'] ?>"><?= "Booking #" . $booking['id'] . " - " . $booking['date'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label>Price</label>
              <input type="number" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Status</label>
              <select name="status" class="form-control" required>
                <option value="Confirmed">Confirmed</option>
                <option value="Pending">Pending</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="add_ticket" class="btn btn-primary">Add Ticket</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
