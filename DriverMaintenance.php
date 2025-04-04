<?php
$PageSecurity = 6;
include('includes/session.php');
$Title = _('Drivers Maintenance');
$ViewTopic = 'GettingStarted';

if (!in_array($PageSecurity, $_SESSION['AllowedPageSecurityTokens'])) {
    echo '<p class="error">You do not have permission to access this page.</p>';
    include('includes/footer.php');
    exit;
}

include('includes/header.php');

// Database connection check
if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}


// Delete corresponding image file when a driver is deleted
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);
    
    // Fetch the picture path before deleting the record
    $stmt = $db->prepare("SELECT picture FROM drivers WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $deleteId);
        $stmt->execute();
        $stmt->bind_result($picturePath);
        $stmt->fetch();
        $stmt->close();

        // Delete the driver record
        $stmt = $db->prepare("DELETE FROM drivers WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $deleteId);
            if ($stmt->execute()) {
                // Delete the image file if it exists
                if (!empty($picturePath) && file_exists($picturePath)) {
                    unlink($picturePath);
                }
                echo "<script>
                    alert('Driver and corresponding image deleted successfully!');
                    window.location.href = 'DriverMaintenance.php';
                </script>";
                exit();
            } else {
                echo "<script>alert('Error deleting driver: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Error preparing delete statement: " . $db->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error fetching driver details: " . $db->error . "');</script>";
    }
}
// Handle form submission for adding a new driver
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['full_name'])) {
    $driversName = trim($_POST['full_name']);
    $phoneNumber = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $picture = $_FILES['picture'];
    $location = trim($_POST['location']);

    // Basic validations
    if (empty($driversName)) {
        echo "<script>alert('Full Name is required.'); document.querySelector('[name=\"full_name\"]').focus();</script>";
    } elseif (empty($phoneNumber)) {
        echo "<script>alert('Phone Number is required.'); document.querySelector('[name=\"phone\"]').focus();</script>";
    } elseif (empty($email)) {
        echo "<script>alert('Email is required.'); document.querySelector('[name=\"email\"]').focus();</script>";
    } elseif (empty($picture['name'])) {
        echo "<script>alert('Picture is required.'); document.querySelector('[name=\"picture\"]').focus();</script>";
    } elseif (empty($location)) {
        echo "<script>alert('Location is required.'); document.querySelector('[name=\"location\"]').focus();</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address.'); document.querySelector('[name=\"email\"]').focus();</script>";
    } elseif (!preg_match('/^\d{11}$/', $phoneNumber)) {
        echo "<script>alert('Phone number must be 11 digits.'); document.querySelector('[name=\"phone\"]').focus();</script>";
    } else {
        // Validate and upload file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($picture['type'], $allowedTypes)) {
            echo '<p class="error">Only JPEG, PNG, and GIF files are allowed.</p>';
        } elseif ($picture['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $uniqueFilename = uniqid() . '_' . basename($picture['name']);
            $uploadFile = $uploadDir . $uniqueFilename;
            move_uploaded_file($picture['tmp_name'], $uploadFile);

            // Insert into database
            $stmt = $db->prepare("INSERT INTO drivers (full_name, phone, email, picture, location) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $driversName, $phoneNumber, $email, $uploadFile, $location);
            if ($stmt->execute()) {
                echo "<script>
                    alert('Driver added successfully!');
                    window.location.href = 'DriverMaintenance.php';
                </script>";
                exit;
            } else {
                echo '<p class="error">Error adding driver: ' . $stmt->error . '</p>';
            }
        } else {
            echo '<p class="error">Error uploading file.</p>';
        }
    }
}

// Fetch drivers AFTER handling form submission
$sql = "SELECT id, full_name, phone, email, picture, location FROM drivers ORDER BY id DESC";
$result = mysqli_query($db, $sql);

// Display drivers table
echo '<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Driver\'s Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>';
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td class="text-primary">' . htmlspecialchars($row['full_name']) . '</td>
                <td class="text-primary">' . htmlspecialchars($row['phone']) . '</td>
                <td class="text-primary">' . htmlspecialchars($row['email']) . '</td>
                <td class="text-primary">' . htmlspecialchars($row['location']) . '</td>
                <td class="text-primary">
                    <a href="editDriver.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-sm btn-primary">Edit</a>
                    <a href="?delete_id=' . htmlspecialchars($row['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>
                </td>
              </tr>';
    }
} else {
    echo '<tr><td colspan="5">No drivers found.</td></tr>';
}
echo '</tbody></table></div>';

// Add Driver Form
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post" enctype="multipart/form-data">
    <input name="FormID" type="hidden" value="' . $_SESSION['FormID'] . '" />
    <h2 class="text-start mb-2">Add New Driver</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group text-start">
                <label>Location:</label>
                <select class="custom-select" name="location" required>
                    <option value="">Select Location</option>';
$locationQuery = "SELECT loccode, locationname FROM locations";
$locationResult = mysqli_query($db, $locationQuery);
if ($locationResult && mysqli_num_rows($locationResult) > 0) {
        while ($location = mysqli_fetch_assoc($locationResult)) {
                echo '<option value="' . htmlspecialchars($location['loccode']) . '">' . htmlspecialchars($location['locationname']) . '</option>';
        }
} else {
        echo '<option value="">No locations available</option>';
}
echo '</select>
            </div>
            <div class="form-group text-start">
                <label>Full Name:</label>
                <div class="input-group">
                    <input class="form-control" placeholder="Full name" type="text" name="full_name" required>
                </div>
            </div>
            <div class="form-group text-start">
                <label>Phone:</label>
                <div class="input-group">
                    <input class="form-control" placeholder="Phone Number" type="text" name="phone" required>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group text-start">
                <label>Email:</label>
                <div class="input-group">
                    <input class="form-control" placeholder="Email" type="email" name="email" required>
                </div>
            </div>
            <div class="form-group text-start">
                <label>Picture:</label>
                <div class="input-group">
                    <input class="form-control" placeholder="Image" type="file" name="picture" required accept="image/*">
                </div>
            </div>
            <button class="btn btn-main-primary  text-white mt-3" type="submit">Add Driver</button>

        </div>
    </div>
</form>';

include('includes/footer.php');
?>