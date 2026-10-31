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
   ADD TRIP
   ========================================================= */

if (isset($_POST['add_trip'])) {

    $bus_id = $_POST['bus_id'];
    $route_id = $_POST['route_id'];
    $trip_date = $_POST['trip_date'];
    $departure_time = $_POST['departure_time'];
    $arrival_time =
        !empty($_POST['arrival_time'])
        ? $_POST['arrival_time']
        : NULL;

    $trip_type = $_POST['trip_type'];
    $status = $_POST['status'];


    $sql = "INSERT INTO trip
            (bus_id, route_id, trip_date, departure_time,
             arrival_time, trip_type, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "iisssss",
        $bus_id,
        $route_id,
        $trip_date,
        $departure_time,
        $arrival_time,
        $trip_type,
        $status
    );


    if (mysqli_stmt_execute($stmt)) {

        $message = "Trip added successfully.";

    } else {

        $message = "Error: " . mysqli_error($con);
    }

    mysqli_stmt_close($stmt);
}


/* =========================================================
   DELETE TRIP
   ========================================================= */

if (isset($_GET['delete'])) {

    $trip_id = (int) $_GET['delete'];


    $delete_sql =
        "DELETE FROM trip
         WHERE trip_id = ?";

    $delete_stmt =
        mysqli_prepare($con, $delete_sql);

    mysqli_stmt_bind_param(
        $delete_stmt,
        "i",
        $trip_id
    );


    if (mysqli_stmt_execute($delete_stmt)) {

        $message = "Trip deleted successfully.";

    } else {

        $message = "Unable to delete this trip.";
    }

    mysqli_stmt_close($delete_stmt);
}


/* =========================================================
   UPDATE TRIP
   ========================================================= */

if (isset($_POST['update_trip'])) {

    $trip_id = $_POST['trip_id'];
    $bus_id = $_POST['bus_id'];
    $route_id = $_POST['route_id'];
    $trip_date = $_POST['trip_date'];
    $departure_time = $_POST['departure_time'];

    $arrival_time =
        !empty($_POST['arrival_time'])
        ? $_POST['arrival_time']
        : NULL;

    $trip_type = $_POST['trip_type'];
    $status = $_POST['status'];


    if (
        empty($trip_id) ||
        empty($bus_id) ||
        empty($route_id) ||
        empty($trip_date) ||
        empty($departure_time) ||
        empty($trip_type) ||
        empty($status)
    ) {

        $message = "Please fill in all required fields.";

    } else {

        $sql = "UPDATE trip
                SET bus_id = ?,
                    route_id = ?,
                    trip_date = ?,
                    departure_time = ?,
                    arrival_time = ?,
                    trip_type = ?,
                    status = ?
                WHERE trip_id = ?";

        $stmt = mysqli_prepare($con, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "iisssssi",
            $bus_id,
            $route_id,
            $trip_date,
            $departure_time,
            $arrival_time,
            $trip_type,
            $status,
            $trip_id
        );


        if (mysqli_stmt_execute($stmt)) {

            $message = "Trip updated successfully.";

        } else {

            $message = "Error: " . mysqli_error($con);
        }

        mysqli_stmt_close($stmt);
    }
}


/* =========================================================
   GET TRIP FOR EDIT
   ========================================================= */

$edit_trip = null;

if (isset($_GET['edit'])) {

    $edit_id = (int) $_GET['edit'];


    $edit_sql = "SELECT
                    trip_id,
                    bus_id,
                    route_id,
                    trip_date,
                    departure_time,
                    arrival_time,
                    trip_type,
                    status
                 FROM trip
                 WHERE trip_id = ?";


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

    $edit_trip =
        mysqli_fetch_assoc($edit_result);

    mysqli_stmt_close($edit_stmt);
}


/* =========================================================
   GET BUSES
   ========================================================= */

/*
   When adding a trip:
   only Available buses are shown.

   When editing:
   the currently assigned bus is also shown even if
   its status is not Available.
*/

