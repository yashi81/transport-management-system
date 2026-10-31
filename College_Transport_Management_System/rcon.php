<?php

session_start();

/* ==========================================
   DATABASE CONNECTION
   ========================================== */

$con = mysqli_connect("localhost", "root", "", "transport_management");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}


/* ==========================================
   GET FORM DATA
   ========================================== */

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$course = trim($_POST['course'] ?? '');
$password = $_POST['password'] ?? '';
$address = trim($_POST['address'] ?? '');


/* ==========================================
   BASIC VALIDATION
   ========================================== */

if (
    empty($name) ||
    empty($email) ||
    empty($mobile) ||
    empty($course) ||
    empty($password) ||
    empty($address)
) {
    die("Please fill in all required fields.");
}


/* ==========================================
   CHECK MOBILE NUMBER
   ========================================== */

if (!preg_match('/^[0-9]{10}$/', $mobile)) {
    die("Please enter a valid 10-digit mobile number.");
}


/* ==========================================
   CHECK WHETHER EMAIL ALREADY EXISTS
   ========================================== */

$check = mysqli_prepare(
    $con,
    "SELECT student_id FROM student WHERE email = ?"
);

mysqli_stmt_bind_param($check, "s", $email);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);

if (mysqli_stmt_num_rows($check) > 0) {
    mysqli_stmt_close($check);
    mysqli_close($con);

    die("This email address is already registered.");
}

mysqli_stmt_close($check);


/* ==========================================
   HASH PASSWORD
   ========================================== */

$hashed_password = password_hash($password, PASSWORD_DEFAULT);


/* ==========================================
   INSERT STUDENT
   ========================================== */

$sql = "INSERT INTO student
        (full_name, email, mobile, course, password, address)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssssss",
    $name,
    $email,
    $mobile,
    $course,
    $hashed_password,
    $address
);


/* ==========================================
   SAVE RECORD
   ========================================== */

if (mysqli_stmt_execute($stmt)) {

    $student_id = mysqli_insert_id($con);

    $_SESSION['student_id'] = $student_id;

    mysqli_stmt_close($stmt);
    mysqli_close($con);

    header("Location: reg.php");
    exit;

} else {

    echo "Registration failed. Please try again.";

    mysqli_stmt_close($stmt);
    mysqli_close($con);
}
?>