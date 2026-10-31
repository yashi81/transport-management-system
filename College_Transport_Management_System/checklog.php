<?php

session_start();


/* ==========================================
   DATABASE CONNECTION
   ========================================== */

$con = mysqli_connect(
    "localhost",
    "root",
    "",
    "transport_management"
);

if (!$con) {

    die("Database connection failed: " . mysqli_connect_error());

}


/* ==========================================
   GET LOGIN DETAILS
   ========================================== */

$student_id = $_POST['student_id'] ?? '';
$password = $_POST['password'] ?? '';


/* ==========================================
   BASIC VALIDATION
   ========================================== */

if (empty($student_id) || empty($password)) {

    mysqli_close($con);

    header("Location: log.php?error=1");
    exit;

}


/* ==========================================
   FIND STUDENT
   ========================================== */

$sql = "SELECT student_id, full_name, password
        FROM student
        WHERE student_id = ?";

$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $student_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$student = mysqli_fetch_assoc($result);


/* ==========================================
   CHECK STUDENT AND PASSWORD
   ========================================== */

if (
    $student &&
    password_verify($password, $student['password'])
) {

    /* Login successful */

    $_SESSION['student_id'] = $student['student_id'];

    $_SESSION['student_name'] = $student['full_name'];

    mysqli_stmt_close($stmt);

    mysqli_close($con);

    header("Location: student.php");

    exit;

}


/* ==========================================
   LOGIN FAILED
   ========================================== */

mysqli_stmt_close($stmt);

mysqli_close($con);

header("Location: log.php?error=1");

exit;

?>