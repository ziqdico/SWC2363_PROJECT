<html>
<head>
    <style>
        /* Basic styling for the page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .content {
            width: 80%;
            margin: 0 auto;
        }

        .wrap {
            padding: 20px;
        }

        .content-top {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .listview_1_of_3 {
            width: 48%;
        }

        .listview_1_of_3 h2 {
            color: #555;
            font-size: 22px;
        }

        /* Upcoming Movies */
        .content-left {
            display: flex;
            margin-bottom: 20px;
        }

        .content-left img {
            width: 100px;
            height: 150px;
            object-fit: cover;
            margin-right: 20px;
        }

        .text {
            flex: 1;
        }

        .text .extra-wrap {
            font-size: 14px;
        }

        .text .data {
            color: #333;
            margin: 5px 0;
        }

        /* Movie Trailers Section */
        .middle-list {
            display: flex;
            flex-direction: column; /* Stack trailers vertically */
            gap: 40px; /* Adjust the gap between trailers */
        }

        .listimg1 {
            width: 100%;
            text-align: center;
        }

        /* Watch Now Button */
        .watch-now-button button {
            background-color: #FF4500;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
            text-decoration: none;
        }

        .watch-now-button button:hover {
            background-color: #ff6347;
        }

        /* Sidebar and Footer */
        .clear {
            clear: both;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: #fff;
        }

        /* Gap between Upcoming Movies and Movie Trailers */
        .listview_1_of_3.images_1_of_3 {
            margin-top: 40px; /* Add margin for gap */
        }

        /* Optional responsive design */
        @media (max-width: 768px) {
            .content-top {
                flex-direction: column;
            }

            .listview_1_of_3 {
                width: 100%;
            }

            .middle-list {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
<?php
include('config.php'); // Include the database connection
include('header.php');
?>

<div class="content">
    <div class="wrap">
        <div class="content-top">
            <!-- Upcoming Movies Section -->
            <div class="listview_1_of_3 images_1_of_3">
                <h2>Upcoming Movies</h2>
                <?php 
                // Query to fetch upcoming movies
                $qry3 = mysqli_query($con, "SELECT * FROM upcoming_movie LIMIT 5");

                while ($n = mysqli_fetch_array($qry3)) {
                ?>
                <div class="content-left">
                    <div class="listimg listimg_1_of_2">
                        <!-- Ensure correct path to image -->
                        <img src="admin/<?php echo htmlspecialchars($n['attachment']); ?>" alt="<?php echo htmlspecialchars($n['name']); ?>" />
                    </div>
                    <div class="text list_1_of_2">
                        <div class="extra-wrap">
                            <strong><?php echo htmlspecialchars($n['name']); ?></strong><br>
                            <strong>Cast: <?php echo htmlspecialchars($n['cast']); ?></strong><br>
                            <div class="data">Release Date: <?php echo htmlspecialchars($n['news_date']); ?></div>
                            <span class="text-top"><?php echo htmlspecialchars($n['description']); ?></span>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <?php
                }
                ?>
            </div>

            <!-- Movie Trailers Section -->
            <div class="listview_1_of_3 images_1_of_3">
                <h2>Movie Trailers</h2>
                <div class="middle-list">
                    <?php 
                    // Query to fetch movie trailers, sorted by specific order
                    $qry4 = mysqli_query($con, "SELECT * FROM movie_trailer WHERE movie_name IN ('Black Widow', 'Shang-Chi and the Legend of the Ten Rings', 'The Eternals') ORDER BY FIELD(movie_name, 'Black Widow', 'Shang-Chi and the Legend of the Ten Rings', 'The Eternals')");

                    while ($nm = mysqli_fetch_array($qry4)) {
                    ?>
                    <div class="listimg1">
                        <!-- Only Watch Now Button, without image -->
                        <a target="_blank" href="<?php echo htmlspecialchars($nm['video_url']); ?>" class="watch-now-button">
                            <button>Watch Now</button>
                        </a>
                        <a target="_blank" href="<?php echo htmlspecialchars($nm['video_url']); ?>" class="link" style="text-decoration:none; font-size:14px;"><?php echo htmlspecialchars($nm['movie_name']); ?></a>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <?php include('movie_sidebar.php'); ?>
        </div>
    </div>
    <?php include('footer.php'); ?>
</div>
<?php include('searchbar.php'); ?>
</body>
</html>
