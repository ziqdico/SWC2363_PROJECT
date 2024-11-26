<?php 
include('header.php');
include('config.php');

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure session variables are set
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = ''; // Default value
}
if (!isset($_SESSION['bookings'])) {
    $_SESSION['bookings'] = ''; // Default value
}

// Fetch movie details if movie ID exists in session
if (isset($_SESSION['movies']) && !empty($_SESSION['movies'])) {
    $qry2 = mysqli_query($con, "SELECT * FROM movies WHERE movie_id='" . $_SESSION['movies'] . "'");
    $movie = mysqli_fetch_array($qry2);
}
?>
<div class="content">
    <div class="wrap">
        <div class="content-top">
            <div class="section group">
                <div class="about span_1_of_2">    
                    <h3 style="color:black;" class="text-center">BOOKING HISTORY</h3>
                    <?php include('msgbox.php'); ?>
                    <?php
                    // Fetch bookings for the logged-in user
                    $bk = mysqli_query($con, "SELECT * FROM bookings WHERE user_id='" . $_SESSION['user'] . "'");
                    if (mysqli_num_rows($bk) > 0) {
                        ?>
                        <table class="table table-bordered">
                            <thead>
                                <th>Booking Id</th>
                                <th>Movie</th>
                                <th>Screen</th>
                                <th>Date</th>
                                <th>No. of Seats</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                            <?php
                            while ($bkg = mysqli_fetch_array($bk)) {
                                // Fetch movie details
                                $m = mysqli_query($con, "SELECT * FROM movies WHERE movie_id=(SELECT movie_id FROM tbl_shows WHERE s_id='" . $bkg['show_id'] . "')");
                                $mov = mysqli_fetch_array($m);

                                // Fetch screen details
                                $s = mysqli_query($con, "SELECT * FROM screens WHERE screen_id='" . $bkg['screen_id'] . "'");
                                $srn = mysqli_fetch_array($s);
                                ?>
                                <tr>
                                    <td><?php echo $bkg['ticket_id']; ?></td>
                                    <td><?php echo $mov['movie_name']; ?></td>
                                    <td><?php echo $srn['screen_name']; ?></td>
                                    <td><?php echo $bkg['ticket_date']; ?></td>
                                    <td><?php echo $bkg['no_seats']; ?></td>
                                    <td>RM <?php echo $bkg['amount']; ?></td>
                                    <td>
                                        <?php if ($bkg['ticket_date'] < date('Y-m-d')) { ?>
                                            <i class="glyphicon glyphicon-ok"></i> Completed
                                        <?php } else { ?>
                                            <a href="cancel.php?id=<?php echo $bkg['book_id']; ?>" style="text-decoration:none; color:red;">Cancel</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                        <?php
                    } else {
                        ?>
                        <h3 style="color:red;" class="text-center">No Previous Bookings Found!</h3>
                        <p>Once you start booking movie tickets with this account, you'll be able to see all the booking history.</p>
                        <?php
                    }
                    ?>
                </div>            
                <?php include('movie_sidebar.php'); ?>
            </div>
            <div class="clear"></div>        
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
<script type="text/javascript">
    $('#seats').change(function() {
        var charge = <?php echo isset($screen['charge']) ? $screen['charge'] : 0; ?>;
        amount = charge * $(this).val();
        $('#amount').html("RM " + amount);
        $('#hm').val(amount);
    });
</script>
