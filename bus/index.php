<?php
// Include the database connection
require 'db.php';

// Initialize variables for form
$name = $email = $password = "";
$update = false;
$id = 0;

// Handle form submission for CREATE and UPDATE
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password

    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $password]);

    header("Location: index.php");
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if ($user) {
        $name = $user['name'];
        $email = $user['email'];
    }
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $password, $id]);

    header("Location: index.php");
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    header("Location: index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">CRUD Application for Users</h2>

    <!-- Form for Add / Update -->
    <form action="index.php" method="POST" class="border p-4 mt-4">
        <input type="hidden" name="id" value="<?= $id; ?>">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="<?= $name; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="<?= $email; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <?php if ($update): ?>
                <button type="submit" name="update" class="btn btn-warning">Update</button>
            <?php else: ?>
                <button type="submit" name="save" class="btn btn-primary">Save</button>
            <?php endif; ?>
        </div>
    </form>

    <!-- Display Users Table -->
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM users";
            $stmt = $pdo->query($sql);
            while ($row = $stmt->fetch()) : ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['name']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td>
                        <a href="index.php?edit=<?= $row['id']; ?>" class="btn btn-info btn-sm">Edit</a>
                        <a href="index.php?delete=<?= $row['id']; ?>" class="btn btn-danger btn-sm" 
                           onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
