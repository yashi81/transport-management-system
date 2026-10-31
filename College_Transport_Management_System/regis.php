<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Registration - College Transport Management System</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        /* HEADER */

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

        /* NAVIGATION */

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

        /* CONTAINER */

        .container {
            width: 95%;
            max-width: 750px;
            margin: 35px auto;
        }

        /* TITLE */

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

        /* FORM */

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
            color: #333;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"],
        select,
        textarea {

            width: 100%;

            padding: 12px;

            border: 1px solid #ccc;

            border-radius: 5px;

            font-size: 15px;

            font-family: Arial, sans-serif;

        }

        input:focus,
        select:focus,
        textarea:focus {

            border-color: #990000;

            outline: none;

        }

        textarea {
            resize: vertical;
        }

        .btn {

            width: 100%;

            padding: 12px;

            background-color: #990000;

            color: white;

            border: none;

            border-radius: 5px;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;

        }

        .btn:hover {
            background-color: #770000;
        }

        /* LOGIN LINK */

        .login-box {

            text-align: center;

            margin-top: 25px;

            padding-top: 20px;

            border-top: 1px solid #ddd;

        }

        .login-box a {

            color: #990000;

            font-weight: bold;

            text-decoration: none;

        }

        .login-box a:hover {
            text-decoration: underline;
        }

        /* BACK BUTTON */

        .back-btn {

            display: inline-block;

            margin-top: 20px;

            padding: 11px 22px;

            background-color: #990000;

            color: white;

            text-decoration: none;

            border-radius: 5px;

        }

        .back-btn:hover {
            background-color: #770000;
        }

        /* FOOTER */

        .footer {

            margin-top: 40px;

            background-color: #222;

            color: white;

            text-align: center;

            padding: 20px;

        }

        /* MOBILE */

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


<!-- HEADER -->

<div class="header">

    <h1>
        INDIRA GANDHI NATIONAL OPEN UNIVERSITY (IGNOU)
    </h1>

    <h2>
        COLLEGE TRANSPORT MANAGEMENT SYSTEM
    </h2>

</div>


<!-- NAVIGATION -->

<div class="navbar">

    <a href="index.html">
        Home
    </a>

    <a href="aboutus/Home.html">
        About Us
    </a>

    <a href="log.php">
        Student Login
    </a>

    <a href="strouteinfo.php">
        Routes
    </a>

    <a href="contactus/contect.html">
        Contact Us
    </a>

</div>


<!-- MAIN -->

<div class="container">


    <!-- TITLE -->

    <div class="title-box">

        <h2>
            Student Registration
        </h2>

        <p>
            Create your student account to access transport services.
        </p>

    </div>


    <!-- FORM -->

    <div class="form-container">

        <form action="rcon.php" method="POST">


            <div class="form-group">

                <label for="name">
                    Student Name
                </label>

                <input
                    type="text"
                    name="name"
                    id="name"
                    placeholder="Enter your full name"
                    required
                >

            </div>


            <div class="form-group">

                <label for="email">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Enter your email address"
                    required
                >

            </div>


            <div class="form-group">

                <label for="mobile">
                    Mobile Number
                </label>

                <input
                    type="tel"
                    name="mobile"
                    id="mobile"
                    placeholder="Enter your 10-digit mobile number"
                    pattern="[0-9]{10}"
                    maxlength="10"
                    required
                >

            </div>


            <div class="form-group">

                <label for="course">
                    Course
                </label>

                <select
                    name="course"
                    id="course"
                    required
                >

                    <option value="">
                        -- Select Course --
                    </option>

                    <option value="BCA">
                        BCA
                    </option>

                    <option value="MCA">
                        MCA
                    </option>

                    <option value="BBA">
                        BBA
                    </option>

                    <option value="MBA">
                        MBA
                    </option>

                    <option value="PGDCA">
                        PGDCA
                    </option>

                </select>

            </div>


            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Create a password"
                    required
                >

            </div>


            <div class="form-group">

                <label for="address">
                    Address
                </label>

                <textarea
                    name="address"
                    id="address"
                    rows="4"
                    placeholder="Enter your address"
                    required
                ></textarea>

            </div>


            <div class="form-group">

                <input
                    type="submit"
                    value="REGISTER"
                    class="btn"
                >

            </div>


        </form>


        <div class="login-box">

            Already registered?

            <a href="log.php">
                Student Login
            </a>

        </div>


        <a href="index.html" class="back-btn">
            Back to Home
        </a>


    </div>

</div>


<!-- FOOTER -->

<div class="footer">

    © 2026 College Transport Management System.
    All Rights Reserved.

</div>


</body>

</html>