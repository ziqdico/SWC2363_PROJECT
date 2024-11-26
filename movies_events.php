<?php
include('header.php');
include('config.php'); // Include the database configuration file

// Pagination settings
$limit = 10; // Number of movies per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure the page number is at least 1
$offset = ($page - 1) * $limit;

// Secure query to fetch active movies
$stmt = $con->prepare("SELECT * FROM movies WHERE status = 0 LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$qry_movies = $stmt->get_result();

// Count total movies for pagination
$total_movies_query = $con->query("SELECT COUNT(*) as count FROM movies WHERE status = 0");
$total_movies = $total_movies_query->fetch_assoc()['count'];
$total_pages = ceil($total_movies / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Events</title>
    <style>
        /* Container for the grid layout */
        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
            justify-items: center;
            background-color: #f9f9f9; /* Optional background */
        }

        /* Each movie item styling */
        .movie-item {
            text-align: center;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px;
            max-width: 250px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .movie-item:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }

        /* Thumbnail (poster) styling */
        .movie-thumbnail img {
            border-radius: 8px;
            margin-bottom: 10px;
            width: 100%;
            height: auto;
        }

        /* Movie details below the poster */
        .movie-details h3 {
            font-size: 18px;
            margin: 10px 0;
            color: #333;
        }

        .movie-details p {
            font-size: 14px;
            margin: 5px 0;
            color: #666;
        }

        /* Add to Cart button */
        .book-now {
            display: inline-block;
            padding: 15px 30px;
            background-color: #ff4081; /* Pink color */
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            border-radius: 30px; /* Rounded corners */
            border: none; /* Remove border */
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .book-now:hover {
            background-color: #e73370; /* Darker pink on hover */
            transform: scale(1.05); /* Slight zoom effect */
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }

        .book-now:active {
            transform: scale(0.95); /* Shrink on click */
        }

        /* Pagination styles */
        .pagination {
            text-align: center;
            margin: 20px 0;
        }

        .pagination a {
            display: inline-block;
            padding: 10px 15px;
            margin: 0 5px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        .pagination .active {
            background-color: #0056b3;
            font-weight: bold;
        }

        /* Responsive design for smaller screens */
        @media (max-width: 768px) {
            .movie-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }

            .movie-item {
                max-width: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="wrap">
            <h1 style="text-align: center; color: #555; margin-bottom: 20px;">Available Movies</h1>
            <?php
            if ($qry_movies->num_rows > 0) {
                echo '<div class="movie-grid">';
                while ($movie = $qry_movies->fetch_assoc()) {
                    ?>
                    <div class="movie-item">
                        <div class="movie-details">
                            <img src="<?php echo htmlspecialchars($movie['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($movie['movie_name'], ENT_QUOTES, 'UTF-8'); ?>">
                            <h3><?php echo htmlspecialchars($movie['movie_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p><strong>Cast:</strong> <?php echo htmlspecialchars($movie['cast'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($movie['desc'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p><strong>Release Date:</strong> <?php echo date("F j, Y", strtotime($movie['release_date'])); ?></p>
                            <form action="booking.php" method="POST">
                                <button type="submit" class="book-now">Book Now</button>
                            </form>
                        </div>
                    </div>
                    <?php
                }
                echo '</div>';
            } else {
                echo "<p style='text-align: center;'>No active movies available at the moment.</p>";
            }
            ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo "<a href='?page=$i' class='active'>$i</a>";
                } else {
                    echo "<a href='?page=$i'>$i</a>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php include('footer.php'); ?>
