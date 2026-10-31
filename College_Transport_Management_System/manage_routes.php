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
   ADD ROUTE
   ========================================================= */

if (isset($_POST['add_route'])) {

    $route_name = trim($_POST['route_name']);
    $starting_point = trim($_POST['starting_point']);
    $destination = trim($_POST['destination']);
    $stops = trim($_POST['stops']);
    $distance = $_POST['distance'];
    $status = $_POST['status'];

    if (
        empty($route_name) ||
        empty($starting_point) ||
        empty($destination)
    ) {

        $message = "Please fill in all required fields.";

    } else {

        $sql = "INSERT INTO route
                (route_name, starting_point, destination, stops, distance, status)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($con, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssssds",
            $route_name,
            $starting_point,
            $destination,
            $stops,
            $distance,
            $status
        );

        if (mysqli_stmt_execute($stmt)) {

            $message = "Route added successfully.";

        } else {

            $message = "Error: " . mysqli_error($con);
        }

        mysqli_stmt_close($stmt);
    }
}


/* =========================================================
   DELETE ROUTE
   ========================================================= */

if (isset($_GET['delete'])) {

    $route_id = (int) $_GET['delete'];


    /* Check if route is used in transport registration */

    $check_registration = mysqli_prepare(
        $con,
        "SELECT COUNT(*) AS total
         FROM transport_registration
         WHERE route_id = ?"
    );

    mysqli_stmt_bind_param(
        $check_registration,
        "i",
        $route_id
    );

    mysqli_stmt_execute($check_registration);

    $registration_result =
        mysqli_stmt_get_result($check_registration);

    $registration_data =
        mysqli_fetch_assoc($registration_result);

    mysqli_stmt_close($check_registration);


    /* Check if route is used in trip */

    $check_trip = mysqli_prepare(
        $con,
        "SELECT COUNT(*) AS total
         FROM trip
         WHERE route_id = ?"
    );

    mysqli_stmt_bind_param(
        $check_trip,
        "i",
        $route_id
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
            "This route cannot be deleted because it is already used in a transport registration or trip.";

    } else {

        $delete_sql =
            "DELETE FROM route
             WHERE route_id = ?";

        $delete_stmt =
            mysqli_prepare($con, $delete_sql);

        mysqli_stmt_bind_param(
            $delete_stmt,
            "i",
            $route_id
        );

        if (mysqli_stmt_execute($delete_stmt)) {

            $message = "Route deleted successfully.";

        } else {

            $message = "Unable to delete this route.";
        }

        mysqli_stmt_close($delete_stmt);
    }
}


/* =========================================================
   UPDATE ROUTE
   ========================================================= */

if (isset($_POST['update_route'])) {

    $route_id = $_POST['route_id'];
    $route_name = trim($_POST['route_name']);
    $starting_point = trim($_POST['starting_point']);
    $destination = trim($_POST['destination']);
    $stops = trim($_POST['stops']);
    $distance = $_POST['distance'];
    $status = $_POST['status'];

    if (
        empty($route_id) ||
        empty($route_name) ||
        empty($starting_point) ||
        empty($destination)
    ) {

        $message = "Please fill in all required fields.";

    } else {

        $sql = "UPDATE route
                SET route_name = ?,
                    starting_point = ?,
                    destination = ?,
                    stops = ?,
                    distance = ?,
                    status = ?
                WHERE route_id = ?";

        $stmt = mysqli_prepare($con, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssssdsi",
            $route_name,
            $starting_point,
            $destination,
            $stops,
            $distance,
            $status,
            $route_id
        );

        if (mysqli_stmt_execute($stmt)) {

            $message = "Route updated successfully.";

        } else {

            $message = "Error: " . mysqli_error($con);
        }

        mysqli_stmt_close($stmt);
    }
}


/* =========================================================
   GET ROUTE FOR EDIT
   ========================================================= */

$edit_route = null;

