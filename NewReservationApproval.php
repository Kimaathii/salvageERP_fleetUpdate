<?php
$PageSecurity = 6;
include('includes/session.php');
$Title = _('Create New Reservation Approval');
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate FormID
    if (!isset($_POST['FormID']) || $_POST['FormID'] !== $_SESSION['FormID']) {
        echo '<p class="error">ERROR Report: This form was not submitted with a correct ID</p>';
        exit;
    }

    // Proceed with form processing
    if (isset($_POST['locationname'], $_POST['approval_level'])) {
        $location = $_POST['locationname'];
        $approval_level = intval($_POST['approval_level']);

        if (!empty($location) && $approval_level >= 1 && $approval_level <= 4) {
            // Check if the location already exists
            $check_sql = "SELECT COUNT(*) AS count FROM approvals WHERE locationname = ?";
            $check_stmt = $db->prepare($check_sql);
            if ($check_stmt === false) {
            echo '<p class="error">Error preparing statement: ' . $db->error . '</p>';
            } else {
            $check_stmt->bind_param('s', $location);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $row = $check_result->fetch_assoc();

            if ($row['count'] > 0) {
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    This location already exists. Please choose a different location.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            } else {
                // Insert the new location
                $sql = "INSERT INTO approvals (locationname, approval_level) VALUES (?, ?)";
                $stmt = $db->prepare($sql);
                if ($stmt === false) {
                echo '<p class="error">Error preparing statement: ' . $db->error . '</p>';
                } else {
                $stmt->bind_param('si', $location, $approval_level);

                if ($stmt->execute()) {
                    echo '<script>
                        alert("Operation completed successfully.");
                        window.location.href = "' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '";
                      </script>';
                    exit;
                } else {
                    echo '<p class="error">Error: ' . $stmt->error . '</p>';
                }
                $stmt->close();
                }
            }
            $check_stmt->close();
            }
        } else {
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Please provide valid inputs.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    // Validate FormID
    if (!isset($_POST['FormID']) || $_POST['FormID'] !== $_SESSION['FormID']) {
        echo '<p class="error">ERROR Report: This form was not submitted with a correct ID</p>';
        exit;
    }

    $edit_id = intval($_POST['edit_id']);
    $location = $_POST['locationname'];
    $approval_level = intval($_POST['approval_level']);

    if (!empty($location) && $approval_level >= 1 && $approval_level <= 4) {
        $sql = "UPDATE approvals SET locationname = ?, approval_level = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('sii', $location, $approval_level, $edit_id);

        if ($stmt->execute()) {
            echo '<script>
                    alert("Operation completed successfully.");
                    window.location.href = "' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '";
                  </script>';
            exit;
        } else {
            echo '<p class="error">Error updating record: ' . $stmt->error . '</p>';
        }
    } else {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Please provide valid inputs.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
}

if (isset($_GET['delete_id'])) {
    // Validate FormID
    // if (!isset($_POST['FormID']) || $_POST['FormID'] !== $_SESSION['FormID']) {
    //     echo '<p class="error">ERROR Report: This form was not submitted with a correct ID</p>';
    //     exit;
    // }

    $delete_id = intval($_GET['delete_id']);

    // Delete the record from the database
    $sql = "DELETE FROM approvals WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {
        echo '<script>
                alert("Operation completed successfully.");
                window.location.href = "' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '";
              </script>';
        exit;
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error deleting record: ' . htmlspecialchars($stmt->error) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
}

// Fetch locations from the database
$sql = "SELECT locationname FROM locations";
$result = $db->query($sql);

// Display the form
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post" enctype="multipart/form-data">
    <input name="FormID" type="hidden" value="' . $_SESSION['FormID'] . '" />';
echo '<h4>New Reservation Approval</h4>';
echo '<div class="row">';
echo '<div class="col-md-6">';
echo '<div class="form-group text-start">';
echo '<label for="locationname">Select Location:</label>';
echo '<select name="locationname" id="locationname" required class="form-control">';
// Re-execute the query to fetch locations again
$sql_locations = "SELECT locationname FROM locations";
$result_locations = $db->query($sql_locations);

if ($result_locations->num_rows > 0) {
    while ($row = $result_locations->fetch_assoc()) {
        echo '<option value="' . htmlspecialchars($row['locationname']) . '">' . htmlspecialchars($row['locationname']) . '</option>';
    }
} else {
    echo '<option value="" disabled>No locations available</option>';
}
echo '</select>';
echo '</div>'; // Close form-group
echo '</div>'; // Close col-md-6

echo '<div class="col-md-6">';
echo '<div class="form-group text-start">';
echo '<label for="approval_level">Approval Level (1-4):</label>';
echo '<select name="approval_level" id="approval_level" required class="form-control">';
for ($i = 1; $i <= 4; $i++) {
    echo '<option value="' . $i . '">' . $i . '</option>';
}
echo '</select>';
echo '</div>'; // Close form-group
echo '</div>'; // Close col-md-6
echo '</div>'; // Close row
echo '<button type="submit" class="btn btn-primary mt-3">Submit</button>';
echo '</form>';

// Display approvals table if there are entries
$sql_approvals = "SELECT locationname, approval_level, id FROM approvals";
$result_approvals = $db->query($sql_approvals);

if ($result_approvals->num_rows > 0) {
    echo '<div class="table-responsive" style="margin-top: 60px;">
    <h4>Existing Approvals</h4>
    <table class="table table-striped">';
    echo '<thead><tr><th><strong>Location</strong></th><th><strong>Approval Level</strong></th><th><strong>Actions</strong></th></tr></thead>';
    echo '<tbody>';
    while ($row = $result_approvals->fetch_assoc()) {
        echo '<tr>';
        echo '<td class="text-primary">' . htmlspecialchars($row['locationname']) . '</td>';
        echo '<td class="text-primary">' . htmlspecialchars($row['approval_level']) . '</td>';
        echo '<td>
                <a class="btn btn-sm btn-primary" href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?edit_id=' . urlencode($row['id']) . '">Edit</a> |
                <a class="btn btn-sm btn-danger" href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?delete_id=' . urlencode($row['id']) . '" onclick="return confirm(\'Are you sure you want to delete this record?\');">Delete</a> |
                <a class="btn btn-sm btn-success" href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?add_users_location=' . urlencode($row['locationname']) . '">Add Users</a>
              </td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table></div>';
}

// Handle edit functionality
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);

    // Fetch the record from the database
    $sql = "SELECT locationname, approval_level FROM approvals WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Populate the form with the existing data
        $edit_location = $row['locationname'];
        $edit_approval_level = $row['approval_level'];
    } else {
        echo '<p class="error">Record not found.</p>';
    }

    // Display the edit form
    echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post">';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
    echo '<input type="hidden" name="edit_id" value="' . (isset($edit_id) ? htmlspecialchars($edit_id) : '') . '">';
    echo '<label for="locationname">Select Location:</label>';
    echo '<select name="locationname" id="locationname" required>';
    if ($result_locations->num_rows > 0) {
        $sql_locations = "SELECT locationname FROM locations";
        $result_locations = $db->query($sql_locations);
        if ($result_locations->num_rows > 0) {
            while ($row = $result_locations->fetch_assoc()) {
                $selected = (isset($edit_location) && $edit_location === $row['locationname']) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($row['locationname']) . '" ' . $selected . '>' . htmlspecialchars($row['locationname']) . '</option>';
            }
        } else {
            echo '<option value="" disabled>No locations available</option>';
        }
    } else {
        echo '<option value="" disabled>No locations available</option>';
    }
    echo '</select>';
    echo '<label for="approval_level">Approval Level (1-4):</label>';
    echo '<select name="approval_level" id="approval_level" required>';
    for ($i = 1; $i <= 4; $i++) {
        $selected = (isset($edit_approval_level) && $edit_approval_level == $i) ? 'selected' : '';
        echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
    }
    echo '</select>';
    echo '<button type="submit">' . (isset($edit_id) ? 'Update' : 'Submit') . '</button>';
    echo '</form>';
}
// Display user approvals table if there are entries
$sql_user_approvals = "SELECT id, location, department, username, approval_level, created_at FROM user_approvals";
$result_user_approvals = $db->query($sql_user_approvals);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $search = '%' . $db->real_escape_string($_GET['search']) . '%';
    $sql_user_approvals = "SELECT id, location, department, username, approval_level, created_at 
                           FROM user_approvals 
                           WHERE location LIKE ? OR department LIKE ? OR username LIKE ?";
    $stmt = $db->prepare($sql_user_approvals);
    $stmt->bind_param('sss', $search, $search, $search);
    $stmt->execute();
    $result_user_approvals = $stmt->get_result();
} else {
    $sql_user_approvals = "SELECT id, location, department, username, approval_level, created_at FROM user_approvals";
    $result_user_approvals = $db->query($sql_user_approvals);
}

