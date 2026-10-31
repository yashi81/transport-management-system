<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ad.php");
    exit;
}

$admin_name = $_SESSION['admin_name'];

?>

<!DOCTYPE html>
<html>
<head>

    <title>Admin Dashboard - College Transport Management System</title>

    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f4f7;
        }

        .header {
            background: #222;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 25px;
        }

        .header p {
            margin: 8px 0 0;
        }

        .nav {
            background: #444;
            padding: 12px;
            text-align: center;
        }

        .nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
        }

        .welcome {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .card h3 {
            margin-top: 0;
        }

        .card a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 18px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .card a:hover {
            background: #555;
        }

        .footer {
            margin-top: 40px;
            padding: 20px;
            background: #222;
            color: white;
            text-align: center;
        }

        @media(max-width: 800px) {
            .cards {
                grid-template-columns: 1fr;
            }
        }

    </style>

</head>

<body>

<div class="header">

    <h1>INDIRA GANDHI NATIONAL OPEN UNIVERSITY (IGNOU)</h1>

    <p>College Transport Management System</p>

</div>

<div class="nav">

    <a href="index.html">Home</a>

    <a href="admin.php">Dashboard</a>

    <a href="admin_logout.php">Logout</a>

</div>

<div class="container">

    <div class="welcome">

        <h2>Admin Dashboard</h2>

        <p>
            Welcome, <strong>
            <?php echo htmlspecialchars($admin_name); ?>
            </strong>
        </p>

        <p>
            Use the options below to manage the college transport system.
        </p>

    </div>

    <div class="cards">

        <div class="card">

            <h3>Students</h3>

            <p>View registered students.</p>

            <a href="admin_students.php">
                Manage Students
            </a>

        </div>


        <div class="card">

            <h3>Drivers</h3>

            <p>Manage transport drivers.</p>

            <a href="admin_drivers.php">
                Manage Drivers
            </a>

        </div>


        <div class="card">

            <h3>Buses</h3>

            <p>Manage college buses.</p>

            <a href="manage_buses.php">
                Manage Buses
            </a>

        </div>


        <div class="card">

            <h3>Routes</h3>

            <p>Manage transport routes.</p>

            <a href="manage_routes.php">
                Manage Routes
            </a>

        </div>


        <div class="card">

            <h3>Trips</h3>

            <p>Manage scheduled trips.</p>

            <a href="manage_trips.php">
                Manage Trips
            </a>

        </div>


        <div class="card">

            <h3>Transport Registrations</h3>

            <p>View student transport registrations.</p>

            <a href="manage_registrations.php">
                View Registrations
            </a>

        </div>


        <div class="card">

            <h3>Payments</h3>

            <p>View transport payments.</p>

            <a href="manage_payments.php">
                View Payments
            </a>

        </div>


        <div class="card">

            <h3>Reports</h3>

            <p>View system reports.</p>

            <a href="admin_reports.php">
                View Reports
            </a>

        </div>

    </div>

</div>

<div class="footer">

    © 2026 College Transport Management System

</div>

</body>
</html>