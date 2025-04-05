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

if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);

    // Fetch the vehicle record from the database
    $sql = "SELECT * FROM vehicles WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $vehicle = $result->fetch_assoc();

        // Pre-fill the form with the vehicle's details
        $edit_year = $vehicle['year'];
        $edit_make = $vehicle['make'];
        $edit_model = $vehicle['model'];
        $edit_color = $vehicle['color'];
        $edit_license_number = $vehicle['license_number'];
        $edit_fuel_consumption = $vehicle['fuel_consumption'];
        $edit_tank_capacity = $vehicle['tank_capacity'];
        $edit_vehicle_class = $vehicle['vehicle_class'];
        $edit_status = $vehicle['status'];
    } else {
        echo '<p class="error">Vehicle not found.</p>';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year = $_POST['year'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $color = $_POST['color'];
    $license_number = $_POST['license_number'];
    $fuel_consumption = $_POST['fuel_consumption'];
    $tank_capacity = $_POST['tank_capacity'];
    $vehicle_class = $_POST['vehicle_class'];
    $status = $_POST['status'];
    $edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : null;

    if ($edit_id) {
        // Update the existing vehicle record
        $sql = "UPDATE vehicles SET year = ?, make = ?, model = ?, color = ?, license_number = ?, fuel_consumption = ?, tank_capacity = ?, vehicle_class = ?, status = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('issssdsssi', $year, $make, $model, $color, $license_number, $fuel_consumption, $tank_capacity, $vehicle_class, $status, $edit_id);

        if ($stmt->execute()) {
            echo "<script>
                alert('Vehicle updated successfully!');
                window.location.href = 'VehicleMaintenance.php';
            </script>";
            exit;
        } else {
            echo '<p class="error">Error updating vehicle: ' . $stmt->error . '</p>';
        }
    } else {
        // Existing logic for adding a new vehicle
        $sql = "INSERT INTO vehicles (year, make, model, color, license_number, fuel_consumption, tank_capacity, vehicle_class, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);
        $stmt->bind_param('issssdsss', $year, $make, $model, $color, $license_number, $fuel_consumption, $tank_capacity, $vehicle_class, $status);

        if ($stmt->execute()) {
            echo "<script>
                        alert('Vehicle added successfully!');
                        window.location.href = 'VehicleMaintenance.php';
                    </script>";
            exit;
        } else {
            echo '<p class="error">Error: ' . $stmt->error . '</p>';
        }
    }
}

$result = $db->query("SELECT * FROM vehicles");

echo '<form method="get" action="" class="mb-3">
    <div class="input-group">
        <input type="text" name="search_license" class="form-control" placeholder="Search by License Number" value="' . (isset($_GET['search_license']) ? htmlspecialchars($_GET['search_license']) : '') . '">
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
      </form>';

$search_license = isset($_GET['search_license']) ? trim($_GET['search_license']) : '';

if (!empty($search_license)) {
    $sql = "SELECT * FROM vehicles WHERE license_number LIKE ?";
    $stmt = $db->prepare($sql);
    $search_param = '%' . $search_license . '%';
    $stmt->bind_param('s', $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $db->query("SELECT * FROM vehicles");
}
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
    $sql = "SELECT * FROM vehicles ORDER BY id DESC LIMIT 10";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
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
                <a href="?edit_id=' . htmlspecialchars($row['id']) . '" class="btn btn-sm btn-primary">Edit</a> |
                <a href="?delete_id=' . htmlspecialchars($row['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>
            </td>
        </tr>';
    }
    echo '</table>
    </div>';
} else {
    echo '<p>No records found.</p>';
}
    echo '</table>
    </div>';


