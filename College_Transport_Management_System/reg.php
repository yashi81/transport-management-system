<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: regis.php");
    exit;
}

$student_id = $_SESSION['student_id'];

$con = mysqli_connect("localhost", "root", "", "transport_management");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

$sql = "SELECT student_id, full_name, email, mobile, course
        FROM student
        WHERE student_id = ?";

$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param($stmt, "i", $student_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$student = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);
mysqli_close($con);

if (!$student) {
    die("Student registration details could not be found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registration Successful - College Transport Management System</title>

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

        .success-container {
            width: 90%;
            max-width: 650px;
            margin: 40px auto;
        }

        .success-box {
            background-color: white;
            padding: 35px;
            border-radius: 8px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.15);
            text-align: center;
        }

        .success-box h2 {
            color: #198754;
            margin-bottom: 10px;
        }

        .success-box p {
            font-size: 16px;
        }

        .student-details {
            margin: 25px 0;
            text-align: left;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
        }

        .student-details p {
            margin: 12px 0;
        }

        .student-id {
            background-color: #fff3cd;
            border: 1px solid #ffe69c;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .student-id strong {
            font-size: 22px;
            color: #990000;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 8px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            background-color: #990000;
        }

        .btn:hover {
            background-color: #770000;
        }

        .btn-login {
            background-color: #198754;
        }

        .btn-login:hover {
            background-color: #146c43;
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


    <div class="success-container">

        <div class="success-box">

            <h2>Registration Successful!</h2>

            <p>
                Your student registration has been completed successfully.
            </p>


            <div class="student-id">

                <p>Your Student ID is:</p>

                <strong>
                    <?php echo htmlspecialchars($student['student_id']); ?>
                </strong>

                <p>
                    Please remember this ID for future reference.
                </p>

            </div>


            <div class="student-details">

                <p>
                    <strong>Student Name:</strong>
                    <?php echo htmlspecialchars($student['full_name']); ?>
                </p>

                <p>
                    <strong>Email:</strong>
                    <?php echo htmlspecialchars($student['email']); ?>
                </p>

                <p>
                    <strong>Mobile:</strong>
                    <?php echo htmlspecialchars($student['mobile']); ?>
                </p>

                <p>
                    <strong>Course:</strong>
                    <?php echo htmlspecialchars($student['course']); ?>
                </p>

            </div>


            <p>
                You can now log in to the Student Dashboard.
            </p>


            <a href="log.php" class="btn btn-login">
                Student Login
            </a>

            <a href="index.html" class="btn">
                Home
            </a>

        </div>

    </div>

</body>

</html>