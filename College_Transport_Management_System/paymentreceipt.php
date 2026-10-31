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

$payment_id = $_GET['payment_id'] ?? '';

$payment = null;
$payments_result = null;


/* ==========================================
   IF PAYMENT ID IS PROVIDED
   SHOW ONE DETAILED RECEIPT
   ========================================== */

if ($payment_id != "") {

    $sql = "SELECT
                p.payment_id,
                p.registration_id,
                p.amount,
                p.payment_date,
                p.payment_method,
                p.transaction_id,
                p.payment_status,

                s.student_id,
                s.full_name,
                s.email,
                s.mobile,

                tr.registration_date,
                tr.start_date,
                tr.end_date,

                r.route_name,
                r.starting_point,
                r.destination,

                b.bus_number

            FROM payment p

            INNER JOIN transport_registration tr
                ON p.registration_id = tr.registration_id

            INNER JOIN student s
                ON tr.student_id = s.student_id

            INNER JOIN route r
                ON tr.route_id = r.route_id

            LEFT JOIN bus b
                ON tr.bus_id = b.bus_id

            WHERE p.payment_id = ?
            AND tr.student_id = ?";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ii",
        $payment_id,
        $student_id
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $payment = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

}


/* ==========================================
   IF NO PAYMENT ID IS PROVIDED
   SHOW ALL PAYMENTS OF LOGGED-IN STUDENT
   ========================================== */

else {

    $sql = "SELECT
                p.payment_id,
                p.registration_id,
                p.amount,
                p.payment_date,
                p.payment_method,
                p.transaction_id,
                p.payment_status,

                r.route_name,
                r.starting_point,
                r.destination,

                b.bus_number

            FROM payment p

            INNER JOIN transport_registration tr
                ON p.registration_id = tr.registration_id

            INNER JOIN route r
                ON tr.route_id = r.route_id

            LEFT JOIN bus b
                ON tr.bus_id = b.bus_id

            WHERE tr.student_id = ?

            ORDER BY p.payment_id DESC";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $student_id
    );

    mysqli_stmt_execute($stmt);

    $payments_result = mysqli_stmt_get_result($stmt);

    mysqli_stmt_close($stmt);

}

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
    Payment Receipt - College Transport Management System
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
    max-width: 1100px;
    margin: 35px auto;
}


/* ==========================================
   TITLE BOX
   ========================================== */

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
   PAYMENT LIST
   ========================================== */

.list-container {
    background-color: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.12);
    overflow-x: auto;
}


.list-container h3 {
    color: #990000;
    margin-top: 0;
    margin-bottom: 20px;
}


.payment-table {
    width: 100%;
    border-collapse: collapse;
}


.payment-table th {
    background-color: #990000;
    color: white;
    padding: 12px;
    text-align: center;
}


.payment-table td {
    padding: 11px;
    border: 1px solid #ddd;
    text-align: center;
}


.payment-table tr:hover {
    background-color: #f9f9f9;
}


/* ==========================================
   STATUS
   ========================================== */

.paid {
    color: green;
    font-weight: bold;
}


.pending {
    color: #d68910;
    font-weight: bold;
}


.failed {
    color: #c62828;
    font-weight: bold;
}


/* ==========================================
   VIEW RECEIPT BUTTON
   ========================================== */

.view-btn {
    display: inline-block;
    padding: 8px 14px;
    background-color: #990000;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 14px;
}


.view-btn:hover {
    background-color: #770000;
}


/* ==========================================
   RECEIPT
   ========================================== */

.receipt {
    background-color: white;
    padding: 35px;
    border-radius: 8px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.12);
}


.receipt-title {
    text-align: center;
    border-bottom: 2px solid #990000;
    padding-bottom: 20px;
    margin-bottom: 25px;
}


.receipt-title h2 {
    color: #990000;
    margin: 0 0 8px 0;
}


.receipt-title p {
    color: #555;
    margin: 0;
}


/* ==========================================
   RECEIPT DETAILS
   ========================================== */

.section-title {
    background-color: #990000;
    color: white;
    padding: 10px 12px;
    margin-top: 20px;
    margin-bottom: 0;
    font-size: 17px;
}


.details-table {
    width: 100%;
    border-collapse: collapse;
}


.details-table td {
    padding: 12px;
    border: 1px solid #ddd;
}


.details-table td:first-child {
    width: 35%;
    font-weight: bold;
    background-color: #f9f9f9;
}


