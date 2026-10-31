<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: log.php");
    exit;
}

$con = mysqli_connect(
    "localhost",
    "root",
    "",
    "transport_management"
);

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

$student_id = $_SESSION['student_id'];


/* ==========================================
   GET UNPAID TRANSPORT REGISTRATIONS
   ========================================== */

$sql = "SELECT
            tr.registration_id,
            tr.registration_date,
            tr.start_date,
            tr.end_date,
            tr.status,

            r.route_name,
            r.starting_point,
            r.destination,

            b.bus_number

        FROM transport_registration tr

        INNER JOIN route r
            ON tr.route_id = r.route_id

        LEFT JOIN bus b
            ON tr.bus_id = b.bus_id

        LEFT JOIN payment p
            ON tr.registration_id = p.registration_id

        WHERE tr.student_id = ?
        AND p.payment_id IS NULL

        ORDER BY tr.registration_id DESC";


$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $student_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Payment - College Transport Management System
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }


        /* ==========================================
           HEADER
           ========================================== */

        .header {
            background-color: white;
            text-align: center;
            padding: 25px 20px;
            border-bottom: 1px solid #ddd;
        }


        .header h1 {
            margin: 0 0 12px 0;
            color: red;
            font-size: 34px;
        }


        .header h2 {
            margin: 0;
            color: #222;
            font-size: 24px;
        }


        /* ==========================================
           NAVIGATION
           ========================================== */

        .navbar {
            background-color: #990000;
            padding: 12px 20px;
            text-align: center;
        }


        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 15px;
        }


        .navbar a:hover {
            text-decoration: underline;
        }


        /* ==========================================
           MAIN CONTAINER
           ========================================== */

        .container {
            width: 95%;
            max-width: 900px;
            margin: 35px auto;
        }


        .title-box {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.12);
            text-align: center;
            margin-bottom: 25px;
        }


        .title-box h2 {
            color: #990000;
            margin: 0 0 10px 0;
        }


        .title-box p {
            color: #555;
            margin: 0;
        }


        /* ==========================================
           PAYMENT FORM
           ========================================== */

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.12);
        }


        .student-info {
            background-color: #f9f9f9;
            border-left: 4px solid #990000;
            padding: 15px;
            margin-bottom: 25px;
            line-height: 1.8;
        }


        .note {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
            line-height: 1.6;
            color: #555;
        }


        .form-group {
            margin-bottom: 20px;
        }


        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }


        select,
        input[type="number"],
        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
            background-color: white;
        }


        select:focus,
        input:focus {
            outline: none;
            border-color: #990000;
        }


        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #990000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }


        .submit-btn:hover {
            background-color: #770000;
        }


        /* ==========================================
           MESSAGE
           ========================================== */

        .message {
            padding: 14px;
            margin-bottom: 20px;
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            border-radius: 5px;
        }


        /* ==========================================
           TRANSACTION FIELD
           ========================================== */

        #transaction_group {
            display: none;
        }


        /* ==========================================
           BACK BUTTON
           ========================================== */

        .back-btn {
            display: inline-block;
            margin-top: 25px;
            padding: 11px 22px;
            background-color: #990000;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }


        .back-btn:hover {
            background-color: #770000;
        }


        /* ==========================================
           FOOTER
           ========================================== */

        .footer {
            margin-top: 40px;
            background-color: #222;
            color: white;
            text-align: center;
            padding: 20px;
        }


        @media (max-width: 700px) {

            .header h1 {
                font-size: 25px;
            }


            .header h2 {
                font-size: 19px;
            }


            .form-container {
                padding: 20px;
            }

        }

    </style>

</head>


<body>


<!-- ==========================================
     HEADER
     ========================================== -->

<div class="header">

    <h1>
        INDIRA GANDHI NATIONAL OPEN UNIVERSITY (IGNOU)
    </h1>

    <h2>
        COLLEGE TRANSPORT MANAGEMENT SYSTEM
    </h2>

</div>


<!-- ==========================================
     NAVIGATION
     ========================================== -->

<div class="navbar">

    <a href="index.html">
        Home
    </a>

    <a href="student.php">
        Student Dashboard
    </a>

    <a href="strouteinfo.php">
        Routes
    </a>

    <a href="businfo.php">
        Buses
    </a>

</div>


<!-- ==========================================
     MAIN CONTENT
     ========================================== -->

<div class="container">


    <div class="title-box">

        <h2>
            Transport Payment
        </h2>

        <p>
            Make payment for your registered college transport service.
        </p>

    </div>


    <div class="form-container">


        <div class="student-info">

            <strong>Student Name:</strong>

            <?php
            echo htmlspecialchars($_SESSION['student_name']);
            ?>

            <br>

            <strong>Student ID:</strong>

            <?php
            echo htmlspecialchars($_SESSION['student_id']);
            ?>

        </div>


        <div class="note">

            <strong>Payment Information</strong>

            <br><br>

            Select your transport registration and enter the required
            payment details.

            For Online, UPI or Card payments, enter the transaction
            or reference number provided by the payment service.

        </div>


        <?php

        if (isset($_GET['error'])) {

            echo "
            <div class='message'>
                Please check all payment details and try again.
            </div>
            ";

        }

        ?>


        <?php

        if ($result && mysqli_num_rows($result) > 0) {

        ?>


        <form
            action="paymentdb.php"
            method="POST"
        >


            <div class="form-group">

                <label for="registration_id">
                    Select Transport Registration
                </label>


                <select
                    name="registration_id"
                    id="registration_id"
                    required
                >

                    <option value="">
                        -- Select Registration --
                    </option>


                    <?php

                    while ($row = mysqli_fetch_assoc($result)) {

                    ?>

                    <option
                        value="<?php echo htmlspecialchars($row['registration_id']); ?>"
                    >

                        <?php

                        echo htmlspecialchars(
                            "Registration ID: " .
                            $row['registration_id'] .
                            " | Route: " .
                            $row['route_name'] .
                            " | Bus: " .
                            ($row['bus_number'] ?? 'Not Assigned')
                        );

                        ?>

                    </option>

                    <?php

                    }

                    ?>

                </select>

            </div>


            <div class="form-group">

                <label for="amount">
                    Payment Amount
                </label>

                <input
                    type="number"
                    name="amount"
                    id="amount"
                    min="1"
                    step="0.01"
                    placeholder="Enter payment amount"
                    required
                >

            </div>


            <div class="form-group">

                <label for="payment_date">
                    Payment Date
                </label>

                <input
                    type="date"
                    name="payment_date"
                    id="payment_date"
                    value="<?php echo date('Y-m-d'); ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label for="payment_method">
                    Payment Method
                </label>


                <select
                    name="payment_method"
                    id="payment_method"
                    required
                >

                    <option value="">
                        -- Select Payment Method --
                    </option>

                    <option value="Cash">
                        Cash
                    </option>

                    <option value="Online">
                        Online
                    </option>

                    <option value="UPI">
                        UPI
                    </option>

                    <option value="Card">
                        Card
                    </option>

                    <option value="Other">
                        Other
                    </option>

                </select>

            </div>


            <div
                class="form-group"
                id="transaction_group"
            >

                <label for="transaction_id">
                    Transaction ID / Reference Number
                </label>

                <input
                    type="text"
                    name="transaction_id"
                    id="transaction_id"
                    maxlength="100"
                    placeholder="Enter transaction/reference ID"
                >

            </div>


            <div class="form-group">

                <input
                    type="submit"
                    value="SUBMIT PAYMENT"
                    class="submit-btn"
                >

            </div>


        </form>


        <?php

        } else {

        ?>

            <div class="message">

                No unpaid transport registration is available.

                <br><br>

                If you have already made a payment, you can view your
                payment details from the Student Dashboard.

            </div>

        <?php

        }

        ?>


    </div>


    <a href="student.php" class="back-btn">
        Back to Student Dashboard
    </a>


</div>


<!-- ==========================================
     FOOTER
     ========================================== -->

<div class="footer">

    © 2026 College Transport Management System. All Rights Reserved.

</div>


<script>

    const paymentMethod =
        document.getElementById("payment_method");

    const transactionGroup =
        document.getElementById("transaction_group");

    const transactionId =
        document.getElementById("transaction_id");


    if (paymentMethod) {

        paymentMethod.addEventListener(
            "change",
            function() {

                if (
                    this.value === "Online" ||
                    this.value === "UPI" ||
                    this.value === "Card"
                ) {

                    transactionGroup.style.display = "block";

                    transactionId.required = true;

                } else {

                    transactionGroup.style.display = "none";

                    transactionId.required = false;

                    transactionId.value = "";

                }

            }
        );

    }

</script>


</body>

</html>


<?php

mysqli_close($con);

?>