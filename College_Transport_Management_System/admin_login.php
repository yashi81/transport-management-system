<?php

session_start();

$con = mysqli_connect("localhost", "root", "", "transport_management");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    mysqli_close($con);
    header("Location: ad.php?error=1");
    exit;
}

$sql = "SELECT admin_id, username, password, full_name
        FROM admin
        WHERE username = ?";

$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param($stmt, "s", $username);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$admin = mysqli_fetch_assoc($result);

if ($admin && password_verify($password, $admin['password'])) {

    $_SESSION['admin_id'] = $admin['admin_id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_name'] = $admin['full_name'];

    mysqli_stmt_close($stmt);
    mysqli_close($con);

    header("Location: admin.php");
    exit;
}

mysqli_stmt_close($stmt);
mysqli_close($con);

header("Location: ad.php?error=1");
exit;

?>