/* ==========================================
   BUTTONS
   ========================================== */

.button-area {
    text-align: center;
    margin-top: 30px;
}


.back-btn,
.print-btn {
    display: inline-block;
    padding: 11px 22px;
    margin: 5px;
    border-radius: 5px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-size: 15px;
}


.back-btn {
    background-color: #990000;
    color: white;
}


.back-btn:hover {
    background-color: #770000;
}


.print-btn {
    background-color: #222;
    color: white;
}


.print-btn:hover {
    background-color: #444;
}


/* ==========================================
   NO DATA / ERROR MESSAGE
   ========================================== */

.message {
    background-color: #fff3cd;
    border: 1px solid #ffeeba;
    color: #856404;
    padding: 15px;
    border-radius: 5px;
    text-align: center;
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


/* ==========================================
   PRINT
   ========================================== */

@media print {

    .navbar,
    .button-area,
    .footer {
        display: none;
    }

    body {
        background-color: white;
    }

    .container {
        width: 100%;
        margin: 0;
    }

    .receipt {
        box-shadow: none;
    }

}


/* ==========================================
   MOBILE
   ========================================== */

@media (max-width: 700px) {

    .header h1 {
        font-size: 25px;
    }


    .header h2 {
        font-size: 19px;
    }


    .list-container,
    .receipt {
        padding: 20px;
    }


    .payment-table {
        font-size: 13px;
    }


    .payment-table th,
    .payment-table td {
        padding: 8px;
    }


    .details-table td {
        padding: 9px;
        font-size: 13px;
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

    <a href="payment.php">
        Payment Details
    </a>

</div>


<div class="container">


<?php

/* ==================================================
   PART 1
   SHOW ALL PAYMENTS
   ================================================== */

if ($payment_id == "") {

?>


<div class="title-box">

    <h2>
        Payment Receipts
    </h2>

    <p>
        View all transport payments made by you.
    </p>

</div>


<div class="list-container">

    <h3>
        My Payment History
    </h3>


    <?php

    if (
        $payments_result &&
        mysqli_num_rows($payments_result) > 0
    ) {

    ?>


    <table class="payment-table">

        <tr>

            <th>
                Payment ID
            </th>

            <th>
                Registration ID
            </th>

            <th>
                Route
            </th>

            <th>
                Bus
            </th>

            <th>
                Amount
            </th>

            <th>
                Payment Date
            </th>

            <th>
                Payment Method
            </th>

            <th>
                Status
            </th>

            <th>
                Action
            </th>

        </tr>


        <?php

        while ($row = mysqli_fetch_assoc($payments_result)) {

        ?>


        <tr>

            <td>
                <?php
                echo htmlspecialchars(
                    $row['payment_id']
                );
                ?>
            </td>


            <td>
                <?php
                echo htmlspecialchars(
                    $row['registration_id']
                );
                ?>
            </td>


            <td>
                <?php
                echo htmlspecialchars(
                    $row['route_name']
                );
                ?>
            </td>


            <td>
                <?php
                echo htmlspecialchars(
                    $row['bus_number'] ??
                    'Not Assigned'
                );
                ?>
            </td>


            <td>
                ₹<?php
                echo htmlspecialchars(
                    number_format(
                        (float)$row['amount'],
                        2
                    )
                );
                ?>
            </td>


            <td>
                <?php
                echo htmlspecialchars(
                    $row['payment_date']
                );
                ?>
            </td>


            <td>
                <?php
                echo htmlspecialchars(
                    $row['payment_method']
                );
                ?>
            </td>


            <td
                class="<?php
                    echo strtolower(
                        $row['payment_status']
                    );
                ?>"
            >

                <?php
                echo htmlspecialchars(
                    $row['payment_status']
                );
                ?>

            </td>


            <td>

                <a
                    href="paymentreceipt.php?payment_id=<?php
                        echo urlencode(
                            $row['payment_id']
                        );
                    ?>"
                    class="view-btn"
                >
                    View Receipt
                </a>

            </td>

        </tr>


        <?php

        }

        ?>


    </table>


    <?php

    } else {

    ?>


    <div class="message">

        No payment records found for your account.

        <br><br>

        Once you make a transport payment,
        it will appear here.

    </div>


    <?php

    }

    ?>


</div>


<div style="text-align:center;">

    <a
        href="student.php"
        class="back-btn"
    >
        Back to Student Dashboard
    </a>

</div>


<?php

}


/* ==================================================
   PART 2
   SHOW INDIVIDUAL RECEIPT
   ================================================== */

else {

?>


<?php

if ($payment) {

?>


<div class="receipt">


    <div class="receipt-title">

        <h2>
            Transport Payment Receipt
        </h2>

        <p>
            College Transport Management System
        </p>

    </div>


    <!-- ==========================================
         STUDENT DETAILS
         ========================================== -->

    <div class="section-title">
        Student Details
    </div>


    <table class="details-table">

        <tr>

            <td>
                Student ID
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $payment['student_id']
                );
                ?>
            </td>

        </tr>


        <tr>

            <td>
                Student Name
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $payment['full_name']
                );
                ?>
            </td>

        </tr>


        <tr>

            <td>
                Email
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $payment['email']
                );
                ?>
            </td>

        </tr>


        <tr>

            <td>
                Mobile
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $payment['mobile']
                );
                ?>
            </td>

        </tr>

    </table>


    <!-- ==========================================
         TRANSPORT DETAILS
         ========================================== -->

    <div class="section-title">
        Transport Details
    </div>


    <table class="details-table">

        <tr>

            <td>
                Registration ID
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $payment['registration_id']
                );
                ?>
            </td>

        </tr>


        <tr>

            <td>
                Route
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $payment['route_name']
                );
                ?>
            </td>

        </tr>


        <tr>

            <td>
                Starting Point
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $payment['starting_point']
                );
                ?>
            </td>

        </tr>


        <tr>

            <td>
                Destination
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $payment['destination']
                );
                ?>
            </td>

        </tr>


        <tr>

            <td>
                Bus Number
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $payment['bus_number'] ??
                    'Not Assigned'
                );
                ?>
            </td>

        </tr>


        <tr>

            <td>
                Transport Start Date
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $payment['start_date'] ??
                    ''
                );
                ?>
            </td>

        </tr>


        <tr>

            <td>
                Transport End Date
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $payment['end_date'] ??
                    ''
                );
                ?>
            </td>

        </tr>

    </table>


    <!-- ==========================================
         PAYMENT DETAILS
         ========================================== -->

    <div class="section-title">
        Payment Details
    </div>


    <table class="details-table">

        <tr>

            <td>
                Payment ID
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $payment['payment_id']
                );
                ?>
            </td>

        </tr>


        <tr>

            <td>
                Amount
            </td>

            <td>
                ₹<?php
                echo htmlspecialchars(
                    number_format(
                        (float)$payment['amount'],
                        2
                    )
                );
                ?>
            </td>

        </tr>


        <tr>

            <td>
                Payment Date
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $payment['payment_date']
                );
                ?>
            </td>

        </tr>


        <tr>

            <td>
                Payment Method
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $payment['payment_method']
                );
                ?>
            </td>

        </tr>


        <tr>

            <td>
                Transaction ID
            </td>

            <td>
                <?php

                if (
                    !empty(
                        $payment['transaction_id']
                    )
                ) {

                    echo htmlspecialchars(
                        $payment['transaction_id']
                    );

                } else {

                    echo "Not Applicable";

                }

                ?>
            </td>

        </tr>


        <tr>

            <td>
                Payment Status
            </td>

            <td class="<?php
                echo strtolower(
                    $payment['payment_status']
                );
            ?>">

                <?php
                echo htmlspecialchars(
                    $payment['payment_status']
                );
                ?>

            </td>

        </tr>

    </table>


    <div class="button-area">

        <button
            onclick="window.print()"
            class="print-btn"
        >
            Print Receipt
        </button>


        <a
            href="paymentreceipt.php"
            class="back-btn"
        >
            Back to Payment History
        </a>


        <a
            href="student.php"
            class="back-btn"
        >
            Back to Student Dashboard
        </a>

    </div>


</div>


<?php

} else {

?>


<div class="receipt">

    <div class="message">

        Payment receipt could not be found.

        <br><br>

        The selected payment may not belong to your account.

    </div>


    <div class="button-area">

        <a
            href="paymentreceipt.php"
            class="back-btn"
        >
            Back to Payment History
        </a>


        <a
            href="student.php"
            class="back-btn"
        >
            Back to Student Dashboard
        </a>

    </div>

</div>


<?php

}

}

?>


</div>


<!-- ==========================================
     FOOTER
     ========================================== -->

<div class="footer">

    © 2026 College Transport Management System. All Rights Reserved.

</div>


</body>

</html>


<?php

mysqli_close($con);

?>