if (isset($_GET['edit'])) {

    $edit_id = (int) $_GET['edit'];

    $edit_sql = "SELECT
                    route_id,
                    route_name,
                    starting_point,
                    destination,
                    stops,
                    distance,
                    status
                 FROM route
                 WHERE route_id = ?";

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

    $edit_route =
        mysqli_fetch_assoc($edit_result);

    mysqli_stmt_close($edit_stmt);
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Routes</title>

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
            min-width: 850px;
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

    <h3>Admin - Manage Routes</h3>

</div>


<div class="container">


    <!-- =====================================================
         MESSAGE
         ===================================================== -->

    <?php

    if ($message != "") {

        echo "<div class='box'>";

        if (
            strpos($message, "cannot") !== false ||
            strpos($message, "Error") !== false ||
            strpos($message, "Unable") !== false
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
         ADD / EDIT ROUTE FORM
         ===================================================== -->

    <div class="box">

        <?php if ($edit_route) { ?>

            <h2>Edit Route</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="route_id"
                    value="<?php echo $edit_route['route_id']; ?>"
                >


                <label>Route Name</label>

                <input
                    type="text"
                    name="route_name"
                    value="<?php echo htmlspecialchars($edit_route['route_name']); ?>"
                    required
                >


                <label>Starting Point</label>

                <input
                    type="text"
                    name="starting_point"
                    value="<?php echo htmlspecialchars($edit_route['starting_point']); ?>"
                    required
                >


                <label>Destination</label>

                <input
                    type="text"
                    name="destination"
                    value="<?php echo htmlspecialchars($edit_route['destination']); ?>"
                    required
                >


                <label>Stops</label>

                <textarea
                    name="stops"
                    rows="3"
                ><?php echo htmlspecialchars($edit_route['stops'] ?? ''); ?></textarea>


                <label>Distance (KM)</label>

                <input
                    type="number"
                    name="distance"
                    step="0.01"
                    min="0"
                    value="<?php echo htmlspecialchars($edit_route['distance'] ?? ''); ?>"
                >


                <label>Status</label>

                <select name="status">

                    <option
                        value="Active"
                        <?php
                        if ($edit_route['status'] == 'Active') {
                            echo "selected";
                        }
                        ?>
                    >
                        Active
                    </option>

                    <option
                        value="Inactive"
                        <?php
                        if ($edit_route['status'] == 'Inactive') {
                            echo "selected";
                        }
                        ?>
                    >
                        Inactive
                    </option>

                </select>


                <button
                    type="submit"
                    name="update_route"
                >
                    Update Route
                </button>


                <a
                    href="manage_routes.php"
                    class="cancel"
                >
                    Cancel
                </a>

            </form>


        <?php } else { ?>


            <h2>Add New Route</h2>

            <form method="POST">

                <label>Route Name</label>

                <input
                    type="text"
                    name="route_name"
                    required
                >


                <label>Starting Point</label>

                <input
                    type="text"
                    name="starting_point"
                    required
                >


                <label>Destination</label>

                <input
                    type="text"
                    name="destination"
                    required
                >


                <label>Stops</label>

                <textarea
                    name="stops"
                    rows="3"
                ></textarea>


                <label>Distance (KM)</label>

                <input
                    type="number"
                    name="distance"
                    step="0.01"
                    min="0"
                >


                <label>Status</label>

                <select name="status">

                    <option value="Active">
                        Active
                    </option>

                    <option value="Inactive">
                        Inactive
                    </option>

                </select>


                <button
                    type="submit"
                    name="add_route"
                >
                    Add Route
                </button>

            </form>


        <?php } ?>

    </div>


    <!-- =====================================================
         ROUTE LIST
         ===================================================== -->

    <div class="box">

        <h2>Route List</h2>

        <table>

            <tr>

                <th>ID</th>

                <th>Route Name</th>

                <th>Starting Point</th>

                <th>Destination</th>

                <th>Stops</th>

                <th>Distance</th>

                <th>Status</th>

                <th>Action</th>

            </tr>


            <?php

            $routes = mysqli_query(
                $con,
                "SELECT
                    route_id,
                    route_name,
                    starting_point,
                    destination,
                    stops,
                    distance,
                    status
                 FROM route
                 ORDER BY route_id DESC"
            );


            while ($route = mysqli_fetch_assoc($routes)) {

                echo "<tr>";


                echo "<td>"
                    . $route['route_id']
                    . "</td>";


                echo "<td>"
                    . htmlspecialchars($route['route_name'])
                    . "</td>";


                echo "<td>"
                    . htmlspecialchars($route['starting_point'])
                    . "</td>";


                echo "<td>"
                    . htmlspecialchars($route['destination'])
                    . "</td>";


                echo "<td>"
                    . htmlspecialchars($route['stops'] ?? '')
                    . "</td>";


                echo "<td>"
                    . htmlspecialchars($route['distance'] ?? '')
                    . "</td>";


                echo "<td>"
                    . htmlspecialchars($route['status'])
                    . "</td>";


                echo "<td>";


                /* EDIT */

                echo "<a
                        href='manage_routes.php?edit="
                        . $route['route_id']
                        . "'
                        class='action'
                      >
                        Edit
                      </a>";


                /* DELETE */

                echo "<a
                        href='manage_routes.php?delete="
                        . $route['route_id']
                        . "'
                        class='action delete'
                        onclick=\"return confirm('Are you sure you want to delete this route?');\"
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