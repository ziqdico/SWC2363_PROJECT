<div class="main-container">
    <!-- Sidebar for Popular Movies -->
    <div class="sidebar">
        <h3>Popular Movies</h3>
        <ul>
            <li class="movie-item">
                <a href="movie_events.php?id=1">
                    <div class="movie-thumbnail">
                        <img src="admin/news_images/the-marvels-movie-poster.jpg" alt="The Marvels" width="100" height="150">
                    </div>
                    <div class="movie-details">
                        <strong>The Marvels</strong><br>
                        <em>Genre: Action, Adventure, Sci-Fi</em><br>
                        <span class="release-date">Release Date: November 10, 2023</span><br>
                        <p>The next chapter in the Marvel Cinematic Universe...</p>
                        <p><strong>Cast:</strong> Brie Larson, Iman Vellani, Teyonah Parris</p>
                    </div>
                </a>
            </li>
            <li class="movie-item">
                <a href="movie_events.php?id=2">
                    <div class="movie-thumbnail">
                        <img src="admin/news_images/killer of the flowers moon.jpg" alt="Killers of the Flower Moon" width="100" height="150">
                    </div>
                    <div class="movie-details">
                        <strong>Killers of the Flower Moon</strong><br>
                        <em>Genre: Crime, Drama, History</em><br>
                        <span class="release-date">Release Date: October 20, 2023</span><br>
                        <p>A gripping crime drama about the Osage murders...</p>
                        <p><strong>Cast:</strong> Leonardo DiCaprio, Robert De Niro, Lily Gladstone</p>
                    </div>
                </a>
            </li>
            <li class="movie-item">
                <a href="movie_events.php?id=3">
                    <div class="movie-thumbnail">
                        <img src="admin/news_images/the hunger games.jpg" alt="The Hunger Games: Ballad of Songbirds and Snakes" width="100" height="150">
                    </div>
                    <div class="movie-details">
                        <strong>The Hunger Games: Ballad of Songbirds and Snakes</strong><br>
                        <em>Genre: Action, Adventure, Drama</em><br>
                        <span class="release-date">Release Date: November 17, 2023</span><br>
                        <p>A prequel set in Panem, focusing on the origins of...</p>
                        <p><strong>Cast:</strong> Rachel Zegler, Tom Blyth, Hunter Schafer</p>
                    </div>
                </a>
            </li>
        </ul>
        <div class="clear"></div>
    </div>
</div>

<style>
    /* Main container for layout */
    .main-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin: 20px auto;
        max-width: 1200px;
        padding: 0 15px;
    }

    /* Main content styling */
    .main-content {
        flex: 3; /* Take more space for content */
        margin-right: 20px;
        padding: 20px;
        background: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }

    .main-content h1 {
        font-size: 24px;
        color: #333;
    }

    .main-content p {
        font-size: 16px;
        color: #555;
    }

    /* Sidebar styling */
    .sidebar {
        flex: 1; /* Take smaller space for the sidebar */
        background-color: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }

    .sidebar h3 {
        font-size: 18px;
        color: #333;
        margin-bottom: 15px;
    }

    .sidebar ul {
        list-style-type: none;
        padding: 0;
    }

    .sidebar ul li {
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
    }

    .sidebar ul li a {
        color: #333;
        text-decoration: none;
        display: flex;
        width: 100%;
    }

    .sidebar ul li .movie-thumbnail {
        margin-right: 10px;
    }

    .sidebar ul li .movie-details {
        flex: 1;
    }

    .sidebar ul li .movie-details strong {
        font-size: 16px;
        display: block;
        color: #333;
    }

    .sidebar ul li .movie-details p {
        margin-top: 5px;
        font-size: 14px;
        color: #666;
    }

    .sidebar ul li .release-date {
        font-size: 12px;
        color: #777;
    }

    .sidebar ul li a:hover {
        color: #007BFF;
    }

    .clear {
        clear: both;
    }

    /* Optional responsive styling */
    @media (max-width: 768px) {
        .sidebar ul li .movie-thumbnail img {
        width: 150%; /* Image takes full width in mobile view */
        height: auto;
    }


        .main-content {
            margin-right: 0;
            margin-bottom: 20px;
        }

        .sidebar {
            width: 100%; /* Sidebar takes full width on smaller screens */
        }
    }
</style>
