<?php
// booking_form.php



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use null coalescing operator to prevent undefined key warnings
    $movie = $_POST['movie'] ?? '';
    $theatre = $_POST['theatre'] ?? '';
    $screen = $_POST['screen'] ?? '';
    $date = $_POST['date'] ?? '';
    $showtime = $_POST['showtime'] ?? '';
    $seats = $_POST['seats'] ?? '';
    $amount = $_POST['amount'] ?? '';

    // If any required fields are empty, return an error
    if (!$movie || !$theatre || !$screen || !$date || !$showtime || !$seats || !$amount) {
        echo "<h2>Error</h2>";
        echo "<p>All fields are required. Please fill out the form completely.</p>";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $movie_id = isset($_POST['movie_id']) ? (int)$_POST['movie_id'] : 0;
        
        // Check if movie_id is valid and exists in the database
        if ($movie_id > 0) {
            // You can now query the database to get details for the selected movie
            $stmt = $con->prepare("SELECT * FROM movies WHERE movie_id = ?");
            $stmt->bind_param("i", $movie_id);
            $stmt->execute();
            $movie = $stmt->get_result()->fetch_assoc();
    
            if ($movie) {
                // Movie details available, proceed with the booking process
            } else {
                echo "Movie not found.";
            }
        } else {
            echo "Invalid movie selection.";
        }
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Ticket Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            width: 50%;
            margin: auto;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .movie-poster img {
            width: 100%;
            border-radius: 8px;
        }
        .form-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
        }
        .form-row label {
            flex: 1;
        }
        .form-row input, .form-row select {
            flex: 2;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .amount-display {
            font-size: 20px;
            color: #333;
            margin-top: 10px;
        }
        .book-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .book-btn:hover {
            background-color: #0056b3;
        }
        .status {
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
        }
        .status.available {
            color: green;
        }
        .status.unavailable {
            color: red;
        }
        .status.ongoing {
            color: orange;
        }
        .disabled {
            color: #ccc;
        }
        .seat-map {
            display: grid;
            grid-template-columns: repeat(10, 1fr); /* Create a grid with 10 columns */
            gap: 5px;
            margin: 20px 0;
            justify-items: center;
        }
        .seat {
            width: 40px;
            height: 40px;
            border-radius: 4px;
            background-color: #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            font-size: 12px;
        }
        .seat.selected {
            background-color: #0056b3; /* Orange color for selected seats */
            color: white;
        }
        .seat.taken {
            background-color: #ff6f61; /* Red color for taken seats */
            cursor: not-allowed;
        }
        .seat.available {
            background-color: #28a745; /* Green color for available seats */
        }
        .row-label {
            width: 40px;
            text-align: center;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Movie Ticket Booking</h2>

    <form action="booking_form.php" method="POST">
        <!-- Movie details, theatre and screen selection -->
        <div class="form-row">
            <label for="movie">Movie</label>
            <select name="movie" id="movie" required>
                <option value="Black Panther: Wakanda Forever">Black Panther: Wakanda Forever</option>
                <option value="Godzilla vs. Kong">Godzilla vs. Kong</option>
                <option value="Cherry">Cherry</option>
                <option value="Top Gun: Maverick">Top Gun: Maverick</option>
                <option value="Avatar: The Way of Water">Avatar: The Way of Water</option>
                <option value="Doctor Strange in the Multiverse of Madness">Doctor Strange in the Multiverse of Madness</option>
                <option value="Inception">Inception</option>
                <option value="Justice League">Justice League</option>
                <option value="The Invisible Man">The Invisible Man</option>
                <option value="Outside the Wire">Outside the Wire</option>
            </select>
        </div>

        <!-- Theatre Selection -->
        <div class="form-row">
            <label for="theatre">Theatre</label>
            <select name="theatre" id="theatre" required>
                <option value="IOI CITY MALL">IOI CITY MALL, Putrajaya, PUT</option>
                <option value="IOI MALL">IOI MALL, Puchong, SEL</option>
                <option value="PAVILLION BK">PAVILLION BUKIT JALIL, Bukit Jalil, KL</option>
                <option value="PAVILLION BB">PAVILLION BUKIT BINTANG, Bukit Bintang, KL</option>
                <option value="PAVILLION GH">PAVILLION GENTING HIGHLAND, Genting Highland, PHG</option>
                <option value="PAVILLION DA">PAVILLION DAMANSARA, Damansara, SEL</option>
            </select>
        </div>

        <!-- Screen Selection -->
        <div class="form-row">
            <label for="screen">Screen</label>
            <select name="screen" id="screen" required onchange="updatePrice()">
                <option value="screen1" data-price="15">Screen 1</option>
                <option value="screen2" data-price="15">Screen 2</option>
                <option value="screen3" data-price="15">Screen 3</option>
                <option value="3d" data-price="30">3D Screen</option>
                <option value="imax" data-price="60">IMAX Screen</option>
            </select>
        </div>

        <!-- Date Selection -->
        <div class="form-row">
            <label for="date">Date</label>
            <input type="date" name="date" id="date" required onchange="updateShowtimeStatus()">
        </div>

        <!-- Show Time Selection -->
        <div class="form-row">
            <label for="showtime">Show Time</label>
            <select name="showtime" id="showtime" required onchange="updateShowtimeStatus()">
                <!-- Showtimes will be populated dynamically -->
            </select>
        </div>

        <!-- Number of Seats -->
        <div class="form-row">
            <label for="seats">Number of Seats</label>
            <input type="number" name="seats" id="seats" min="1" max="10" value="1" required readonly>
        </div>

        <!-- Amount Display -->
        <div class="form-row">
            <label for="amount">Amount</label>
            <input type="text" name="amount" id="amount" value="RM 15" readonly>
        </div>

        <!-- Showtime Status -->
        <div class="status" id="showtime-status">
            <!-- Showtime status will be dynamically inserted here -->
        </div>

        <button type="submit" class="book-btn">Book Now</button>
    </form>

    <h3>Seat Selection</h3>
    <div class="seat-map" id="seat-map">
        <!-- Dynamic seat map will be populated here -->
    </div>

</div>

<script>
    const moviesData = {
        "Cherry": {
            "screen1": {
                "9:00 PM": "available",
                "12:00 AM": "full",
                "3:00 AM": "available",
                "6:00 AM": "full"
            },
            "screen2": {
                "9:00 PM": "available",
                "12:00 AM": "available",
                "3:00 AM": "full",
                "6:00 AM": "available"
            },
            "screen3": {
                "9:00 PM": "full",
                "12:00 AM": "available",
                "3:00 AM": "available",
                "6:00 AM": "full"
            },
            "3d": {
                "9:00 PM": "available",
                "12:00 AM": "available",
                "3:00 AM": "full",
                "6:00 AM": "available"
            },
            "imax": {
                "9:00 PM": "available",
                "12:00 AM": "full",
                "3:00 AM": "available",
                "6:00 AM": "available",
                "9:00 AM": "full",
                "12:00 PM": "available",
                "3:00 PM": "available"
            }
        }
    };

    let selectedSeats = [];
    let takenSeats = ['seat-2-3', 'seat-3-5', 'seat-4-7','seat-1-1','seat-2-10','seat-5-1']; 

    function updatePrice() {
        const screenSelect = document.getElementById('screen');
        const seatsInput = document.getElementById('seats');
        const amountInput = document.getElementById('amount');
        
        const pricePerTicket = parseFloat(screenSelect.options[screenSelect.selectedIndex].getAttribute('data-price'));
        const totalPrice = pricePerTicket * selectedSeats.length;
        
        amountInput.value = "RM " + totalPrice.toFixed(2);
        seatsInput.value = selectedSeats.length; 

        updateShowtimes();
    }

    function updateShowtimes() {
        const screenSelect = document.getElementById('screen');
        const showtimeSelect = document.getElementById('showtime');
        const screen = screenSelect.value;
        const availableShowtimes = moviesData["Cherry"][screen]; 
        
        showtimeSelect.innerHTML = '';

        for (let time in availableShowtimes) {
            const option = document.createElement('option');
            option.value = time;
            option.innerText = time + ' (' + availableShowtimes[time] + ')';
            showtimeSelect.appendChild(option);
        }
    }

    function populateSeats() {
        const seatMap = document.getElementById('seat-map');
        seatMap.innerHTML = '';

        for (let row = 1; row <= 5; row++) {
            for (let col = 1; col <= 10; col++) {
                const seatId = `seat-${row}-${col}`;
                const seat = document.createElement('div');
                
                if (takenSeats.includes(seatId)) {
                    seat.className = 'seat taken';
                    seat.style.cursor = 'not-allowed';
                } else {
                    seat.className = 'seat available';
                }
                
                seat.id = seatId;
                seat.innerText = `${row}-${col}`;
                
                seat.onclick = () => toggleSeatSelection(seatId);
                
                seatMap.appendChild(seat);
            }
        }
    }

    function toggleSeatSelection(seatId) {
        const seat = document.getElementById(seatId);
        
        if (seat.classList.contains('available') && !seat.classList.contains('taken')) {
            seat.classList.toggle('selected');
            if (seat.classList.contains('selected')) {
                selectedSeats.push(seatId);
            } else {
                selectedSeats = selectedSeats.filter(seat => seat !== seatId);
            }

            updatePrice();
        }
    }

    window.onload = function() {
        updatePrice();
        populateSeats();
    };
</script>

</body>
</html>