if ($edit_trip) {

    $bus_sql = "SELECT
                    bus_id,
                    bus_number,
                    status
                FROM bus
                WHERE status = 'Available'
                   OR bus_id = ?
                ORDER BY bus_number ASC";

    $bus_stmt =
        mysqli_prepare($con, $bus_sql);

    mysqli_stmt_bind_param(
        $bus_stmt,
        "i",
        $edit_trip['bus_id']
    );

    mysqli_stmt_execute($bus_stmt);

    $bus_result =
        mysqli_stmt_get_result($bus_stmt);

} else {

    $bus_sql = "SELECT
                    bus_id,
                    bus_number,
                    status
                FROM bus
                WHERE status = 'Available'
                ORDER BY bus_number ASC";

    $bus_result =
        mysqli_query($con, $bus_sql);
}


/* =========================================================
   GET ROUTES
   ========================================================= */

/*
   When adding a trip:
   only Active routes are shown.

   When editing:
   the currently assigned route is also shown even if
   its status is Inactive.
*/

if ($edit_trip) {

    $route_sql = "SELECT
                    route_id,
                    route_name,
                    starting_point,
                    destination,
                    status
                  FROM route
                  WHERE status = 'Active'
                     OR route_id = ?
                  ORDER BY route_name ASC";

    $route_stmt =
        mysqli_prepare($con, $route_sql);

    mysqli_stmt_bind_param(
        $route_stmt,
        "i",
        $edit_trip['route_id']
    );

    mysqli_stmt_execute($route_stmt);

    $route_result =
        mysqli_stmt_get_result($route_stmt);

} else {

    $route_sql = "SELECT
                    route_id,
                    route_name,
                    starting_point,
                    destination
                  FROM route
                  WHERE status = 'Active'
                  ORDER BY route_name ASC";

    $route_result =
        mysqli_query($con, $route_sql);
}


/* =========================================================
   GET ALL TRIPS
   ========================================================= */

$trip_sql = "SELECT
                t.trip_id,
                t.trip_date,
                t.departure_time,
                t.arrival_time,
                t.trip_type,
                t.status,
                b.bus_number,
                r.route_name,
                r.starting_point,
                r.destination
             FROM trip t

             INNER JOIN bus b
                ON t.bus_id = b.bus_id

             INNER JOIN route r
                ON t.route_id = r.route_id

             ORDER BY
                t.trip_date DESC,
                t.departure_time ASC";


