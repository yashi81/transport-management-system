<?php

session_start();


/* ==========================================
   CHECK WHETHER STUDENT IS LOGGED IN
   ========================================== */

if (!isset($_SESSION['student_id'])) {

    header("Location: log.php");

    exit;

}


/* ==========================================
   GET STUDENT DETAILS
   ========================================== */

$student_id = $_SESSION['student_id'];

$student_name = $_SESSION['student_name'] ?? 'Student';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard - College Transport Management System</title>


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

            display: flex;

            justify-content: space-between;

            align-items: center;

            flex-wrap: wrap;

        }


        .navbar-title {

            color: white;

            font-size: 20px;

            font-weight: bold;

        }


        .navbar-links a {

            color: white;

            text-decoration: none;

            margin-left: 20px;

            font-size: 15px;

        }


        .navbar-links a:hover {

            text-decoration: underline;

        }


        /* ==========================================
           MAIN CONTAINER
           ========================================== */

        .dashboard-container {

            width: 90%;

            max-width: 1100px;

            margin: 35px auto;

        }


        /* ==========================================
           WELCOME SECTION
           ========================================== */

        .welcome-box {

            background-color: white;

            padding: 25px;

            border-radius: 8px;

            box-shadow: 0 3px 12px rgba(0,0,0,0.12);

            margin-bottom: 30px;

        }


        .welcome-box h2 {

            margin-top: 0;

            color: #990000;

        }


        .welcome-box p {

            font-size: 16px;

            margin-bottom: 8px;

        }


        .student-id {

            font-weight: bold;

            color: #333;

        }


        /* ==========================================
           DASHBOARD CARDS
           ========================================== */

        .dashboard-grid {

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 20px;

        }


        .dashboard-card {

            background-color: white;

            padding: 25px;

            border-radius: 8px;

            box-shadow: 0 3px 12px rgba(0,0,0,0.12);

            text-align: center;

            min-height: 190px;

            display: flex;

            flex-direction: column;

            justify-content: space-between;

        }


        .dashboard-card h3 {

            color: #990000;

            margin-top: 0;

        }


        .dashboard-card p {

            color: #555;

            line-height: 1.5;

        }


        .dashboard-btn {

            display: inline-block;

            padding: 10px 18px;

            background-color: #990000;

            color: white;

            text-decoration: none;

            border-radius: 5px;

            margin-top: 12px;

        }


        .dashboard-btn:hover {

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


        /* ==========================================
           RESPONSIVE DESIGN
           ========================================== */

        @media (max-width: 900px) {

            .dashboard-grid {

                grid-template-columns: repeat(2, 1fr);

            }

        }


        @media (max-width: 600px) {

            .dashboard-grid {

                grid-template-columns: 1fr;

            }


            .header h1 {

                font-size: 25px;

            }


            .header h2 {

                font-size: 19px;

            }


            .navbar {

                flex-direction: column;

                gap: 10px;

            }


            .navbar-links a {

                margin: 0 8px;

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
     NAVIGATION BAR
     ========================================== -->

<div class="navbar">

    <div class="navbar-title">
        Student Dashboard
    </div>


    <div class="navbar-links">

        <a href="index.html">
            Home
        </a>

        <a href="logout.php">
            Logout
        </a>

    </div>

</div>



<!-- ==========================================
     DASHBOARD
     ========================================== -->

<div class="dashboard-container">


    <!-- WELCOME -->

    <div class="welcome-box">

        <h2>
            Welcome, <?php echo htmlspecialchars($student_name); ?>!
        </h2>

        <p>
            Welcome to the College Transport Management System.
        </p>

        <p class="student-id">

            Student ID:
            <?php echo htmlspecialchars($student_id); ?>

        </p>

        <p>
            Use the options below to manage and view your college transport services.
        </p>

    </div>



    <!-- DASHBOARD OPTIONS -->

    <div class="dashboard-grid">


        <!-- VIEW ROUTES -->

        <div class="dashboard-card">

            <div>

                <h3>
                    View Routes
                </h3>

                <p>
                    View available transport routes and route details.
                </p>

            </div>

            <a href="strouteinfo.php" class="dashboard-btn">
                View Routes
            </a>

        </div>



        <!-- VIEW BUSES -->

        <div class="dashboard-card">

            <div>

                <h3>
                    View Buses
                </h3>

                <p>
                    View available buses and their transport information.
                </p>

            </div>

            <a href="businfo.php" class="dashboard-btn">
                View Buses
            </a>

        </div>



        <!-- TRANSPORT REGISTRATION -->

        <div class="dashboard-card">

            <div>

                <h3>
                    Transport Registration
                </h3>

                <p>
                    Register for college transportation services.
                </p>

            </div>

            <a href="buspass.php" class="dashboard-btn">
                Register for Transport
            </a>

        </div>



        <!-- PAYMENT -->

        <div class="dashboard-card">

            <div>

                <h3>
                    Payment
                </h3>

                <p>
                    Make and manage your transport fee payments.
                </p>

            </div>

            <a href="payment.php" class="dashboard-btn">
                Payment
            </a>

        </div>



        <!-- REGISTRATION DETAILS -->

        <div class="dashboard-card">

            <div>

                <h3>
                    Registration Details
                </h3>

                <p>
                    View your transport registration details and status.
                </p>

            </div>

            <a href="registration_details.php" class="dashboard-btn">
                View Details
            </a>

        </div>



        <!-- PAYMENT RECEIPT -->

        <div class="dashboard-card">

            <div>

                <h3>
                    Payment Receipt
                </h3>

                <p>
                    View your transport payment receipt.
                </p>

            </div>

            <a href="paymentreceipt.php" class="dashboard-btn">
                View Receipt
            </a>

        </div>



        <!-- CHANGE PASSWORD -->

        <div class="dashboard-card">

            <div>

                <h3>
                    Change Password
                </h3>

                <p>
                    Change your student account password.
                </p>

            </div>

            <a href="stchangepass.php" class="dashboard-btn">
                Change Password
            </a>

        </div>



        <!-- LOGOUT -->

        <div class="dashboard-card">

            <div>

                <h3>
                    Logout
                </h3>

                <p>
                    Securely log out of your student account.
                </p>

            </div>

            <a href="logout.php" class="dashboard-btn">
                Logout
            </a>

        </div>


    </div>

</div>



<!-- ==========================================
     FOOTER
     ========================================== -->

<div class="footer">

    © 2026 College Transport Management System. All Rights Reserved.

</div>


</body>

</html>