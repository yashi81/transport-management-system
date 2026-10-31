<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ad.php");
    exit;
}

$con = mysqli_connect("localhost", "root", "", "transport_management");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}


/* =========================
   SUMMARY COUNTS
   ========================= */

$student_count = mysqli_fetch_assoc(
    mysqli_query($con, "SELECT COUNT(*) AS total FROM student")
)['total'];

$driver_count = mysqli_fetch_assoc(
    mysqli_query($con, "SELECT COUNT(*) AS total FROM driver")
)['total'];

$bus_count = mysqli_fetch_assoc(
    mysqli_query($con, "SELECT COUNT(*) AS total FROM bus")
)['total'];

$route_count = mysqli_fetch_assoc(
    mysqli_query($con, "SELECT COUNT(*) AS total FROM route")
)['total'];

$trip_count = mysqli_fetch_assoc(
    mysqli_query($con, "SELECT COUNT(*) AS total FROM trip")
)['total'];

$registration_count = mysqli_fetch_assoc(
    mysqli_query($con, "SELECT COUNT(*) AS total FROM transport_registration")
)['total'];

$payment_count = mysqli_fetch_assoc(
    mysqli_query($con, "SELECT COUNT(*) AS total FROM payment")
)['total'];


/* =========================
   REGISTRATION STATUS
   ========================= */

$registration_status_sql = "
    SELECT status, COUNT(*) AS total
    FROM transport_registration
    GROUP BY status
    ORDER BY status
";

$registration_status_result =
    mysqli_query($con, $registration_status_sql);


/* =========================
   PAYMENT STATUS
   ========================= */

$payment_status_sql = "
    SELECT payment_status, COUNT(*) AS total
    FROM payment
    GROUP BY payment_status
    ORDER BY payment_status
";

$payment_status_result =
    mysqli_query($con, $payment_status_sql);


/* =========================
   TOTAL PAID AMOUNT
   ========================= */

$paid_amount_sql = "
    SELECT COALESCE(SUM(amount), 0) AS total
    FROM payment
    WHERE payment_status = 'Paid'
";

$paid_amount =
    mysqli_fetch_assoc(
        mysqli_query($con, $paid_amount_sql)
    )['total'];


/* =========================
   REGISTRATION REPORT
   ========================= */

$registration_report_sql = "
    SELECT
        tr.registration_id,
        s.student_id,
        s.full_name,
        r.route_name,
        r.starting_point,
        r.destination,
        b.bus_number,
        tr.registration_date,
        tr.start_date,
        tr.end_date,
        tr.status
    FROM transport_registration tr

    INNER JOIN student s
        ON tr.student_id = s.student_id

    INNER JOIN route r
        ON tr.route_id = r.route_id

    LEFT JOIN bus b
        ON tr.bus_id = b.bus_id

    ORDER BY tr.registration_id DESC
";

$registration_report =
    mysqli_query($con, $registration_report_sql);


/* =========================
   PAYMENT REPORT
   ========================= */

$payment_report_sql = "
    SELECT
        p.payment_id,
        p.registration_id,
        s.student_id,
        s.full_name,
        p.amount,
        p.payment_date,
        p.payment_method,
        p.transaction_id,
        p.payment_status
    FROM payment p

    INNER JOIN transport_registration tr
        ON p.registration_id = tr.registration_id

    INNER JOIN student s
        ON tr.student_id = s.student_id

    ORDER BY p.payment_id DESC
";

$payment_report =
    mysqli_query($con, $payment_report_sql);


/* =========================
   TRIP REPORT
   ========================= */

$trip_report_sql = "
    SELECT
        t.trip_id,
        b.bus_number,
        r.route_name,
        r.starting_point,
        r.destination,
        t.trip_date,
        t.departure_time,
        t.arrival_time,
        t.trip_type,
        t.status
    FROM trip t

    INNER JOIN bus b
        ON t.bus_id = b.bus_id

    INNER JOIN route r
        ON t.route_id = r.route_id

    ORDER BY t.trip_date DESC, t.departure_time ASC
";

