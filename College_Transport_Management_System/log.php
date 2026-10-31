<?php

session_start();

$error = "";

if (isset($_GET['error'])) {
    $error = "Invalid Student ID or Password.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Login - College Transport Management System</title>

    <style>

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .header {
            text-align: center;
            background-color: white;
            padding: 30px 20px;
        }

        .header h1 {
            color: red;
            margin: 0 0 15px 0;
            font-size: 36px;
        }

        .header h2 {
            margin: 0;
            color: #222;
        }

        .login-container {
            width: 90%;
            max-width: 500px;
            margin: 50px auto;
        }

        .login-box {
            background-color: white;
            padding: 35px;
            border-radius: 8px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.15);
        }

        .login-box h2 {
            text-align: center;
            color: #990000;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 15px;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: #990000;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-login:hover {
            background-color: #770000;
        }

        .error {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: #990000;
            text-decoration: none;
            font-weight: bold;
        }

        .home-link {
            text-align: center;
            margin-top: 15px;
        }

        .home-link a {
            color: #333;
            text-decoration: none;
        }

    </style>

</head>

<body>

    <div class="header">

        <h1>
            INDIRA GANDHI NATIONAL OPEN UNIVERSITY (IGNOU)
        </h1>

        <h2>
            COLLEGE TRANSPORT MANAGEMENT SYSTEM
        </h2>

    </div>


    <div class="login-container">

        <div class="login-box">

            <h2>Student Login</h2>


            <?php if ($error != "") { ?>

                <div class="error">
                    <?php echo $error; ?>
                </div>

            <?php } ?>


            <form action="checklog.php" method="POST">

                <div class="form-group">

                    <label for="student_id">
                        Student ID:
                    </label>

                    <input
                        type="number"
                        name="student_id"
                        id="student_id"
                        placeholder="Enter your Student ID"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="password">
                        Password:
                    </label>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Enter your password"
                        required
                    >

                </div>


                <div class="form-group">

                    <button
                        type="submit"
                        class="btn-login"
                    >
                        Login
                    </button>

                </div>

            </form>


            <div class="register-link">

                Don't have an account?

                <a href="regis.php">
                    Student Registration
                </a>

            </div>


            <div class="home-link">

                <a href="index.html">
                    Back to Home
                </a>

            </div>

        </div>

    </div>

</body>

</html>