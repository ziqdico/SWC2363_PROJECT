<?php
session_start();

// Check if user session exists
if (!isset($_SESSION['user'])) 

// Include the database configuration file
include('config.php');

// Ensure that session variables are set before proceeding
if (isset($_SESSION['theater_id'], $_SESSION['user_id'], $_SESSION['show_id'], $_SESSION['screen_id'], $_SESSION['no_seats'], $_SESSION['amount_id'], $_SESSION['date_id'])) {

    // Sanitize user input to avoid SQL Injection
    $theatre = mysqli_real_escape_string($con, $_SESSION['theater_id']);
    $user = mysqli_real_escape_string($con, $_SESSION['user_id']);
    $show = mysqli_real_escape_string($con, $_SESSION['show_id']);
    $screen = mysqli_real_escape_string($con, $_SESSION['screen_id']);
    $seats = mysqli_real_escape_string($con, $_SESSION['no_seats']);
    $amount = mysqli_real_escape_string($con, $_SESSION['amount_id']);
    $date = mysqli_real_escape_string($con, $_SESSION['date_id']);

    // Extract OTP from POST
    $otp = isset($_POST['otp']) ? $_POST['otp'] : '';

    if ($otp == "123456") {
        // Generate a unique booking ID
        $bookid = "BKID" . rand(1000000, 9999999);

        // Prepare the SQL query for inserting the booking data
        $query = "INSERT INTO bookings (book_id, theater_id, user_id, show_id, screen_id, no_seats, amount, ticket_date, status)
                  VALUES ('$bookid', '$theatre', '$user', '$show', '$screen', '$seats', '$amount', '$date', '1')";

        // Execute the query and check if it was successful
        if (mysqli_query($con, $query)) {
            $_SESSION['success'] = "Booking completed successfully!";
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($con);
        }
    } else {
        $_SESSION['error'] = "Invalid OTP. Payment Failed.";
    }

} else {
    $_SESSION['error'] = "Session data missing. Please ensure that the session variables are set.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0 minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Payment Processing</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <table align="center">
        <tr><td><strong>Transaction is being processed,</strong></td></tr>
        <tr><td><font color="blue">Please Wait <i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span></font></td></tr>
        <tr><td>(Do not 'RELOAD' this page or 'CLOSE' this page)</td></tr>
    </table>
    <h2>
        <?php
        // Display success or error message
        if (isset($_SESSION['success'])) {
            echo $_SESSION['success'];
        } elseif (isset($_SESSION['error'])) {
            echo $_SESSION['error'];
        }
        ?>
    </h2>
    
    <script>
        // Redirect to the profile page after 3 seconds
        setTimeout(function(){
            window.location = "profile.php"; 
        }, 3000);
    </script>
</body>
</html>
