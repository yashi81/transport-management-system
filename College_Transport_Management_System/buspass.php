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
$message = "";


/* ==========================================
   SUBMIT TRANSPORT REGISTRATION
   ========================================== */

   if (isset($_POST['register_transport'])) {

    $route_id = $_POST['route_id'] ?? '';
    $bus_id = $_POST['bus_id'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    if (
        empty($route_id) ||
        empty($bus_id) ||
        empty($start_date) ||
        empty($end_date)
    ) {
        $message = "Please fill in all required fields.";
    } elseif ($end_date < $start_date) {
        $message = "End date cannot be earlier than start date.";
    } else {

        $registration_date = date("Y-m-d");

        $sql = "INSERT INTO transport_registration
                (student_id, route_id, bus_id, registration_date,
                 start_date, end_date, status)
                VALUES (?, ?, ?, ?, ?, ?, 'Pending')";

        $stmt = mysqli_prepare($con, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "iiisss",
            $student_id,
            $route_id,
            $bus_id,
            $registration_date,
            $start_date,
            $end_date
        );

        if (mysqli_stmt_execute($stmt)) {

            $message =
                "Transport registration submitted successfully. Your request is pending approval.";

        } else {

            $message =
                "Unable to submit transport registration.";

        }

        mysqli_stmt_close($stmt);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Transport Registration - College Transport Management System
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
           REGISTRATION FORM
           ========================================== */

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.12);
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
        input[type="date"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
            background-color: white;
        }


        select:focus,
        input[type="date"]:focus {
            outline: none;
            border-color: #990000;
        }


        .submit-btn {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background-color: #990000;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }


        .submit-btn:hover {
            background-color: #770000;
        }


        /* ==========================================
           MESSAGE
           ========================================== */

        .success-message {
            background-color: #e8f5e9;
            border: 1px solid #a5d6a7;
            color: #2e7d32;
            padding: 14px;
            border-radius: 5px;
            margin-bottom: 20px;
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
            Transport Registration
        </h2>

        <p>
            Apply for college transport by selecting your route, bus and
            transport period.
        </p>

    </div>


    <div class="form-container">


        <?php

        if ($message != "") {

            echo "<div class='success-message'>" .
                 htmlspecialchars($message) .
                 "</div>";

        }

        ?>


        <form method="POST">


            <div class="form-group">

                <label for="route_id">
                    Select Route
                </label>


                <select
                    name="route_id"
                    id="route_id"
                    required
                >

                    <option value="">
                        -- Select Route --
                    </option>


                    <?php

                    $routes = mysqli_query(
                        $con,
                        "SELECT route_id, route_name,
                                starting_point, destination
                         FROM route
                         WHERE status = 'Active'
                         ORDER BY route_name"
                    );


                    while ($route = mysqli_fetch_assoc($routes)) {

                        echo "<option value='" .
                             htmlspecialchars($route['route_id']) .
                             "'>" .
                             htmlspecialchars(
                                 $route['route_name'] .
                                 " - " .
                                 $route['starting_point'] .
                                 " to " .
                                 $route['destination']
                             ) .
                             "</option>";

                    }

                    ?>

                </select>

            </div>


            <div class="form-group">

                <label for="bus_id">
                    Select Bus
                </label>


                <select
                    name="bus_id"
                    id="bus_id"
                    required
                >

                    <option value="">
                        -- Select Bus --
                    </option>


                    <?php

                    $buses = mysqli_query(
                        $con,
                        "SELECT bus_id, bus_number,
                                bus_type, capacity
                         FROM bus
                         WHERE status = 'Available'
                         ORDER BY bus_number"
                    );


                    while ($bus = mysqli_fetch_assoc($buses)) {

                        echo "<option value='" .
                             htmlspecialchars($bus['bus_id']) .
                             "'>" .
                             htmlspecialchars(
                                 $bus['bus_number'] .
                                 " - " .
                                 $bus['bus_type'] .
                                 " - Capacity: " .
                                 $bus['capacity']
                             ) .
                             "</option>";

                    }

                    ?>

                </select>

            </div>


            <div class="form-group">

                <label for="start_date">
                    Transport Start Date
                </label>

                <input
                    type="date"
                    name="start_date"
                    id="start_date"
                    required
                >

            </div>


            <div class="form-group">

                <label for="end_date">
                    Transport End Date
                </label>

                <input
                    type="date"
                    name="end_date"
                    id="end_date"
                    required
                >

            </div>


            <button
                type="submit"
                name="register_transport"
                class="submit-btn"
            >
                SUBMIT REGISTRATION
            </button>


        </form>


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