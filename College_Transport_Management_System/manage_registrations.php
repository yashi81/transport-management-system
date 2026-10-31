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

$message = "";

/* Approve Registration */
if (isset($_POST['approve'])) {

    $registration_id = $_POST['registration_id'];

    $sql = "UPDATE transport_registration
            SET status = 'Approved'
            WHERE registration_id = ?";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $registration_id);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Registration approved successfully.";
    } else {
        $message = "Error: " . mysqli_error($con);
    }

    mysqli_stmt_close($stmt);
}

/* Reject Registration */
if (isset($_POST['reject'])) {

    $registration_id = $_POST['registration_id'];

    $sql = "UPDATE transport_registration
            SET status = 'Rejected'
            WHERE registration_id = ?";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $registration_id);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Registration rejected.";
    } else {
        $message = "Error: " . mysqli_error($con);
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Transport Registrations</title>

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

    <h1>College Transport Management System</h1>
    <h2>Manage Transport Registrations</h2>

</div>

<div class="container">
<div class="box">

        <?php
        if ($message != "") {
            echo "<p class='message'>" .
                 htmlspecialchars($message) .
                 "</p>";
        }
        ?>

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
                <th>Action</th>
            </tr>

            <?php

            $sql = "SELECT
                        tr.registration_id,
                        s.full_name,
                        s.email,
                        r.route_name,
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

                    ORDER BY tr.registration_id DESC";

            $result = mysqli_query($con, $sql);

            while ($row = mysqli_fetch_assoc($result)) {

                echo "<tr>";

                echo "<td>" .
                     $row['registration_id'] .
                     "</td>";

                echo "<td>" .
                     htmlspecialchars($row['full_name']) .
                     "<br>" .
                     htmlspecialchars($row['email']) .
                     "</td>";

                echo "<td>" .
                     htmlspecialchars($row['route_name']) .
                     "</td>";

                echo "<td>" .
                     htmlspecialchars(
                         $row['bus_number'] ?? 'Not Assigned'
                     ) .
                     "</td>";

                echo "<td>" .
                     htmlspecialchars($row['registration_date']) .
                     "</td>";

                echo "<td>" .
                     htmlspecialchars($row['start_date'] ?? '') .
                     "</td>";

                echo "<td>" .
                     htmlspecialchars($row['end_date'] ?? '') .
                     "</td>";

                echo "<td>" .
                     htmlspecialchars($row['status']) .
                     "</td>";

                echo "<td>";

                if ($row['status'] == 'Pending') {

                    echo "
                    <form method='POST' style='display:inline;'>
                        <input type='hidden'
                               name='registration_id'
                               value='" . $row['registration_id'] . "'>

                        <button type='submit'
                                name='approve'
                                class='approve'>
                            Approve
                        </button>
                    </form>

                    <form method='POST' style='display:inline;'>
                        <input type='hidden'
                               name='registration_id'
                               value='" . $row['registration_id'] . "'>

                        <button type='submit'
                                name='reject'
                                class='reject'>
                            Reject
                        </button>
                    </form>
                    ";

                } else {

                    echo "No Action";

                }

                echo "</td>";

                echo "</tr>";
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