<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ad.php");
    exit;
}

$con = mysqli_connect("localhost", "root", "", "transport_management");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

$sql = "SELECT
            student_id,
            full_name,
            email,
            mobile,
            course,
            address,
            created_at
        FROM student
        ORDER BY student_id DESC";

$result = mysqli_query($con, $sql);

?>

<!DOCTYPE html>
<html>
<head>

    <title>Manage Students</title>

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

        .container {
            width: 95%;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #333;
            color: white;
        }

        tr:nth-child(even) {
            background: #f5f5f5;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 18px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

    </style>

</head>

<body>

<div class="header">

    <h1>College Transport Management System</h1>

    <p>Student Management</p>

</div>

<div class="container">

    <h2>Registered Students</h2>

    <table>

        <tr>

            <th>Student ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Course</th>
            <th>Address</th>
            <th>Registered On</th>

        </tr>

        <?php

        if (mysqli_num_rows($result) > 0) {

            while ($student = mysqli_fetch_assoc($result)) {

        ?>

        <tr>

            <td>
                <?php echo htmlspecialchars($student['student_id']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($student['full_name']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($student['email']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($student['mobile']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($student['course']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($student['address']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($student['created_at']); ?>
            </td>

        </tr>

        <?php

            }

        } else {

            echo '
            <tr>
                <td colspan="7" style="text-align:center;">
                    No students registered yet.
                </td>
            </tr>';

        }

        ?>

    </table>

    <a href="admin.php" class="back">
        ← Back to Dashboard
    </a>

</div>

</body>
</html>

<?php

mysqli_close($con);

?>