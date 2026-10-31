<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - College Transport Management System</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f4f7;
        }

        .container {
            width: 400px;
            margin: 100px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            color: #222;
        }

        h3 {
            text-align: center;
            color: #555;
            font-weight: normal;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 11px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background: #555;
        }

        .error {
            background: #ffe0e0;
            color: #c00000;
            padding: 10px;
            margin-top: 15px;
            text-align: center;
            border-radius: 5px;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #333;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>INDIRA GANDHI NATIONAL OPEN UNIVERSITY (IGNOU)</h2>

    <h3>College Transport Management System</h3>

    <h3>Admin Login</h3>

    <?php
    if (isset($_GET['error'])) {
        echo '<div class="error">Invalid username or password.</div>';
    }
    ?>

    <form action="admin_login.php" method="POST">

        <label for="username">Username</label>

        <input
            type="text"
            id="username"
            name="username"
            placeholder="Enter username"
            required
        >

        <label for="password">Password</label>

        <input
            type="password"
            id="password"
            name="password"
            placeholder="Enter password"
            required
        >

        <input type="submit" value="LOGIN">

    </form>

    <a href="index.html" class="back">← Back to Home</a>

</div>

</body>
</html>