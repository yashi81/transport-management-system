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
   GET STUDENT REGISTRATION DETAILS
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

            b.bus_number,
            b.bus_type

        FROM transport_registration tr

        INNER JOIN route r
            ON tr.route_id = r.route_id

        LEFT JOIN bus b
            ON tr.bus_id = b.bus_id

        WHERE tr.student_id = ?

        ORDER BY tr.registration_id DESC";


$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $student_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Registration Details - College Transport Management System
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
            max-width: 1250px;
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
           REGISTRATION TABLE
           ========================================== */

        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.12);
            overflow-x: auto;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }


        th {
            background-color: #990000;
            color: white;
            padding: 14px;
            text-align: center;
        }


        td {
            padding: 13px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }


        tr:hover {
            background-color: #f9f9f9;
        }


        /* ==========================================
           STATUS
           ========================================== */

        .pending {
            color: #d68910;
            font-weight: bold;
        }


        .approved {
            color: green;
            font-weight: bold;
        }


        .active {
            color: green;
            font-weight: bold;
        }


        .rejected {
            color: #c62828;
            font-weight: bold;
        }


        .expired {
            color: #666;
            font-weight: bold;
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


            th,
            td {
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
            My Registration Details
        </h2>

        <p>
            View your college transport registration information and status.
        </p>

    </div>


    <div class="table-container">

        <table>

            <thead>

                <tr>

                    <th>
                        Registration ID
                    </th>

                    <th>
                        Route
                    </th>

                    <th>
                        Starting Point
                    </th>

                    <th>
                        Destination
                    </th>

                    <th>
                        Bus
                    </th>

                    <th>
                        Registration Date
                    </th>

                    <th>
                        Start Date
                    </th>

                    <th>
                        End Date
                    </th>

                    <th>
                        Status
                    </th>

                </tr>

            </thead>


            <tbody>

            <?php

            if (mysqli_num_rows($result) > 0) {

                while ($row = mysqli_fetch_assoc($result)) {

                    $status_class = strtolower(
                        $row['status']
                    );

            ?>

                <tr>

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
                            $row['starting_point']
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $row['destination']
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
                        <?php
                        echo htmlspecialchars(
                            $row['registration_date']
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $row['start_date'] ?? ''
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $row['end_date'] ?? ''
                        );
                        ?>
                    </td>


                    <td class="<?php echo htmlspecialchars($status_class); ?>">

                        <?php
                        echo htmlspecialchars(
                            $row['status']
                        );
                        ?>

                    </td>

                </tr>

            <?php

                }

            } else {

            ?>

                <tr>

                    <td colspan="9">
                        No transport registration found.
                    </td>

                </tr>

            <?php

            }

            ?>

            </tbody>

        </table>

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


</body>

</html>


<?php

mysqli_stmt_close($stmt);

mysqli_close($con);

?>