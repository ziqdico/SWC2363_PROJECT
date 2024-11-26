<?php
session_start();

// Initialize an errors array
$errors = [];

// Validate Name on Card
if (empty(trim($_POST["name_on_card"]))) {
    $errors['name_on_card'] = "The Name is required and can't be empty.";
}

// Validate Card Number
if (empty(trim($_POST["card_number"]))) {
    $errors['card_number'] = "The Card Number is required and can't be empty.";
} elseif (!preg_match('/^\d{16}$/', $_POST["card_number"])) {
    $errors['card_number'] = "The Card Number must be 16 digits.";
}

// Validate Expiration Date
if (empty(trim($_POST["expiration_date"]))) {
    $errors['expiration_date'] = "The Expiration Date is required and can't be empty.";
} else {
    $expiration_date = DateTime::createFromFormat('Y-m', $_POST["expiration_date"]);
    $current_date = new DateTime('first day of this month'); // Start of the current month

    if (!$expiration_date || $expiration_date < $current_date) {
        $errors['expiration_date'] = "The Expiration Date is invalid or expired.";
    }
}

// Validate CVV
if (empty(trim($_POST["cvv"]))) {
    $errors['cvv'] = "The CVV is required and can't be empty.";
} elseif (!preg_match('/^\d{3,4}$/', $_POST["cvv"])) {
    $errors['cvv'] = "The CVV must be 3 or 4 digits.";
}

// Check if there are validation errors
if (!empty($errors)) {
    // Store errors in session and redirect back to the payment form
    $_SESSION['errors'] = $errors;
    header("Location: payment_form.php");
    exit();
}

// Simulate payment processing (here you would integrate with a real payment gateway)
// For now, simulate successful payment.
$isPaymentSuccessful = true; // Simulating success

if ($isPaymentSuccessful) {
    // Store success message
    $_SESSION['success'] = "Payment Successful. Your booking has been confirmed.";
} else {
    // Store error message in case of failed payment
    $_SESSION['error'] = "Payment failed. Please try again.";
}

// Redirect to bank.php for payment status or final confirmation page
header("Location: bank.php");
exit();