echo '<div style="margin-top: 60px;">
<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post">';
echo '<input type="hidden" name="edit_id" value="' . (isset($edit_id) ? htmlspecialchars($edit_id) : '') . '">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo '<h4 class="text-start mb-2">' . (isset($edit_id) ? 'Edit Vehicle' : 'Add New Vehicle') . '</h4>';
?>
<div class="row">
    <div class="col-md-6">
        <div class="form-group text-start">
            <label for="year">Year:</label>
            <input type="number" id="year" name="year" class="form-control" value="<?php echo isset($edit_year) ? htmlspecialchars($edit_year) : ''; ?>" required>
        </div>
        <div class="form-group text-start">
            <label for="make">Make:</label>
            <input type="text" id="make" name="make" class="form-control" value="<?php echo isset($edit_make) ? htmlspecialchars($edit_make) : ''; ?>" required>
        </div>
        <div class="form-group text-start">
            <label for="model">Model:</label>
            <input type="text" id="model" name="model" class="form-control" value="<?php echo isset($edit_model) ? htmlspecialchars($edit_model) : ''; ?>" required>
        </div>
        <div class="form-group text-start">
            <label for="color">Color:</label>
            <input type="text" id="color" name="color" class="form-control" value="<?php echo isset($edit_color) ? htmlspecialchars($edit_color) : ''; ?>">
        </div>
        <div class="form-group text-start">
            <label for="license_number">License Number:</label>
            <input type="text" id="license_number" name="license_number" class="form-control" value="<?php echo isset($edit_license_number) ? htmlspecialchars($edit_license_number) : ''; ?>" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group text-start">
            <label for="fuel_consumption">Fuel Consumption (L/KM):</label>
            <input type="number" step="0.001" id="fuel_consumption" name="fuel_consumption" class="form-control" value="<?php echo isset($edit_fuel_consumption) ? htmlspecialchars($edit_fuel_consumption) : ''; ?>" required>
        </div>
        <div class="form-group text-start">
            <label for="tank_capacity">Tank Capacity (Liters):</label>
            <input type="number" step="0.01" id="tank_capacity" name="tank_capacity" class="form-control" value="<?php echo isset($edit_tank_capacity) ? htmlspecialchars($edit_tank_capacity) : ''; ?>" required>
        </div>
        <div class="form-group text-start">
            <label for="vehicle_class">Vehicle Class:</label>
            <select id="vehicle_class" name="vehicle_class" class="form-control" required>
                <option value="Sedan" <?php echo (isset($edit_vehicle_class) && $edit_vehicle_class === 'Sedan') ? 'selected' : ''; ?>>Sedan</option>
                <option value="SUV" <?php echo (isset($edit_vehicle_class) && $edit_vehicle_class === 'SUV') ? 'selected' : ''; ?>>SUV</option>
                <option value="Truck" <?php echo (isset($edit_vehicle_class) && $edit_vehicle_class === 'Truck') ? 'selected' : ''; ?>>Truck</option>
                <option value="Van" <?php echo (isset($edit_vehicle_class) && $edit_vehicle_class === 'Van') ? 'selected' : ''; ?>>Van</option>
                <option value="Motorcycle" <?php echo (isset($edit_vehicle_class) && $edit_vehicle_class === 'Motorcycle') ? 'selected' : ''; ?>>Motorcycle</option>
                <option value="Bus" <?php echo (isset($edit_vehicle_class) && $edit_vehicle_class === 'Bus') ? 'selected' : ''; ?>>Bus</option>
                <option value="Other" <?php echo (isset($edit_vehicle_class) && $edit_vehicle_class === 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>
        </div>
        <div class="form-group text-start">
            <label for="status">Status:</label>
            <select id="status" name="status" class="form-control" required>
                <option value="Available" <?php echo (isset($edit_status) && $edit_status === 'Available') ? 'selected' : ''; ?>>Available</option>
                <option value="Unavailable" <?php echo (isset($edit_status) && $edit_status === 'Unavailable') ? 'selected' : ''; ?>>Unavailable</option>
                <option value="Out of Fleet" <?php echo (isset($edit_status) && $edit_status === 'Out of Fleet') ? 'selected' : ''; ?>>Out of Fleet</option>
            </select>
        </div>
        <div class="form-group text-start">
            <button type="submit" class="btn btn-primary"><?php echo isset($edit_id) ? 'Update Vehicle' : 'Add Vehicle'; ?></button>
        </div>
    </div>
</div>
</form></div>
<?php
include('includes/footer.php');
?>


