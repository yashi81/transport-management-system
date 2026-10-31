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

/* DELETE DRIVER */

if (isset($_GET['delete'])) {

    $driver_id = (int) $_GET['delete'];

    $delete_sql = "DELETE FROM driver WHERE driver_id = ?";

    $stmt = mysqli_prepare($con, $delete_sql);

    mysqli_stmt_bind_param($stmt, "i", $driver_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    header("Location: admin_drivers.php");

    exit;
}


/* ADD DRIVER */

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['add_driver'])) {

    $driver_name = trim($_POST['driver_name']);
    $mobile = trim($_POST['mobile']);
    $license_number = trim($_POST['license_number']);
    $address = trim($_POST['address']);
    $status = $_POST['status'];

    $sql = "INSERT INTO driver
            (driver_name, mobile, license_number, address, status)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sssss",
        $driver_name,
        $mobile,
        $license_number,
        $address,
        $status
    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    header("Location: admin_drivers.php");

    exit;
}


/* UPDATE DRIVER */

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['update_driver'])) {

    $driver_id = (int) $_POST['driver_id'];

    $driver_name = trim($_POST['driver_name']);
    $mobile = trim($_POST['mobile']);
    $license_number = trim($_POST['license_number']);
    $address = trim($_POST['address']);
    $status = $_POST['status'];

    $sql = "UPDATE driver
            SET
                driver_name = ?,
                mobile = ?,
                license_number = ?,
                address = ?,
                status = ?
            WHERE driver_id = ?";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sssssi",
        $driver_name,
        $mobile,
        $license_number,
        $address,
        $status,
        $driver_id
    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    header("Location: admin_drivers.php");

    exit;
}


/* GET DRIVER FOR EDIT */

$edit_driver = null;

if (isset($_GET['edit'])) {

    $driver_id = (int) $_GET['edit'];

    $sql = "SELECT *
            FROM driver
            WHERE driver_id = ?";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param($stmt, "i", $driver_id);

    mysqli_stmt_execute($stmt);

    $result_edit = mysqli_stmt_get_result($stmt);

    $edit_driver = mysqli_fetch_assoc($result_edit);

    mysqli_stmt_close($stmt);
}


/* GET ALL DRIVERS */

$result = mysqli_query(
    $con,
    "SELECT *
     FROM driver
     ORDER BY driver_id DESC"
);

?>

<!DOCTYPE html>
<html>
<head>

    <title>Manage Drivers</title>

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
        }

        .box {
            background: white;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 8px;
        }

        h2 {
            margin-top: 0;
        }

        label {
            display: block;
            margin-top: 12px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            margin-top: 15px;
            padding: 10px 20px;
            background: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #555;
        }

        .cancel {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: #777;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            overflow-x: auto;
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

        .action {
            display: inline-block;
            padding: 6px 10px;
            margin: 2px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            background: #444;
        }

        .delete {
            background: #a00000;
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

    <p>Driver Management</p>

</div>

<div class="container">


    <!-- ADD / EDIT DRIVER -->

    <div class="box">

        <?php if ($edit_driver): ?>

            <h2>Edit Driver</h2>

            <form method="POST" action="admin_drivers.php">

                <input
                    type="hidden"
                    name="driver_id"
                    value="<?php echo $edit_driver['driver_id']; ?>"
                >

                <label>Driver Name</label>

                <input
                    type="text"
                    name="driver_name"
                    value="<?php echo htmlspecialchars($edit_driver['driver_name']); ?>"
                    required
                >

                <label>Mobile</label>

                <input
                    type="text"
                    name="mobile"
                    value="<?php echo htmlspecialchars($edit_driver['mobile']); ?>"
                    required
                >

                <label>License Number</label>

                <input
                    type="text"
                    name="license_number"
                    value="<?php echo htmlspecialchars($edit_driver['license_number']); ?>"
                    required
                >

                <label>Address</label>

                <textarea
                    name="address"
                    rows="3"
                ><?php echo htmlspecialchars($edit_driver['address'] ?? ''); ?></textarea>

                <label>Status</label>

                <select name="status">

                    <option value="Active"
                        <?php
                        if ($edit_driver['status'] == 'Active') {
                            echo 'selected';
                        }
                        ?>
                    >
                        Active
                    </option>

                    <option value="Inactive"
                        <?php
                        if ($edit_driver['status'] == 'Inactive') {
                            echo 'selected';
                        }
                        ?>
                    >
                        Inactive
                    </option>

                </select>

                <button type="submit" name="update_driver">
                    Update Driver
                </button>

                <a href="admin_drivers.php" class="cancel">
                    Cancel
                </a>

            </form>

        <?php else: ?>

            <h2>Add New Driver</h2>

            <form method="POST" action="admin_drivers.php">

                <label>Driver Name</label>

                <input
                    type="text"
                    name="driver_name"
                    placeholder="Enter driver name"
                    required
                >

                <label>Mobile</label>

                <input
                    type="text"
                    name="mobile"
                    placeholder="Enter mobile number"
                    required
                >

                <label>License Number</label>

                <input
                    type="text"
                    name="license_number"
                    placeholder="Enter license number"
                    required
                >

                <label>Address</label>

                <textarea
                    name="address"
                    rows="3"
                    placeholder="Enter address"
                ></textarea>

                <label>Status</label>

                <select name="status">

                    <option value="Active">Active</option>

                    <option value="Inactive">Inactive</option>

                </select>

                <button type="submit" name="add_driver">
                    Add Driver
                </button>

            </form>

        <?php endif; ?>

    </div>


    <!-- DRIVER LIST -->

    <div class="box">

        <h2>Driver List</h2>

        <table>

            <tr>

                <th>ID</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>License Number</th>
                <th>Address</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>

            </tr>

            <?php

            if (mysqli_num_rows($result) > 0) {

                while ($driver = mysqli_fetch_assoc($result)) {

            ?>

            <tr>

                <td>
                    <?php echo htmlspecialchars($driver['driver_id']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($driver['driver_name']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($driver['mobile']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($driver['license_number']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($driver['address'] ?? ''); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($driver['status']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($driver['created_at']); ?>
                </td>

                <td>

                    <a
                        href="admin_drivers.php?edit=<?php echo $driver['driver_id']; ?>"
                        class="action"
                    >
                        Edit
                    </a>

                    <a
                        href="admin_drivers.php?delete=<?php echo $driver['driver_id']; ?>"
                        class="action delete"
                        onclick="return confirm('Are you sure you want to delete this driver?');"
                    >
                        Delete
                    </a>

                </td>

            </tr>

            <?php

                }

            } else {

                echo '
                <tr>
                    <td colspan="8" style="text-align:center;">
                        No drivers found.
                    </td>
                </tr>';

            }

            ?>

        </table>

    </div>


    <a href="admin.php" class="back">
        ← Back to Dashboard
    </a>

</div>

</body>
</html>

<?php

mysqli_close($con);

?>