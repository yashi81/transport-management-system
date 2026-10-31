<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: log.php");
    exit;
}

$message = "";
$error = "";

if (isset($_GET['success'])) {
    $message = "Password changed successfully.";
}

if (isset($_GET['error'])) {
    $error = "Old password is incorrect or the passwords do not match.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Change Password - College Transport Management System</title>


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
            max-width: 700px;
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
           FORM BOX
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


        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
        }


        input[type="password"]:focus {
            border-color: #990000;
            outline: none;
        }


        .btn {
            width: 100%;
            padding: 12px;
            background-color: #990000;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }


        .btn:hover {
            background-color: #770000;
        }


        /* ==========================================
           MESSAGES
           ========================================== */

        .success {
            background-color: #e8f5e9;
            color: green;
            border: 1px solid #b7dfb9;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }


        .error {
            background-color: #fdeaea;
            color: #b00000;
            border: 1px solid #e0aaaa;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
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


            .navbar a {
                display: inline-block;
                margin: 5px 8px;
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
            Change Password
        </h2>

        <p>
            Update your student account password securely.
        </p>

    </div>



    <div class="form-container">


        <?php

        if ($message != "") {

            echo "<div class='success'>" .
                 htmlspecialchars($message) .
                 "</div>";

        }


        if ($error != "") {

            echo "<div class='error'>" .
                 htmlspecialchars($error) .
                 "</div>";

        }

        ?>


        <form action="stchangepassdb.php" method="POST">


            <div class="form-group">

                <label for="old_password">
                    Old Password
                </label>

                <input
                    type="password"
                    id="old_password"
                    name="old_password"
                    placeholder="Enter your old password"
                    required
                >

            </div>



            <div class="form-group">

                <label for="new_password">
                    New Password
                </label>

                <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    placeholder="Enter your new password"
                    required
                >

            </div>



            <div class="form-group">

                <label for="confirm_password">
                    Confirm New Password
                </label>

                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Re-enter your new password"
                    required
                >

            </div>



            <div class="form-group">

                <input
                    type="submit"
                    value="UPDATE PASSWORD"
                    class="btn"
                >

            </div>


        </form>


        <a href="student.php" class="back-btn">
            Back to Student Dashboard
        </a>


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