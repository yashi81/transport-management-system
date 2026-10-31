<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: log.php");
    exit;
}

$con = mysqli_connect(
    "localhost",
    "root",
    "",
    "transport_management"
);

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

$student_id = $_SESSION['student_id'];

$old_password = $_POST['old_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';


/* Check required fields */

if (
    empty($old_password) ||
    empty($new_password) ||
    empty($confirm_password)
) {
    mysqli_close($con);
    header("Location: stchangepass.php?error=1");
    exit;
}


/* Check whether new passwords match */

if ($new_password !== $confirm_password) {
    mysqli_close($con);
    header("Location: stchangepass.php?error=1");
    exit;
}


/* Get current password */

$sql = "SELECT password
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

mysqli_stmt_close($stmt);


if (!$student) {
    mysqli_close($con);
    header("Location: stchangepass.php?error=1");
    exit;
}


/* Verify old password */

if (!password_verify($old_password, $student['password'])) {
    mysqli_close($con);
    header("Location: stchangepass.php?error=1");
    exit;
}


/* Hash new password */

$new_hashed_password =
    password_hash($new_password, PASSWORD_DEFAULT);


/* Update password */

$update_sql = "UPDATE student
               SET password = ?
               WHERE student_id = ?";

$update_stmt =
    mysqli_prepare($con, $update_sql);

mysqli_stmt_bind_param(
    $update_stmt,
    "si",
    $new_hashed_password,
    $student_id
);


if (mysqli_stmt_execute($update_stmt)) {

    mysqli_stmt_close($update_stmt);
    mysqli_close($con);

    header("Location: stchangepass.php?success=1");
    exit;

}


mysqli_stmt_close($update_stmt);
mysqli_close($con);

header("Location: stchangepass.php?error=1");
exit;

?>