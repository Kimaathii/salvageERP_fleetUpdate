<?php
$PageSecurity = 6;
include('includes/session.php');
$Title = _('Vechile Maintenance');
$ViewTopic = 'GettingStarted';

if (!in_array($PageSecurity, $_SESSION['AllowedPageSecurityTokens'])) {
    echo '<p class="error">You do not have permission to access this page.</p>';
    include('includes/footer.php');
    exit;
}

include('includes/header.php');

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $sql = "DELETE FROM vehicles WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {
        echo "<script>
                    alert('Vechile deleted successfully!');
                    window.location.href = 'VehicleMaintenance.php';
                </script>";
    } else {
        echo "<script>alert('Error deleting driver: " . $stmt->error . "');</script>";
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $year = $_POST['year'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $color = $_POST['color'];
    $license_number = $_POST['license_number'];
    $fuel_consumption = $_POST['fuel_consumption'];
    $tank_capacity = $_POST['tank_capacity'];
    $vehicle_class = $_POST['vehicle_class'];
    $status = $_POST['status'];

    $sql = "INSERT INTO vehicles (year, make, model, color, license_number, fuel_consumption, tank_capacity, vehicle_class, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($sql);
    $stmt->bind_param('issssdsss', $year, $make, $model, $color, $license_number, $fuel_consumption, $tank_capacity, $vehicle_class, $status);

    if ($stmt->execute()) {
        echo "<script>
                    alert('Vehicle added successfully!');
                    window.location.href = 'VehicleMaintenance.php';
                </script>";
        exit; // Prevent further execution after redirect
    } else {
        echo '<p class="error">Error: ' . $stmt->error . '</p>';
    }
}

$result = $db->query("SELECT * FROM vehicles");

if ($result->num_rows > 0) {
    echo '<div class="table-responsive">
         <table class="table text-nowrap text-md-nowrap table-striped mg-b-0">
            <tr>
                <th>Year</th>
                <th>Make</th>
                <th>Model</th>
                <th>Color</th>
                <th>License Number</th>
                <th>Fuel Consumption</th>
                <th>Tank Capacity</th>
                <th>Vehicle Class</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td class="text-primary">' . htmlspecialchars($row['year']) . '</td>
                <td class="text-primary">' . htmlspecialchars($row['make']) . '</td>
                <td class="text-primary">' . htmlspecialchars($row['model']) . '</td>
                <td class="text-primary">' . htmlspecialchars($row['color']) . '</td>
                <td class="text-primary">' . htmlspecialchars($row['license_number']) . '</td>
                <td class="text-primary">' . htmlspecialchars($row['fuel_consumption']) . '</td>
                <td class="text-primary">' . htmlspecialchars($row['tank_capacity']) . '</td>
                <td class="text-primary">' . htmlspecialchars($row['vehicle_class']) . '</td>
                <td class="text-primary">' . htmlspecialchars($row['status']) . '</td>
                <td class="text-primary">
                    <a href="editDriver.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-sm btn-primary">Edit</a>
                    <a href="?delete_id=' . htmlspecialchars($row['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>
                </td>
              </tr>';
    }
    echo '</table>
    </div>';
} else {
    echo '<p>No record available.</p>';
}
echo'

<form action="" method="post" enctype="multipart/form-data">
    <input name="FormID" type="hidden" value="' . $_SESSION['FormID'] . '" />
     <div class="form-group text-start">
    <h2 class="text-start mb-2">Add New Vechile</h2>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group text-start">
                <label for="year">Year:</label>
                <div class="input-group">
                    <input type="number" id="year" name="year" class="form-control" required>
                </div>
            </div><br>

            <div class="form-group text-start">
                <label for="make">Make:</label>
                <div class="input-group">
                    <input type="text" id="make" name="make" class="form-control" required>
                </div>
            </div><br>

            <div class="form-group text-start">
                <label for="model">Model:</label>
                <div class="input-group">
                    <input type="text" id="model" name="model" class="form-control" required>
                </div>
            </div><br>

            <div class="form-group text-start">
                <label for="color">Color:</label>
                <div class="input-group">
                    <input type="text" id="color" name="color" class="form-control">
                </div>
            </div><br>

            <div class="form-group text-start">
                <label for="license_number">License Number:</label>
                <div class="input-group">
                    <input type="text" id="license_number" name="license_number" class="form-control" required>
                </div>
            </div><br>
        </div>
        <div class="col-md-6">
            <div class="form-group text-start">
                <label for="fuel_consumption">Fuel Consumption (L/KM):</label>
                <div class="input-group">
                    <input type="number" step="0.001" id="fuel_consumption" name="fuel_consumption" class="form-control" required>
                </div>
            </div><br>

            <div class="form-group text-start">
                <label for="tank_capacity">Tank Capacity (Liters):</label>
                <div class="input-group">
                    <input type="number" step="0.01" id="tank_capacity" name="tank_capacity" class="form-control" required>
                </div>
            </div><br>

            <div class="form-group text-start">
                <label for="vehicle_class">Vehicle Class:</label>
                <div class="input-group">
                    <select id="vehicle_class" name="vehicle_class" class="form-control" required>
                        <option value="Sedan">Sedan</option>
                        <option value="SUV">SUV</option>
                        <option value="Truck">Truck</option>
                        <option value="Van">Van</option>
                        <option value="Motorcycle">Motorcycle</option>
                        <option value="Bus">Bus</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div><br>

            <div class="form-group text-start">
                <label for="status">Status:</label>
                <div class="input-group">
                    <select id="status" name="status" class="form-control" required>
                        <option value="Available">Available</option>
                        <option value="Unavailable">Unavailable</option>
                        <option value="Out of Fleet">Out of Fleet</option>
                    </select>
                </div>
            </div><br>

            <div class="form-group text-start">
                <button type="submit" class="btn btn-primary">Add Vehicle</button>
            </div>
        </div>
    </div>
</form>';
include('includes/footer.php');
?>


