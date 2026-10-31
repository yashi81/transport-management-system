<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: log.php");
    exit;
}

$con = mysqli_connect("localhost", "root", "", "transport_management");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}


$student_id = $_SESSION['student_id'];

$registration_id = $_POST['registration_id'] ?? '';
$amount = $_POST['amount'] ?? '';
$payment_date = $_POST['payment_date'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';
$transaction_id = $_POST['transaction_id'] ?? '';


/* Check required fields */

if (
    empty($registration_id) ||
    empty($amount) ||
    empty($payment_date) ||
    empty($payment_method)
) {

    mysqli_close($con);

    header("Location: payment.php?error=1");
    exit;
}


/* Check amount */

if (!is_numeric($amount) || $amount <= 0) {

    mysqli_close($con);

    header("Location: payment.php?error=1");
    exit;
}


/* Check payment method */

$allowed_methods = array(
    'Cash',
    'Online',
    'UPI',
    'Card',
    'Other'
);

if (!in_array($payment_method, $allowed_methods)) {

    mysqli_close($con);

    header("Location: payment.php?error=1");
    exit;
}


/*
   Transaction ID is required for Online, UPI and Card.
*/

if (
    ($payment_method === 'Online' ||
     $payment_method === 'UPI' ||
     $payment_method === 'Card')
    &&
    empty($transaction_id)
) {

    mysqli_close($con);

    header("Location: payment.php?error=1");
    exit;
}


/*
   Check that the selected registration belongs
   to the logged-in student.
*/

$registration_sql = "SELECT registration_id
                     FROM transport_registration
                     WHERE registration_id = ?
                     AND student_id = ?";

$registration_stmt =
    mysqli_prepare($con, $registration_sql);

mysqli_stmt_bind_param(
    $registration_stmt,
    "ii",
    $registration_id,
    $student_id
);

mysqli_stmt_execute($registration_stmt);

$registration_result =
    mysqli_stmt_get_result($registration_stmt);

$registration =
    mysqli_fetch_assoc($registration_result);

mysqli_stmt_close($registration_stmt);


if (!$registration) {

    mysqli_close($con);

    header("Location: payment.php?error=1");
    exit;
}


/*
   Check whether this registration already has
   a payment record.
*/

$existing_sql = "SELECT payment_id
                 FROM payment
                 WHERE registration_id = ?
                 LIMIT 1";

$existing_stmt =
    mysqli_prepare($con, $existing_sql);

mysqli_stmt_bind_param(
    $existing_stmt,
    "i",
    $registration_id
);

mysqli_stmt_execute($existing_stmt);

$existing_result =
    mysqli_stmt_get_result($existing_stmt);

$existing_payment =
    mysqli_fetch_assoc($existing_result);

mysqli_stmt_close($existing_stmt);


if ($existing_payment) {

    mysqli_close($con);

    header("Location: payment.php?error=1");
    exit;
}


/*
   For this project demonstration, a successfully
   submitted payment is recorded as Paid.

   This is NOT a real online payment gateway.
*/

$payment_status = "Paid";


$insert_sql = "INSERT INTO payment
               (
                   registration_id,
                   amount,
                   payment_date,
                   payment_method,
                   transaction_id,
                   payment_status
               )
               VALUES (?, ?, ?, ?, ?, ?)";


$insert_stmt =
    mysqli_prepare($con, $insert_sql);


mysqli_stmt_bind_param(
    $insert_stmt,
    "idssss",
    $registration_id,
    $amount,
    $payment_date,
    $payment_method,
    $transaction_id,
    $payment_status
);


if (mysqli_stmt_execute($insert_stmt)) {

    $payment_id = mysqli_insert_id($con);

    mysqli_stmt_close($insert_stmt);
    mysqli_close($con);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Payment Successful - College Transport Management System
    </title>


    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .header {
            background: #222;
            color: white;
            text-align: center;
            padding: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 25px;
        }

        .box {
            width: 60%;
            margin: 60px auto;
            background: white;
            padding: 35px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .success {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .details {
            text-align: left;
            background: #f1f1f1;
            padding: 20px;
            border-radius: 5px;
            line-height: 1.9;
        }

        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 25px;
            background: #222;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
        }

    </style>

</head>


<body>


<div class="header">

    <h1>
        INDIRA GANDHI NATIONAL OPEN UNIVERSITY (IGNOU)
    </h1>

    <p>
        COLLEGE TRANSPORT MANAGEMENT SYSTEM
    </p>

</div>


<div class="box">

    <div class="success">
        Payment Submitted Successfully
    </div>


    <div class="details">

        <strong>Payment ID:</strong>

        <?php
        echo htmlspecialchars($payment_id);
        ?>

        <br>


        <strong>Registration ID:</strong>

        <?php
        echo htmlspecialchars($registration_id);
        ?>

        <br>


        <strong>Amount:</strong>

        ₹<?php
        echo htmlspecialchars(number_format((float)$amount, 2));
        ?>

        <br>


        <strong>Payment Date:</strong>

        <?php
        echo htmlspecialchars($payment_date);
        ?>

        <br>


        <strong>Payment Method:</strong>

        <?php
        echo htmlspecialchars($payment_method);
        ?>

        <br>


        <strong>Transaction ID:</strong>

        <?php

        if (!empty($transaction_id)) {

            echo htmlspecialchars($transaction_id);

        } else {

            echo "Not Applicable";

        }

        ?>

        <br>


        <strong>Payment Status:</strong>

        Paid

    </div>


    <p>

        Your payment has been recorded successfully
        in the transport management system.

    </p>


    <a href="student.php" class="btn">
        Back to Student Dashboard
    </a>


</div>


</body>

</html>

<?php

} else {

    mysqli_stmt_close($insert_stmt);
    mysqli_close($con);

    header("Location: payment.php?error=1");
    exit;
}

?>