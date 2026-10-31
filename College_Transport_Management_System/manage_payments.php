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


/* UPDATE PAYMENT STATUS */

if (isset($_POST['update_payment'])) {

    $payment_id = $_POST['payment_id'];
    $payment_status = $_POST['payment_status'];

    $sql = "UPDATE payment
            SET payment_status = ?
            WHERE payment_id = ?";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "si",
        $payment_status,
        $payment_id
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: manage_payments.php?updated=1");
    exit;
}


/* GET ALL PAYMENTS */

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

        ORDER BY p.payment_date DESC, p.payment_id DESC";

$result = mysqli_query($con, $sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Payments - College Transport Management System</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f4f7;
            color: #333;
        }

        .header {
            background: #222;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0 0 8px 0;
            font-size: 30px;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
        }

        .header p {
            margin: 8px 0 0 0;
        }

        .container {
            width: 95%;
            margin: 30px auto;
        }

        .box {
            background: white;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 8px;
            overflow-x: auto;
        }

        h2 {
            margin-top: 0;
        }

        label {
            display: block;
            margin-top: 12px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-family: Arial, sans-serif;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #333;
        }

        button {
            margin-top: 15px;
            padding: 10px 20px;
            background: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            min-width: 650px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #333;
            color: white;
        }

        tr:nth-child(even) {
            background: #f5f5f5;
        }

        tr:hover {
            background: #eeeeee;
        }

        .action {
            display: inline-block;
            padding: 6px 10px;
            margin: 2px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            background: #444;
        }

        .action:hover {
            background: #222;
        }

        .delete {
            background: #a00000;
        }

        .delete:hover {
            background: #770000;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 18px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back:hover {
            background: #555;
        }

        .message {
            color: green;
            font-weight: bold;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .amount {
            font-weight: bold;
        }

        .summary-table {
            min-width: 0;
        }

        a {
            color: #333;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 700px) {

            .header h1 {
                font-size: 24px;
            }

            .header h2 {
                font-size: 19px;
            }

            .container {
                width: 95%;
            }

            .box {
                padding: 15px;
            }
        }

    </style>

</head>

<body>

<div class="header">

    <h1>INDIRA GANDHI NATIONAL OPEN UNIVERSITY (IGNOU)</h1>

    <h2>COLLEGE TRANSPORT MANAGEMENT SYSTEM</h2>

    <h3>Admin - Manage Payments</h3>

</div>


<div class="container">
<?php

    if (isset($_GET['updated'])) {

        echo '<div class="success">
                Payment status updated successfully.
              </div>';
    }

    ?>


    <div class="box">

        <h2>Payment Records</h2>

        <table>

            <tr>

                <th>Payment ID</th>

                <th>Registration ID</th>

                <th>Student</th>

                <th>Route</th>

                <th>Bus</th>

                <th>Amount</th>

                <th>Payment Date</th>

                <th>Method</th>

                <th>Transaction ID</th>

                <th>Status</th>

                <th>Action</th>

            </tr>


            <?php

            if (mysqli_num_rows($result) > 0) {

                while ($payment = mysqli_fetch_assoc($result)) {

                    echo "<tr>";

                    echo "<td>"
                        . htmlspecialchars($payment['payment_id'])
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars($payment['registration_id'])
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $payment['student_id']
                            . ' - '
                            . $payment['full_name']
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $payment['route_name']
                            . ' - '
                            . $payment['starting_point']
                            . ' to '
                            . $payment['destination']
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $payment['bus_number'] ?? '-'
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars($payment['amount'])
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars($payment['payment_date'])
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars($payment['payment_method'])
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars(
                            $payment['transaction_id'] ?? '-'
                        )
                        . "</td>";

                    echo "<td>"
                        . htmlspecialchars($payment['payment_status'])
                        . "</td>";


                    echo "<td>";

                    echo '<form method="post">';

                    echo '<input type="hidden"
                                 name="payment_id"
                                 value="'
                         . htmlspecialchars($payment['payment_id'])
                         . '">';

                    echo '<select name="payment_status">';

                    $statuses = ['Pending', 'Paid', 'Failed'];

                    foreach ($statuses as $status) {

                        $selected =
                            ($payment['payment_status'] === $status)
                            ? 'selected'
                            : '';

                        echo '<option value="'
                             . $status
                             . '" '
                             . $selected
                             . '>'
                             . $status
                             . '</option>';
                    }

                    echo '</select>';

                    echo '<br>';

                    echo '<button
                            type="submit"
                            name="update_payment"
                            class="btn">
                            Update
                          </button>';

                    echo '</form>';

                    echo "</td>";

                    echo "</tr>";
                }

            } else {

                echo '<tr>
                        <td colspan="11">
                            No payment records found.
                        </td>
                      </tr>';
            }

            ?>

        </table>

    </div>

    <a href="admin.php" class="back">← Back to Admin Dashboard</a>

</div>

</body>

</html>

<?php
mysqli_close($con);
?>