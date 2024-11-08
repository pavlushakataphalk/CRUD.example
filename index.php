<?php
// Database connection
$host = 'localhost';
$db = 'testdb';
$user = 'root';
$password = '';
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $query = "INSERT INTO users (name, email) VALUES ('$name', '$email')";
    $conn->query($query);
}

// Delete user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM users WHERE id = $id";
    $conn->query($query);
}

// Update user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $query = "UPDATE users SET name = '$name', email = '$email' WHERE id = $id";
    $conn->query($query);
}

// Read users
$users = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Example</title>
</head>
<body>
    <h1>CRUD with PHP and MySQL</h1>

    <!-- Create Form -->
    <h2>Add a User</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit" name="create">Create</button>
    </form>

    <!-- Users Table -->
    <h2>Users List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while ($user = $users->fetch_assoc()): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['name'] ?></td>
            <td><?= $user['email'] ?></td>
            <td>
                <form method="POST" style="display:inline-block">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <input type="text" name="name" value="<?= $user['name'] ?>" required>
                    <input type="email" name="email" value="<?= $user['email'] ?>" required>
                    <button type="submit" name="update">Update</button>
                </form>
                <a href="?delete=<?= $user['id'] ?>" onclick="return confirm('Delete this user?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
