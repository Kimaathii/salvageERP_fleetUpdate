<?php
session_start(); // Start the session
$PageSecurity = 6;
include('includes/session.php');
$Title = _('Drivers Maintenance');
$ViewTopic = 'GettingStarted';

if (!in_array($PageSecurity, $_SESSION['AllowedPageSecurityTokens'])) {
    echo '<p class="error">You do not have permission to access this page.</p>';
    include('includes/footer.php');
    exit;
}

if (!isset($_SESSION['FormID'])) {
    $_SESSION['FormID'] = bin2hex(random_bytes(16)); // Generate a unique FormID
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

// Handle form submission for adding or editing a driver
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate FormID
    if (!isset($_POST['FormID']) || $_POST['FormID'] !== $_SESSION['FormID']) {
        echo '<p class="error">ERROR Report: This form was not submitted with a correct ID</p>';
        exit;
    }

    // Proceed with form processing
    $driversName = trim($_POST['full_name']);
    $phoneNumber = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $location = trim($_POST['location']);
    $edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : null;

    if ($edit_id) {
        // Update the existing driver record
        $sql = "UPDATE drivers SET full_name = ?, phone = ?, email = ?, location = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('ssssi', $driversName, $phoneNumber, $email, $location, $edit_id);

        if ($stmt->execute()) {
            echo "<script>
                alert('Driver updated successfully!');
                window.location.href = 'DriverMaintenance.php';
            </script>";
            exit;
        } else {
            echo '<p class="error">Error updating driver: ' . $stmt->error . '</p>';
        }
    } else {
        // Existing logic for adding a new driver
        $picture = $_FILES['picture'];

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
}

// Fetch drivers AFTER handling form submission
$sql = "SELECT id, full_name, phone, email, picture, location FROM drivers ORDER BY id DESC";
$result = mysqli_query($db, $sql);

// Edit driver details
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);

    // Fetch the driver record from the database
    $sql = "SELECT full_name, phone, email, location FROM drivers WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $driver = $result->fetch_assoc();

        // Pre-fill the form with the driver's details
        $edit_full_name = $driver['full_name'];
        $edit_phone = $driver['phone'];
        $edit_email = $driver['email'];
        $edit_location = $driver['location'];
    } else {
        echo '<p class="error">Driver not found.</p>';
    }
}

// Search functionality
$searchQuery = '';
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchQuery = trim($_GET['search']);
    $sql = "SELECT id, full_name, phone, email, picture, location 
            FROM drivers 
            WHERE full_name LIKE ? OR phone LIKE ? OR email LIKE ? OR location LIKE ? 
            ORDER BY id DESC LIMIT 10";
    $stmt = $db->prepare($sql);
    $searchTerm = '%' . $searchQuery . '%';
    $stmt->bind_param('ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Default query to fetch the most recent 10 drivers
    $sql = "SELECT id, full_name, phone, email, picture, location 
            FROM drivers 
            ORDER BY id DESC LIMIT 10";
    $result = mysqli_query($db, $sql);
}

// Search form
echo '<form method="get" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" class="mb-3">';
echo '<div class="input-group">';
echo '<input type="text" name="search" class="form-control" placeholder="Search drivers..." value="' . htmlspecialchars($searchQuery) . '">';
echo '<button class="btn btn-primary" type="submit">Search</button>';
echo '</div>';
echo '</form>';

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
                    <a href="?edit_id=' . htmlspecialchars($row['id']) . '" class="btn btn-sm btn-primary">Edit</a> |
                    <a href="?delete_id=' . htmlspecialchars($row['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this driver?\')">Delete</a>
                </td>
              </tr>';
    }
} else {
    echo '<tr><td colspan="5">No drivers found.</td></tr>';
}
echo '</tbody></table></div>';

// Add/Edit Driver Form
echo '<div style="margin-top: 60px;">
    <form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post" enctype="multipart/form-data">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo '<input type="hidden" name="edit_id" value="' . (isset($edit_id) ? htmlspecialchars($edit_id) : '') . '">';
echo '<h4 class="text-start mb-2">' . (isset($edit_id) ? 'Edit Driver' : 'Add New Driver') . '</h4>';
echo '<div class="row">';
echo '<div class="col-md-6">';
echo '<div class="form-group text-start">';
echo '<label>Location:</label>';
echo '<select class="custom-select" name="location" required>';
echo '<option value="">Select Location</option>';
$locationQuery = "SELECT loccode, locationname FROM locations";
$locationResult = mysqli_query($db, $locationQuery);
if ($locationResult && mysqli_num_rows($locationResult) > 0) {
    while ($location = mysqli_fetch_assoc($locationResult)) {
    $selected = (isset($edit_location) && $edit_location === $location['loccode']) ? 'selected' : '';
    echo '<option value="' . htmlspecialchars($location['loccode']) . '" ' . $selected . '>' . htmlspecialchars($location['locationname']) . '</option>';
    }
} else {
    echo '<option value="">No locations available</option>';
}
echo '</select>';
echo '</div>';
echo '<div class="form-group text-start">';
echo '<label>Full Name:</label>';
echo '<div class="input-group">';
echo '<input class="form-control" placeholder="Full name" type="text" name="full_name" value="' . (isset($edit_full_name) ? htmlspecialchars($edit_full_name) : '') . '" required>';
echo '</div>';
echo '</div>';
echo '<div class="form-group text-start">';
echo '<label>Picture:</label>';
echo '<div class="input-group">';
echo '<input class="form-control" type="file" name="picture" ' . (isset($edit_id) ? '' : 'required') . '>';
echo '</div>';
echo '</div>';
echo '<button class="btn btn-main-primary text-white mt-3" type="submit">' . (isset($edit_id) ? 'Update Driver' : 'Add Driver') . '</button>';

echo '</div>';
echo '<div class="col-md-6">';
echo '<div class="form-group text-start">';
echo '<label>Email:</label>';
echo '<div class="input-group">';
echo '<input class="form-control" placeholder="Email" type="email" name="email" value="' . (isset($edit_email) ? htmlspecialchars($edit_email) : '') . '" required>';
echo '</div>';
echo '</div>';
echo '<div class="form-group text-start">';
echo '<label>Phone:</label>';
echo '<div class="input-group">';
echo '<input class="form-control" placeholder="Phone Number" type="text" name="phone" value="' . (isset($edit_phone) ? htmlspecialchars($edit_phone) : '') . '" required>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</form></div>';

include('includes/footer.php');
?>