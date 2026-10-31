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

$message = "";


/* =========================================================
   ADD BUS
   ========================================================= */

if (isset($_POST['add_bus'])) {

    $bus_number = trim($_POST['bus_number']);
    $bus_type = trim($_POST['bus_type']);
    $capacity = $_POST['capacity'];
    $driver_id = $_POST['driver_id'];
    $status = $_POST['status'];

    if (
        empty($bus_number) ||
        empty($bus_type) ||
        empty($capacity) ||
        empty($driver_id)
    ) {

        $message = "Please fill in all required fields.";

    } else {

        $sql = "INSERT INTO bus
                (bus_number, bus_type, capacity, driver_id, status)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($con, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssiis",
            $bus_number,
            $bus_type,
            $capacity,
            $driver_id,
            $status
        );

        if (mysqli_stmt_execute($stmt)) {

            $message = "Bus added successfully.";

        } else {

            if (mysqli_errno($con) == 1062) {
                $message = "Bus number already exists.";
            } else {
                $message = "Error: " . mysqli_error($con);
            }
        }

        mysqli_stmt_close($stmt);
    }
}


/* =========================================================
   DELETE BUS
   ========================================================= */

if (isset($_GET['delete'])) {

    $bus_id = (int) $_GET['delete'];

    /*
       First check whether this bus is being used
       in a transport registration or trip.
    */

    $check_registration = mysqli_prepare(
        $con,
        "SELECT COUNT(*) AS total
         FROM transport_registration
         WHERE bus_id = ?"
    );

    mysqli_stmt_bind_param(
        $check_registration,
        "i",
        $bus_id
    );

    mysqli_stmt_execute($check_registration);

    $registration_result =
        mysqli_stmt_get_result($check_registration);

    $registration_data =
        mysqli_fetch_assoc($registration_result);

    mysqli_stmt_close($check_registration);


    $check_trip = mysqli_prepare(
        $con,
        "SELECT COUNT(*) AS total
         FROM trip
         WHERE bus_id = ?"
    );

    mysqli_stmt_bind_param(
        $check_trip,
        "i",
        $bus_id
    );

    mysqli_stmt_execute($check_trip);

    $trip_result =
        mysqli_stmt_get_result($check_trip);

    $trip_data =
        mysqli_fetch_assoc($trip_result);

    mysqli_stmt_close($check_trip);


    if (
        $registration_data['total'] > 0 ||
        $trip_data['total'] > 0
    ) {

        $message =
            "This bus cannot be deleted because it is already used in a transport registration or trip.";

    } else {

        $delete_sql =
            "DELETE FROM bus
             WHERE bus_id = ?";

        $delete_stmt =
            mysqli_prepare($con, $delete_sql);

        mysqli_stmt_bind_param(
            $delete_stmt,
            "i",
            $bus_id
        );

        if (mysqli_stmt_execute($delete_stmt)) {

            $message = "Bus deleted successfully.";

        } else {

            $message =
                "Unable to delete this bus.";

        }

        mysqli_stmt_close($delete_stmt);
    }
}


/* =========================================================
   UPDATE BUS
   ========================================================= */

if (isset($_POST['update_bus'])) {

    $bus_id = $_POST['bus_id'];
    $bus_number = trim($_POST['bus_number']);
    $bus_type = trim($_POST['bus_type']);
    $capacity = $_POST['capacity'];
    $driver_id = $_POST['driver_id'];
    $status = $_POST['status'];

    if (
        empty($bus_id) ||
        empty($bus_number) ||
        empty($bus_type) ||
        empty($capacity) ||
        empty($driver_id)
    ) {

        $message = "Please fill in all required fields.";

    } else {

        $sql = "UPDATE bus
                SET bus_number = ?,
                    bus_type = ?,
                    capacity = ?,
                    driver_id = ?,
                    status = ?
                WHERE bus_id = ?";

        $stmt = mysqli_prepare($con, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssiisi",
            $bus_number,
            $bus_type,
            $capacity,
            $driver_id,
            $status,
            $bus_id
        );

        if (mysqli_stmt_execute($stmt)) {

            $message = "Bus updated successfully.";

        } else {

            if (mysqli_errno($con) == 1062) {
                $message = "Bus number already exists.";
            } else {
                $message = "Error: " . mysqli_error($con);
            }
        }

        mysqli_stmt_close($stmt);
    }
}


/* =========================================================
   GET BUS FOR EDIT
   ========================================================= */

$edit_bus = null;

