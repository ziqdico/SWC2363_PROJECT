<?php
session_start();
include 'config.php'; // Include your database connection file

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Handle adding a new user
if (isset($_POST['add_user'])) {
    $first_name = htmlspecialchars($_POST['first_name']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = htmlspecialchars($_POST['role']);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (first_name, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$first_name, $email, $password, $role]);
        $success_message = "New user added successfully!";
    } catch (PDOException $e) {
        $error_message = "Error adding user: " . $e->getMessage();
    }
}

// Handle deleting a user
if (isset($_POST['delete_user'])) {
    $user_id = htmlspecialchars($_POST['delete_user_id']);
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $success_message = "User deleted successfully!";
    } catch (PDOException $e) {
        $error_message = "Error deleting user: " . $e->getMessage();
    }
}

// Fetch all users
try {
    $stmt = $pdo->prepare("SELECT * FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching users: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
            position: fixed;
            width: 220px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px 20px;
            margin: 5px 0;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #007bff;
        }
        .main-content {
            margin-left: 240px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 class="text-center text-white">Admin Dashboard</h3>
        <a href="index.php">Home</a>
        <a href="admin_dashboard.php">Manage Users</a>
        <a href="admin_dashboard.php#movies-section">Manage Movies</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <div class="container">
            <h2>Admin Dashboard</h2>

            <!-- Success/Error Message -->
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                </div>
            <?php elseif (isset($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <!-- Manage Users -->
            <h3>Manage Users</h3>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="first_name" class="form-label">Full Name</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" class="form-control" required>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                        <option value="customer">Customer</option>
                    </select>
                </div>
                <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
            </form>

            <!-- User List -->
            <h3 class="mt-4">Users List</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th> <!-- Actions column -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['user_id']; ?></td>
                            <td><?php echo $user['first_name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['role']; ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="delete_user_id" value="<?php echo $user['user_id']; ?>">
                                    <button type="submit" name="delete_user" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
