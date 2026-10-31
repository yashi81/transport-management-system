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


/* Get logged-in student */
$student_id = $_SESSION['student_id'];


/* Get form values */
$route_id = $_POST['route_id'] ?? '';
$bus_id = $_POST['bus_id'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';


/* Check required fields */

if (
    empty($route_id) ||
    empty($bus_id) ||
    empty($start_date) ||
    empty($end_date)
) {

    mysqli_close($con);

    header("Location: buspass.php?error=1");
    exit;
}


/* Check date order */

if ($end_date < $start_date) {

    mysqli_close($con);

    header("Location: buspass.php?error=1");
    exit;
}


/* Check that route is active */

$route_sql = "SELECT route_id
              FROM route
              WHERE route_id = ?
              AND status = 'Active'";

$route_stmt = mysqli_prepare($con, $route_sql);

mysqli_stmt_bind_param($route_stmt, "i", $route_id);

mysqli_stmt_execute($route_stmt);

$route_result = mysqli_stmt_get_result($route_stmt);

$route = mysqli_fetch_assoc($route_result);

mysqli_stmt_close($route_stmt);


if (!$route) {

    mysqli_close($con);

    header("Location: buspass.php?error=1");
    exit;
}


/* Check that bus is available */

$bus_sql = "SELECT bus_id
            FROM bus
            WHERE bus_id = ?
            AND status = 'Available'";

$bus_stmt = mysqli_prepare($con, $bus_sql);

mysqli_stmt_bind_param($bus_stmt, "i", $bus_id);

mysqli_stmt_execute($bus_stmt);

$bus_result = mysqli_stmt_get_result($bus_stmt);

$bus = mysqli_fetch_assoc($bus_result);

mysqli_stmt_close($bus_stmt);


if (!$bus) {

    mysqli_close($con);

    header("Location: buspass.php?error=1");
    exit;
}


/* Insert transport registration */

$registration_date = date("Y-m-d");

$insert_sql = "INSERT INTO transport_registration
               (student_id, route_id, bus_id, registration_date, start_date, end_date, status)
               VALUES (?, ?, ?, ?, ?, ?, 'Pending')";

$insert_stmt = mysqli_prepare($con, $insert_sql);

mysqli_stmt_bind_param(
    $insert_stmt,
    "iiisss",
    $student_id,
    $route_id,
    $bus_id,
    $registration_date,
    $start_date,
    $end_date
);


if (mysqli_stmt_execute($insert_stmt)) {

    $registration_id = mysqli_insert_id($con);

    mysqli_stmt_close($insert_stmt);
    mysqli_close($con);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Transport Registration Successful</title>

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
            line-height: 1.8;
        }

        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 25px;
            background: #222;
            color: white;
            text-decoration: none;
            border-radius: 4px;
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
        Transport Registration Submitted Successfully
    </div>

    <div class="details">

        <strong>Registration ID:</strong>
        <?php echo htmlspecialchars($registration_id); ?>

        <br>

        <strong>Student ID:</strong>
        <?php echo htmlspecialchars($student_id); ?>

        <br>

        <strong>Start Date:</strong>
        <?php echo htmlspecialchars($start_date); ?>

        <br>

        <strong>End Date:</strong>
        <?php echo htmlspecialchars($end_date); ?>

        <br>

        <strong>Status:</strong>
        Pending

    </div>


    <p>
        Your transport registration has been submitted.
        The administrator can review and approve it.
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

    header("Location: buspass.php?error=1");
    exit;
}

?>