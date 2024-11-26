<?php
session_start();
include 'config.php'; // Include your database connection file

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch the movie details
if (isset($_GET['id'])) {
    $movie_id = htmlspecialchars($_GET['id']);
    try {
        $stmt = $pdo->prepare("SELECT * FROM movies WHERE movie_id = ?");
        $stmt->execute([$movie_id]);
        $movie = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching movie: " . $e->getMessage());
    }
}

// Handle movie updates
if (isset($_POST['update_movie'])) {
    $movie_id = htmlspecialchars($_POST['movie_id']);
    $title = htmlspecialchars($_POST['title']);
    $cast = htmlspecialchars($_POST['cast']);
    $description = htmlspecialchars($_POST['description']);
    $image = htmlspecialchars($_POST['image']);
    $video_url = htmlspecialchars($_POST['video_url']);
    $release_date = htmlspecialchars($_POST['release_date']);

    try {
        $stmt = $pdo->prepare("UPDATE movies SET movie_name = ?, cast = ?, `desc` = ?, image = ?, video_url = ?, release_date = ? WHERE movie_id = ?");
        $stmt->execute([$title, $cast, $description, $image, $video_url, $release_date, $movie_id]);
        header("Location: admin_dashboard.php");
    } catch (PDOException $e) {
        $error_message = "Error updating movie: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Movie</h2>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="movie_id" value="<?php echo $movie['movie_id']; ?>">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo $movie['movie_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="cast" class="form-label">Cast</label>
                <textarea name="cast" class="form-control" rows="2" required><?php echo $movie['cast']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" required><?php echo $movie['desc']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image URL</label>
                <input type="text" name="image" class="form-control" value="<?php echo $movie['image']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="video_url" class="form-label">Video URL</label>
                <input type="text" name="video_url" class="form-control" value="<?php echo $movie['video_url']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="release_date" class="form-label">Release Date</label>
                <input type="date" name="release_date" class="form-control" value="<?php echo $movie['release_date']; ?>" required>
            </div>
            <button type="submit" name="update_movie" class="btn btn-primary">Update Movie</button>
        </form>
    </div>
</body>
</html>
