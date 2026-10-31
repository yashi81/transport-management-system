<?php

$con = mysqli_connect(
    "localhost",
    "root",
    "",
    "transport_management"
);

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}


$sql = "SELECT route_id, route_name, starting_point, destination, stops, distance, status
        FROM route
        WHERE status = 'Active'
        ORDER BY route_name ASC";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Unable to load route information.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Transport Routes - College Transport Management System</title>


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
            max-width: 1200px;
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
        }


        /* ==========================================
           ROUTE TABLE
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


        .active {
            color: green;
            font-weight: bold;
        }


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

    <a href="contactus\contect.html">
        Contact
    </a>

</div>



<!-- ==========================================
     MAIN CONTENT
     ========================================== -->

<div class="container">


    <div class="title-box">

        <h2>
            Available Transport Routes
        </h2>

        <p>
            View the routes currently available in the college transport system.
        </p>

    </div>



    <div class="table-container">

        <table>

            <thead>

                <tr>

                    <th>
                        Route ID
                    </th>

                    <th>
                        Route Name
                    </th>

                    <th>
                        Starting Point
                    </th>

                    <th>
                        Destination
                    </th>

                    <th>
                        Stops
                    </th>

                    <th>
                        Distance
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

            ?>

                <tr>

                    <td>
                        <?php echo htmlspecialchars($row['route_id']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['route_name']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['starting_point']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['destination']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['stops']); ?>
                    </td>

                    <td>

                        <?php

                        if ($row['distance'] !== null) {

                            echo htmlspecialchars($row['distance']) . " km";

                        } else {

                            echo "Not specified";

                        }

                        ?>

                    </td>

                    <td class="active">

                        <?php echo htmlspecialchars($row['status']); ?>

                    </td>

                </tr>

            <?php

                }

            } else {

            ?>

                <tr>

                    <td colspan="7">
                        No active routes are currently available.
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

mysqli_close($con);

?>