if (isset($_GET['edit'])) {

    $edit_id = (int) $_GET['edit'];

    $edit_sql = "SELECT
                    bus_id,
                    bus_number,
                    bus_type,
                    capacity,
                    driver_id,
                    status
                 FROM bus
                 WHERE bus_id = ?";

    $edit_stmt =
        mysqli_prepare($con, $edit_sql);

    mysqli_stmt_bind_param(
        $edit_stmt,
        "i",
        $edit_id
    );

    mysqli_stmt_execute($edit_stmt);

    $edit_result =
        mysqli_stmt_get_result($edit_stmt);

    $edit_bus =
        mysqli_fetch_assoc($edit_result);

    mysqli_stmt_close($edit_stmt);
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Buses</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f4f7;
            color: #333;
        }

        .header {
            background: #222;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0 0 8px 0;
            font-size: 30px;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
        }

        .header p {
            margin: 8px 0 0 0;
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
            overflow-x: auto;
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
            font-family: Arial, sans-serif;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #333;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            min-width: 800px;
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

        tr:hover {
            background: #eeeeee;
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

        .action:hover {
            background: #222;
        }

        .delete {
            background: #a00000;
        }

        .delete:hover {
            background: #770000;
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

        .cancel:hover {
            background: #555;
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

        .back:hover {
            background: #555;
        }

        .message {
            color: green;
            font-weight: bold;
        }

        .error {
            color: #a00000;
            font-weight: bold;
        }

        @media (max-width: 700px) {

            .header h1 {
                font-size: 24px;
            }

            .header h2 {
                font-size: 19px;
            }

            .container {
                width: 95%;
            }

            .box {
                padding: 15px;
            }

        }

    </style>

</head>

<body>


<div class="header">

    <h1>College Transport Management System</h1>

    <h2>Manage Buses</h2>

</div>


<div class="container">


    <!-- =====================================================
         MESSAGE
         ===================================================== -->

    <?php

    if ($message != "") {

        echo "<div class='box'>";

        /*
           Messages containing these words are treated
           as error messages.
        */

        if (
            strpos($message, "cannot") !== false ||
            strpos($message, "Error") !== false ||
            strpos($message, "Unable") !== false ||
            strpos($message, "already exists") !== false
        ) {

            echo "<p class='error'>"
                . htmlspecialchars($message)
                . "</p>";

        } else {

            echo "<p class='message'>"
                . htmlspecialchars($message)
                . "</p>";
        }

        echo "</div>";
    }

    ?>


    <!-- =====================================================
         ADD / EDIT BUS FORM
         ===================================================== -->

    <div class="box">

        <?php if ($edit_bus) { ?>

            <h2>Edit Bus</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="bus_id"
                    value="<?php echo $edit_bus['bus_id']; ?>"
                >

                <label>Bus Number</label>

                <input
                    type="text"
                    name="bus_number"
                    value="<?php echo htmlspecialchars($edit_bus['bus_number']); ?>"
                    required
                >


                <label>Bus Type</label>

                <input
                    type="text"
                    name="bus_type"
                    value="<?php echo htmlspecialchars($edit_bus['bus_type']); ?>"
                    required
                >


                <label>Capacity</label>

                <input
                    type="number"
                    name="capacity"
                    min="1"
                    value="<?php echo $edit_bus['capacity']; ?>"
                    required
                >


                <label>Driver</label>

                <select name="driver_id" required>

                    <option value="">
                        -- Select Driver --
                    </option>

                    <?php

                    /*
                       Show all drivers while editing.
                       This allows an already-assigned bus to
                       keep its current driver even if that
                       driver's status is not Active.
                    */

                    $drivers = mysqli_query(
                        $con,
                        "SELECT driver_id, driver_name, status
                         FROM driver
                         ORDER BY driver_name"
                    );

                    while ($driver = mysqli_fetch_assoc($drivers)) {

                        $selected = "";

                        if (
                            $driver['driver_id']
                            == $edit_bus['driver_id']
                        ) {

                            $selected = "selected";
                        }

                        echo "<option value='"
                            . $driver['driver_id']
                            . "' "
                            . $selected
                            . ">";

                        echo htmlspecialchars(
                            $driver['driver_name']
                        );

                        if ($driver['status'] != 'Active') {
                            echo " ("
                                . htmlspecialchars($driver['status'])
                                . ")";
                        }

                        echo "</option>";
                    }

                    ?>

                </select>


                <label>Status</label>

                <select name="status">

                    <option
                        value="Available"
                        <?php
                        if ($edit_bus['status'] == 'Available') {
                            echo "selected";
                        }
                        ?>
                    >
                        Available
                    </option>

                    <option
                        value="Maintenance"
                        <?php
                        if ($edit_bus['status'] == 'Maintenance') {
                            echo "selected";
                        }
                        ?>
                    >
                        Maintenance
                    </option>

                    <option
                        value="Inactive"
                        <?php
                        if ($edit_bus['status'] == 'Inactive') {
                            echo "selected";
                        }
                        ?>
                    >
                        Inactive
                    </option>

                </select>


                <button
                    type="submit"
                    name="update_bus"
                >
                    Update Bus
                </button>


                <a
                    href="manage_buses.php"
                    class="cancel"
                >
                    Cancel
                </a>

            </form>


        <?php } else { ?>


            <h2>Add New Bus</h2>

            <form method="POST">

                <label>Bus Number</label>

                <input
                    type="text"
                    name="bus_number"
                    required
                >


                <label>Bus Type</label>

                <input
                    type="text"
                    name="bus_type"
                    placeholder="College Bus"
                    required
                >


                <label>Capacity</label>

                <input
                    type="number"
                    name="capacity"
                    min="1"
                    required
                >


                <label>Driver</label>

                <select
                    name="driver_id"
                    required
                >

                    <option value="">
                        -- Select Driver --
                    </option>

                    <?php

                    /*
                       Only Active drivers are available
                       when adding a new bus.
                    */

                    $drivers = mysqli_query(
                        $con,
                        "SELECT driver_id, driver_name
                         FROM driver
                         WHERE status = 'Active'
                         ORDER BY driver_name"
                    );

                    while ($driver = mysqli_fetch_assoc($drivers)) {

                        echo "<option value='"
                            . $driver['driver_id']
                            . "'>"
                            . htmlspecialchars($driver['driver_name'])
                            . "</option>";
                    }

                    ?>

                </select>


                <label>Status</label>

                <select name="status">

                    <option value="Available">
                        Available
                    </option>

                    <option value="Maintenance">
                        Maintenance
                    </option>

                    <option value="Inactive">
                        Inactive
                    </option>

                </select>


                <button
                    type="submit"
                    name="add_bus"
                >
                    Add Bus
                </button>

            </form>


        <?php } ?>

    </div>


    <!-- =====================================================
         BUS LIST
         ===================================================== -->

    <div class="box">

        <h2>Bus List</h2>

        <table>

            <tr>

                <th>ID</th>

                <th>Bus Number</th>

                <th>Type</th>

                <th>Capacity</th>

                <th>Driver</th>

                <th>Status</th>

                <th>Action</th>

            </tr>


            <?php

            $buses = mysqli_query(
                $con,
                "SELECT
                    b.bus_id,
                    b.bus_number,
                    b.bus_type,
                    b.capacity,
                    d.driver_name,
                    b.status
                 FROM bus b
                 LEFT JOIN driver d
                 ON b.driver_id = d.driver_id
                 ORDER BY b.bus_id DESC"
            );


            while ($bus = mysqli_fetch_assoc($buses)) {

                echo "<tr>";


                echo "<td>"
                    . $bus['bus_id']
                    . "</td>";


                echo "<td>"
                    . htmlspecialchars($bus['bus_number'])
                    . "</td>";


                echo "<td>"
                    . htmlspecialchars($bus['bus_type'])
                    . "</td>";


                echo "<td>"
                    . $bus['capacity']
                    . "</td>";


                echo "<td>"
                    . htmlspecialchars(
                        $bus['driver_name']
                        ?? 'Not Assigned'
                    )
                    . "</td>";


                echo "<td>"
                    . htmlspecialchars($bus['status'])
                    . "</td>";


                echo "<td>";

                /*
                   EDIT BUTTON
                */

                echo "<a
                        href='manage_buses.php?edit="
                        . $bus['bus_id']
                        . "'
                        class='action'
                      >
                        Edit
                      </a>";


                /*
                   DELETE BUTTON
                */

                echo "<a
                        href='manage_buses.php?delete="
                        . $bus['bus_id']
                        . "'
                        class='action delete'
                        onclick=\"return confirm('Are you sure you want to delete this bus?');\"
                      >
                        Delete
                      </a>";


                echo "</td>";


                echo "</tr>";
            }

            ?>

        </table>

    </div>


    <a
        href="admin.php"
        class="back"
    >
        ← Back to Admin Dashboard
    </a>


</div>


</body>

</html>


<?php

mysqli_close($con);

?>