$trip_result =
    mysqli_query($con, $trip_sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Trips - College Transport Management System</title>

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

        .header h3 {
            margin: 8px 0 0 0;
            font-size: 18px;
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
            min-width: 1000px;
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

    <h1>INDIRA GANDHI NATIONAL OPEN UNIVERSITY (IGNOU)</h1>

    <h2>COLLEGE TRANSPORT MANAGEMENT SYSTEM</h2>

    <h3>Admin - Manage Trips</h3>

</div>


<div class="container">


    <!-- =====================================================
         MESSAGE
         ===================================================== -->

    <?php

    if ($message != "") {

        echo "<div class='box'>";

        if (
            strpos($message, "Error") !== false ||
            strpos($message, "Unable") !== false ||
            strpos($message, "cannot") !== false
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
         ADD / EDIT TRIP
         ===================================================== -->

    <div class="box">


        <?php if ($edit_trip) { ?>

            <h2>Edit Trip</h2>


            <form method="POST">


                <input
                    type="hidden"
                    name="trip_id"
                    value="<?php echo $edit_trip['trip_id']; ?>"
                >


                <label>Bus:</label>

                <select
                    name="bus_id"
                    required
                >

                    <option value="">
                        -- Select Bus --
                    </option>


                    <?php

                    while ($bus = mysqli_fetch_assoc($bus_result)) {

                        $selected = "";

                        if (
                            $bus['bus_id']
                            == $edit_trip['bus_id']
                        ) {

                            $selected = "selected";
                        }

                        echo '<option value="'
                            . $bus['bus_id']
                            . '" '
                            . $selected
                            . '>';

                        echo htmlspecialchars(
                            $bus['bus_number']
                        );

                        if ($bus['status'] != 'Available') {

                            echo " ("
                                . htmlspecialchars($bus['status'])
                                . ")";
                        }

                        echo '</option>';
                    }

                    ?>

                </select>


                <label>Route:</label>

                <select
                    name="route_id"
                    required
                >

                    <option value="">
                        -- Select Route --
                    </option>


                    <?php

                    while ($route = mysqli_fetch_assoc($route_result)) {

                        $selected = "";

                        if (
                            $route['route_id']
                            == $edit_trip['route_id']
                        ) {

                            $selected = "selected";
                        }

                        echo '<option value="'
                            . $route['route_id']
                            . '" '
                            . $selected
                            . '>';

                        echo htmlspecialchars(
                            $route['route_name']
                            . ' - '
                            . $route['starting_point']
                            . ' to '
                            . $route['destination']
                        );

                        if (
                            isset($route['status']) &&
                            $route['status'] != 'Active'
                        ) {

                            echo " ("
                                . htmlspecialchars($route['status'])
                                . ")";
                        }

                        echo '</option>';
                    }

                    ?>

                </select>


                <label>Trip Date:</label>

                <input
                    type="date"
                    name="trip_date"
                    value="<?php echo htmlspecialchars($edit_trip['trip_date']); ?>"
                    required
                >


                <label>Departure Time:</label>

                <input
                    type="time"
                    name="departure_time"
                    value="<?php echo htmlspecialchars($edit_trip['departure_time']); ?>"
                    required
                >


                <label>Arrival Time:</label>

                <input
                    type="time"
                    name="arrival_time"
                    value="<?php echo htmlspecialchars($edit_trip['arrival_time'] ?? ''); ?>"
                >


                <label>Trip Type:</label>

                <select
                    name="trip_type"
                    required
                >

                    <option
                        value="Morning"
                        <?php
                        if ($edit_trip['trip_type'] == 'Morning') {
                            echo "selected";
                        }
                        ?>
                    >
                        Morning
                    </option>

                    <option
                        value="Evening"
                        <?php
                        if ($edit_trip['trip_type'] == 'Evening') {
                            echo "selected";
                        }
                        ?>
                    >
                        Evening
                    </option>

                    <option
                        value="Other"
                        <?php
                        if ($edit_trip['trip_type'] == 'Other') {
                            echo "selected";
                        }
                        ?>
                    >
                        Other
                    </option>

                </select>


                <label>Status:</label>

                <select
                    name="status"
                    required
                >

                    <option
                        value="Scheduled"
                        <?php
                        if ($edit_trip['status'] == 'Scheduled') {
                            echo "selected";
                        }
                        ?>
                    >
                        Scheduled
                    </option>

                    <option
                        value="Completed"
                        <?php
                        if ($edit_trip['status'] == 'Completed') {
                            echo "selected";
                        }
                        ?>
                    >
                        Completed
                    </option>

                    <option
                        value="Cancelled"
                        <?php
                        if ($edit_trip['status'] == 'Cancelled') {
                            echo "selected";
                        }
                        ?>
                    >
                        Cancelled
                    </option>

                </select>


                <button
                    type="submit"
                    name="update_trip"
                >
                    Update Trip
                </button>


                <a
                    href="manage_trips.php"
                    class="cancel"
                >
                    Cancel
                </a>


            </form>


        <?php } else { ?>


            <h2>Add New Trip</h2>


            <form method="POST">


                <label>Bus:</label>

                <select
                    name="bus_id"
                    required
                >

                    <option value="">
                        -- Select Bus --
                    </option>


                    <?php

                    while ($bus = mysqli_fetch_assoc($bus_result)) {

                        echo '<option value="'
                            . $bus['bus_id']
                            . '">'

                            . htmlspecialchars(
                                $bus['bus_number']
                            )

                            . '</option>';
                    }

                    ?>

                </select>


                <label>Route:</label>

                <select
                    name="route_id"
                    required
                >

                    <option value="">
                        -- Select Route --
                    </option>


                    <?php

                    while ($route = mysqli_fetch_assoc($route_result)) {

                        echo '<option value="'
                            . $route['route_id']
                            . '">'

                            . htmlspecialchars(
                                $route['route_name']
                                . ' - '
                                . $route['starting_point']
                                . ' to '
                                . $route['destination']
                            )

                            . '</option>';
                    }

                    ?>

                </select>


                <label>Trip Date:</label>

                <input
                    type="date"
                    name="trip_date"
                    required
                >


                <label>Departure Time:</label>

                <input
                    type="time"
                    name="departure_time"
                    required
                >


                <label>Arrival Time:</label>

                <input
                    type="time"
                    name="arrival_time"
                >


                <label>Trip Type:</label>

                <select
                    name="trip_type"
                    required
                >

                    <option value="Morning">
                        Morning
                    </option>

                    <option value="Evening">
                        Evening
                    </option>

                    <option value="Other">
                        Other
                    </option>

                </select>


                <label>Status:</label>

                <select
                    name="status"
                    required
                >

                    <option value="Scheduled">
                        Scheduled
                    </option>

                    <option value="Completed">
                        Completed
                    </option>

                    <option value="Cancelled">
                        Cancelled
                    </option>

                </select>


                <button
                    type="submit"
                    name="add_trip"
                >
                    ADD TRIP
                </button>


            </form>


        <?php } ?>


    </div>


    <!-- =====================================================
         TRIP LIST
         ===================================================== -->

    <div class="box">

        <h2>All Trips</h2>


        <table>

            <tr>

                <th>ID</th>

                <th>Bus</th>

                <th>Route</th>

                <th>Date</th>

                <th>Departure</th>

                <th>Arrival</th>

                <th>Type</th>

                <th>Status</th>

                <th>Action</th>

            </tr>


            <?php

            if (mysqli_num_rows($trip_result) > 0) {

                while ($trip = mysqli_fetch_assoc($trip_result)) {

                    echo "<tr>";


                    echo "<td>"
                        . $trip['trip_id']
                        . "</td>";


                    echo "<td>"
                        . htmlspecialchars(
                            $trip['bus_number']
                        )
                        . "</td>";


                    echo "<td>"
                        . htmlspecialchars(
                            $trip['route_name']
                            . ' - '
                            . $trip['starting_point']
                            . ' to '
                            . $trip['destination']
                        )
                        . "</td>";


                    echo "<td>"
                        . htmlspecialchars(
                            $trip['trip_date']
                        )
                        . "</td>";


                    echo "<td>"
                        . htmlspecialchars(
                            $trip['departure_time']
                        )
                        . "</td>";


                    echo "<td>"
                        . htmlspecialchars(
                            $trip['arrival_time'] ?? '-'
                        )
                        . "</td>";


                    echo "<td>"
                        . htmlspecialchars(
                            $trip['trip_type']
                        )
                        . "</td>";


                    echo "<td>"
                        . htmlspecialchars(
                            $trip['status']
                        )
                        . "</td>";


                    echo "<td>";


                    echo "<a
                            href='manage_trips.php?edit="
                            . $trip['trip_id']
                            . "'
                            class='action'
                          >
                            Edit
                          </a>";


                    echo "<a
                            href='manage_trips.php?delete="
                            . $trip['trip_id']
                            . "'
                            class='action delete'
                            onclick=\"return confirm('Are you sure you want to delete this trip?');\"
                          >
                            Delete
                          </a>";


                    echo "</td>";


                    echo "</tr>";
                }

            } else {

                echo '<tr>

                        <td colspan="9">
                            No trips found.
                        </td>

                      </tr>';
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