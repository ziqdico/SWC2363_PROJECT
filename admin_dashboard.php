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

// Handle adding a new movie
if (isset($_POST['add_movie'])) {
    $title = htmlspecialchars($_POST['title']);
    $cast = htmlspecialchars($_POST['cast']);
    $description = htmlspecialchars($_POST['description']);
    $image = htmlspecialchars($_POST['image']);
    $video_url = htmlspecialchars($_POST['video_url']);
    $release_date = htmlspecialchars($_POST['release_date']);

    try {
        $stmt = $pdo->prepare("INSERT INTO movies (movie_name, cast, `desc`, image, video_url, release_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $cast, $description, $image, $video_url, $release_date]);
        $success_message = "New movie added successfully!";
    } catch (PDOException $e) {
        $error_message = "Error adding movie: " . $e->getMessage();
    }
}

// Handle editing a user
if (isset($_POST['edit_user'])) {
    $user_id = htmlspecialchars($_POST['user_id']);
    $first_name = htmlspecialchars($_POST['first_name']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);

    try {
        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, email = ?, role = ? WHERE user_id = ?");
        $stmt->execute([$first_name, $email, $role, $user_id]);
        $success_message = "User updated successfully!";
    } catch (PDOException $e) {
        $error_message = "Error updating user: " . $e->getMessage();
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

// Handle deleting a movie
if (isset($_POST['delete_movie'])) {
    $movie_id = htmlspecialchars($_POST['delete_movie_id']);
    try {
        $stmt = $pdo->prepare("DELETE FROM movies WHERE movie_id = ?");
        $stmt->execute([$movie_id]);
        $success_message = "Movie deleted successfully!";
    } catch (PDOException $e) {
        $error_message = "Error deleting movie: " . $e->getMessage();
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

// Fetch all movies
try {
    $stmt = $pdo->prepare("SELECT * FROM movies");
    $stmt->execute();
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching movies: " . $e->getMessage());
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
        <a href="admin_dashboard.php#users-section">Manage Users</a>
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

            <!-- Manage Users Section -->
            <h3 id="users-section">Manage Users</h3>
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
                        <th>Actions</th>
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
                                <!-- Edit User -->
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" required>
                                    <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                                    <select name="role" required>
                                        <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        <option value="staff" <?php echo $user['role'] == 'staff' ? 'selected' : ''; ?>>Staff</option>
                                        <option value="customer" <?php echo $user['role'] == 'customer' ? 'selected' : ''; ?>>Customer</option>
                                    </select>
                                    <button type="submit" name="edit_user" class="btn btn-sm btn-warning">Save</button>
                                </form>
                                <!-- Delete User -->
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="delete_user_id" value="<?php echo $user['user_id']; ?>">
                                    <button type="submit" name="delete_user" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Manage Movies Section -->
            <h3 id="movies-section" class="mt-5">Manage Movies</h3>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="cast" class="form-label">Cast</label>
                    <textarea name="cast" class="form-control" rows="2" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image URL</label>
                    <input type="text" name="image" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="video_url" class="form-label">Video URL</label>
                    <input type="text" name="video_url" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="release_date" class="form-label">Release Date</label>
                    <input type="date" name="release_date" class="form-control" required>
                </div>
                <button type="submit" name="add_movie" class="btn btn-primary">Add Movie</button>
            </form>

            <!-- Movie List -->
            <h3 class="mt-4">Movies List</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Cast</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Video URL</th>
                        <th>Release Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movies as $movie): ?>
                        <tr>
                            <td><?php echo $movie['movie_id']; ?></td>
                            <td><?php echo $movie['movie_name']; ?></td>
                            <td><?php echo $movie['cast']; ?></td>
                            <td><?php echo $movie['desc']; ?></td>
                            <td><img src="<?php echo $movie['image']; ?>" alt="<?php echo $movie['movie_name']; ?>" width="100"></td>
                            <td><a href="<?php echo $movie['video_url']; ?>" target="_blank">Watch Trailer</a></td>
                            <td><?php echo $movie['release_date']; ?></td>
                            <td>
                                <a href="edit_movie.php?id=<?php echo $movie['movie_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="delete_movie_id" value="<?php echo $movie['movie_id']; ?>">
                                    <button type="submit" name="delete_movie" class="btn btn-sm btn-danger">Delete</button>
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
