<?php
session_start();
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .container {
            width: 100%;
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            font-size: 16px;
        }

        h2 {
            text-align: left;
            margin-bottom: 20px;
            font-size: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
            box-sizing: border-box;
            background-color: #f0f3f7;
        }

        .form-group input:focus {
            border-color: #007bff;
            outline: none;
            background-color: #fff;
        }

        .form-group label {
            position: absolute;
            top: -10px;
            left: 10px;
            background-color: #fff;
            padding: 0 5px;
            font-size: 12px;
            color: #aaa;
        }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .row .form-group {
            flex: 1;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        .form-group p {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                padding: 15px;
                margin: 20px auto;
            }

            h2 {
                font-size: 18px;
            }

            button {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Payment:</h2>
    <form action="bank.php" method="POST"> <!-- Change the action to bank.php -->
        <div class="form-group">
            <label for="name_on_card">Card holder name</label>
            <input type="text" name="name_on_card" id="name_on_card" required>
            <?php if (isset($errors['name_on_card'])): ?>
                <p><?= htmlspecialchars($errors['name_on_card']) ?></p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="card_number">Card number</label>
            <input type="text" name="card_number" id="card_number" required>
            <?php if (isset($errors['card_number'])): ?>
                <p><?= htmlspecialchars($errors['card_number']) ?></p>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="form-group">
                <label for="expiration_date">Exp. date</label>
                <input type="month" name="expiration_date" id="expiration_date" required>
                <?php if (isset($errors['expiration_date'])): ?>
                    <p><?= htmlspecialchars($errors['expiration_date']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="text" name="cvv" id="cvv" required>
                <?php if (isset($errors['cvv'])): ?>
                    <p><?= htmlspecialchars($errors['cvv']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <button type="submit">Proceed to Payment</button>
    </form>
</div>

</body>
</html>