echo '<div style="margin-top: 60px;">
        <h4>User with Authorization</h4>
        <form method="get" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" class="mb-3" style="float: right; width: 40%;">
            <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by location, department, or username" value="' . (isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '') . '">
            <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>
        </div>';

if ($result_user_approvals->num_rows > 0) {
    echo '<div class="table-responsive" style="margin-top: 0px;">
   
    <table class="table table-striped">';
    echo '<thead><tr><th><strong>Location</strong></th><th><strong>Department</strong></th><th><strong>Username</strong></th><th><strong>Approval Level</strong></th><th><strong>Created At</strong></th><th><strong>Actions</strong></th></tr></thead>';
    echo '<tbody>';
    while ($row = $result_user_approvals->fetch_assoc()) {
        echo '<tr>';
        echo '<td class="text-primary">' . htmlspecialchars($row['location']) . '</td>';
        echo '<td class="text-primary">' . htmlspecialchars($row['department']) . '</td>';
        echo '<td class="text-primary">' . htmlspecialchars($row['username']) . '</td>';
        echo '<td class="text-primary">' . htmlspecialchars($row['approval_level']) . '</td>';
        echo '<td class="text-primary">' . htmlspecialchars($row['created_at']) . '</td>';
        echo '<td>
                <a class="btn btn-sm btn-primary" href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?edit_user_id=' . urlencode($row['id']) . '">Edit</a> |
                <a class="btn btn-sm btn-danger" href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?delete_user_id=' . urlencode($row['id']) . '" onclick="return confirm(\'Are you sure you want to delete this record?\');">Delete</a>
              </td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table></div>';
} else {
    echo '<p class="error">No user approvals found in the database.</p>';
}

// Handle edit functionality for user approvals
if (isset($_GET['edit_user_id'])) {
    $edit_user_id = intval($_GET['edit_user_id']);

    // Fetch the record from the database
    $sql = "SELECT location, department, username, approval_level, created_at FROM user_approvals WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $edit_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Populate the form with the existing data
        $edit_location = $row['location'];
        $edit_department = $row['department'];
        $edit_username = $row['username'];
        $edit_approval_level = $row['approval_level'];
        $edit_created_at = $row['created_at'];
    } else {
        echo '<p class="error">Record not found.</p>';
    }

    // Display the edit form
    echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post">';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
    echo '<input type="hidden" name="edit_user_id" value="' . (isset($edit_user_id) ? htmlspecialchars($edit_user_id) : '') . '">';
    echo '<label for="location">Select Location:</label>';
    echo '<input type="text" name="location" id="location" value="' . htmlspecialchars($edit_location) . '" required>';
    echo '<label for="department">Department:</label>';
    echo '<input type="text" name="department" id="department" value="' . htmlspecialchars($edit_department) . '" required>';
    echo '<label for="username">Username:</label>';
    echo '<input type="text" name="username" id="username" value="' . htmlspecialchars($edit_username) . '" required>';
    echo '<label for="approval_level">Approval Level (1-4):</label>';
    echo '<select name="approval_level" id="approval_level" required>';
    for ($i = 1; $i <= 4; $i++) {
        $selected = (isset($edit_approval_level) && $edit_approval_level == $i) ? 'selected' : '';
        echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
    }
    echo '</select>';
    echo '<label for="created_at">Created At:</label>';
    echo '<input type="datetime-local" name="created_at" id="created_at" value="' . htmlspecialchars($edit_created_at) . '" required>';
    echo '<button type="submit">Update</button>';
    echo '</form>';
}

// Handle delete functionality for user approvals
if (isset($_GET['delete_user_id'])) {
    $delete_user_id = intval($_GET['delete_user_id']);

    // Delete the record from the database
    $sql = "DELETE FROM user_approvals WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $delete_user_id);

    if ($stmt->execute()) {
        echo '<script>
                alert("Operation completed successfully.");
                window.location.href = "' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '";
              </script>';
        exit;
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error deleting record: ' . htmlspecialchars($stmt->error) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['location'], $_POST['department'], $_POST['username'], $_POST['approval_level'], $_POST['created_at'])) {
    $location = $_POST['location'];
    $department = $_POST['department'];
    $username = $_POST['username'];
    $approval_level = intval($_POST['approval_level']);
    $created_at = $_POST['created_at'];

    // Fetch the maximum approval level for the location
    $sql = "SELECT approval_level FROM approvals WHERE locationname = ?";
    $stmt = $db->prepare($sql);

    if ($stmt === false) {
        die('<p class="error">Error preparing statement: ' . htmlspecialchars($db->error) . '</p>');
    }

    $stmt->bind_param('s', $location);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $max_approval_level = $row['approval_level'];

        // Count the number of users already created for this location
        $sql_count = "SELECT COUNT(*) AS user_count FROM user_approvals WHERE location = ?";
        $stmt_count = $db->prepare($sql_count);

        if ($stmt_count === false) {
            die('<p class="error">Error preparing statement: ' . htmlspecialchars($db->error) . '</p>');
        }

        $stmt_count->bind_param('s', $location);
        $stmt_count->execute();
        $result_count = $stmt_count->get_result();
        $row_count = $result_count->fetch_assoc();
        $current_user_count = $row_count['user_count'];

        // Check if the current user count exceeds the approval level
        if ($current_user_count >= $max_approval_level) {
            echo '<p class="error">Cannot create more users for this location. The maximum number of users (' . $max_approval_level . ') has already been reached.</p>';
            exit;
        }

        // Validate the approval level
        if ($approval_level < 1 || $approval_level > $max_approval_level) {
            echo '<p class="error">Invalid approval level for the selected location.</p>';
            exit;
        }

        // Insert the user approval record
        $sql_insert = "INSERT INTO user_approvals (location, department, username, approval_level, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $db->prepare($sql_insert);

        if ($stmt_insert === false) {
            die('<p class="error">Error preparing statement: ' . htmlspecialchars($db->error) . '</p>');
        }

        $stmt_insert->bind_param('sssis', $location, $department, $username, $approval_level, $created_at);

        if ($stmt_insert->execute()) {
            echo '<script>
                    alert("User approval created successfully.");
                    window.location.href = "' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '";
                  </script>';
            exit;
        } else {
            echo '<p class="error">Error: ' . htmlspecialchars($stmt_insert->error) . '</p>';
        }
    } else {
        echo '<p class="error">Invalid location selected.</p>';
    }
}

// Fetch departments from the database
$sql_departments = "SELECT DISTINCT description AS department FROM departments"; // Assuming a 'departments' table exists
$result_departments = $db->query($sql_departments);

// Fetch users from the database
$sql_users = "SELECT userid FROM www_users"; // Assuming a 'www_users' table exists
$result_users = $db->query($sql_users);


if (isset($_GET['add_users_location'])) {
    $selected_location = $_GET['add_users_location'];

    // Fetch the maximum approval level for the selected location
    $sql = "SELECT approval_level FROM approvals WHERE locationname = ?";
    $stmt = $db->prepare($sql);

    if ($stmt === false) {
        // Output the error message if prepare() fails
        die('<p class="error">Error preparing statement: ' . htmlspecialchars($db->error) . '</p>');
    }

    $stmt->bind_param('s', $selected_location);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $max_approval_level = $row['approval_level'];

        // Display the user approval form
        echo '<div style="margin-top: 60px;">
            <h4>Create User Approval for Location: ' . htmlspecialchars($selected_location) . '</h4>
            <form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post" enctype="multipart/form-data">
                <input name="FormID" type="hidden" value="' . $_SESSION['FormID'] . '" />
                <div class="row">
                    <div class="col-md-6">
                        <label for="location">Location:</label>
                        <input type="text" name="location" id="location" value="' . htmlspecialchars($selected_location) . '" class="form-control" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="department">Select Department:</label>
                        <select name="department" id="department" required class="form-control">';
        // Fetch departments from the database
        $sql_departments = "SELECT DISTINCT description AS department FROM departments";
        $result_departments = $db->query($sql_departments);
        if ($result_departments->num_rows > 0) {
            while ($row = $result_departments->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($row['department']) . '">' . htmlspecialchars($row['department']) . '</option>';
            }
        } else {
            echo '<option value="" disabled>No departments available</option>';
        }
        echo '</select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="username">Select Username:</label>
                        <select name="username" id="username" required class="form-control">';
        // Fetch users from the database
        $sql_users = "SELECT userid FROM www_users";
        $result_users = $db->query($sql_users);
        if ($result_users->num_rows > 0) {
            while ($row = $result_users->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($row['userid']) . '">' . htmlspecialchars($row['userid']) . '</option>';
            }
        } else {
            echo '<option value="" disabled>No users available</option>';
        }
        echo '</select>
                    </div>
                    <div class="col-md-6">
                        <label for="approval_level">Approval Level:</label>';
        echo '<select name="approval_level" id="approval_level" required class="form-control">';
        for ($i = 1; $i <= $max_approval_level; $i++) {
            echo '<option value="' . $i . '">Assign to Approval Level ' . $i . '</option>';
        }
        echo '</select>';
        echo '</div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="created_at">Created At:</label>
                        <input type="datetime-local" name="created_at" id="created_at" required class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary mt-4">Submit</button>
                    </div>
                </div>
            </form>
        </div>';
    } else {
        echo '<p class="error">Invalid location selected.</p>';
    }
}

include('includes/footer.php');

?>