$trip_report =
    mysqli_query($con, $trip_report_sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Reports - College Transport Management System</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .header {
            background: #222;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            width: 97%;
            margin: 25px auto;
        }

        .box {
            background: white;
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        h2 {
            margin-top: 0;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .card h3 {
            margin: 5px 0;
            font-size: 30px;
        }

        .card p {
            margin: 0;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 9px;
            text-align: center;
        }

        th {
            background: #222;
            color: white;
        }

        .summary-table {
            width: 50%;
        }

        .amount {
            font-weight: bold;
        }

        /* DASHBOARD BUTTON */

        .back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .back:hover {
            background: #222;
        }

        @media screen and (max-width: 900px) {

            .cards {
                grid-template-columns: repeat(2, 1fr);
            }

            table {
                font-size: 12px;
            }

            .summary-table {
                width: 100%;
            }

        }

    </style>

</head>

<body>


<div class="header">

    <h1>INDIRA GANDHI NATIONAL OPEN UNIVERSITY (IGNOU)</h1>

    <h2>COLLEGE TRANSPORT MANAGEMENT SYSTEM</h2>

    <h3>ADMIN REPORTS</h3>

</div>


<div class="container">


    <!-- =========================
         SUMMARY
         ========================= -->

    <div class="cards">

        <div class="card">
            <p>Total Students</p>
            <h3><?php echo $student_count; ?></h3>
        </div>

        <div class="card">
            <p>Total Drivers</p>
            <h3><?php echo $driver_count; ?></h3>
        </div>

        <div class="card">
            <p>Total Buses</p>
            <h3><?php echo $bus_count; ?></h3>
        </div>

        <div class="card">
            <p>Total Routes</p>
            <h3><?php echo $route_count; ?></h3>
        </div>

        <div class="card">
            <p>Total Trips</p>
            <h3><?php echo $trip_count; ?></h3>
        </div>

        <div class="card">
            <p>Total Registrations</p>
            <h3><?php echo $registration_count; ?></h3>
        </div>

        <div class="card">
            <p>Total Payments</p>
            <h3><?php echo $payment_count; ?></h3>
        </div>

        <div class="card">
            <p>Total Paid Amount</p>
            <h3>₹<?php echo number_format($paid_amount, 2); ?></h3>
        </div>

    </div>


    <!-- =========================
         REGISTRATION STATUS
         ========================= -->

    <div class="box">

        <h2>Transport Registration Summary</h2>

        <table class="summary-table">

            <tr>
                <th>Status</th>
                <th>Total</th>
            </tr>

            <?php

            if (mysqli_num_rows($registration_status_result) > 0) {

                while ($row =
                    mysqli_fetch_assoc($registration_status_result)) {

                    echo "<tr>";

                    echo "<td>"
                        . htmlspecialchars($row['status'])
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars($row['total'])
                        . "</td>";

                    echo "</tr>";
                }

            } else {

                echo "<tr>
                        <td colspan='2'>No registration data.</td>
                      </tr>";
            }

            ?>

        </table>

    </div>


    <!-- =========================
         PAYMENT STATUS
         ========================= -->

    <div class="box">

        <h2>Payment Summary</h2>

        <table class="summary-table">

            <tr>
                <th>Payment Status</th>
                <th>Total</th>
            </tr>

            <?php

            if (mysqli_num_rows($payment_status_result) > 0) {

                while ($row =
                    mysqli_fetch_assoc($payment_status_result)) {

                    echo "<tr>";

                    echo "<td>"
                        . htmlspecialchars($row['payment_status'])
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars($row['total'])
                        . "</td>";

                    echo "</tr>";
                }

            } else {

                echo "<tr>
                        <td colspan='2'>No payment data.</td>
                      </tr>";
            }

            ?>

        </table>

    </div>


    <!-- =========================
         REGISTRATION REPORT
         ========================= -->

    <div class="box">

        <h2>Transport Registration Report</h2>

        <table>

            <tr>

                <th>Registration ID</th>
                <th>Student</th>
                <th>Route</th>
                <th>Bus</th>
                <th>Registration Date</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>

            </tr>

            <?php

            if (mysqli_num_rows($registration_report) > 0) {

                while ($row =
                    mysqli_fetch_assoc($registration_report)) {

                    echo "<tr>";

                    echo "<td>"
                        . htmlspecialchars($row['registration_id'])
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $row['student_id']
                            . " - "
                            . $row['full_name']
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $row['route_name']
                            . " - "
                            . $row['starting_point']
                            . " to "
                            . $row['destination']
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $row['bus_number'] ?? '-'
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $row['registration_date']
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $row['start_date'] ?? '-'
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $row['end_date'] ?? '-'
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars($row['status'])
                        . "</td>";

                    echo "</tr>";
                }

            } else {

                echo "<tr>
                        <td colspan='8'>
                            No registration records found.
                        </td>
                      </tr>";
            }

            ?>

        </table>

    </div>


    <!-- =========================
         PAYMENT REPORT
         ========================= -->

    <div class="box">

        <h2>Payment Report</h2>

        <table>

            <tr>

                <th>Payment ID</th>
                <th>Registration ID</th>
                <th>Student</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Method</th>
                <th>Transaction ID</th>
                <th>Status</th>

            </tr>

            <?php

            if (mysqli_num_rows($payment_report) > 0) {

                while ($row =
                    mysqli_fetch_assoc($payment_report)) {

                    echo "<tr>";

                    echo "<td>"
                        . htmlspecialchars($row['payment_id'])
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $row['registration_id']
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $row['student_id']
                            . " - "
                            . $row['full_name']
                        )
                        . "</td>";

                    echo "<td class='amount'>₹"
                        . htmlspecialchars($row['amount'])
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $row['payment_date']
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $row['payment_method']
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $row['transaction_id'] ?? '-'
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $row['payment_status']
                        )
                        . "</td>";

                    echo "</tr>";
                }

            } else {

                echo "<tr>
                        <td colspan='8'>
                            No payment records found.
                        </td>
                      </tr>";
            }

            ?>

        </table>

    </div>


    <!-- =========================
         TRIP REPORT
         ========================= -->

    <div class="box">

        <h2>Trip Report</h2>

        <table>

            <tr>

                <th>Trip ID</th>
                <th>Bus</th>
                <th>Route</th>
                <th>Date</th>
                <th>Departure</th>
                <th>Arrival</th>
                <th>Type</th>
                <th>Status</th>

            </tr>

            <?php

            if (mysqli_num_rows($trip_report) > 0) {

                while ($row =
                    mysqli_fetch_assoc($trip_report)) {

                    echo "<tr>";

                    echo "<td>"
                        . htmlspecialchars($row['trip_id'])
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars($row['bus_number'])
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $row['route_name']
                            . " - "
                            . $row['starting_point']
                            . " to "
                            . $row['destination']
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars($row['trip_date'])
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $row['departure_time']
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $row['arrival_time'] ?? '-'
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars($row['trip_type'])
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars($row['status'])
                        . "</td>";

                    echo "</tr>";
                }

            } else {

                echo "<tr>
                        <td colspan='8'>
                            No trip records found.
                        </td>
                      </tr>";
            }

            ?>

        </table>

    </div>


    <!-- DASHBOARD BUTTON AT THE BOTTOM -->

    <a href="admin.php" class="back">
        ← Back to Admin Dashboard
    </a>


</div>

</body>

</html>

<?php
mysqli_close($con);
?>