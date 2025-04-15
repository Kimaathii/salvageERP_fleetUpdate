<?php
$PageSecurity = 1; // Set the page security level
include('includes/session.php');
$Title = _('Level 1 Approval');
include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/ConfirmDispatch_invoice.php');

// Fetch the logged-in user
$loggedInUser = $_SESSION['UserID']; // Assuming the logged-in user's ID is stored in the session

// Check if the logged-in user is authorized for Level 1 Approval
$authSQL = "SELECT username 
            FROM user_approvals 
            WHERE username = ? 
              AND approval_level = 1";
$stmt = $db->prepare($authSQL);
if (!$stmt) {
    die('SQL Prepare Error: ' . $db->error);
}
$stmt->bind_param('s', $loggedInUser);
$stmt->execute();
$result = $stmt->get_result();
$authRow = $result->fetch_assoc();

if (!$authRow) {
    // If the user is not authorized, show an error message and terminate the script
    prnMsg(_('You are not authorized to access this page. Please contact the administrator.'), 'error');
    include('includes/footer.php');
    exit;
}

// Fetch available locations
$locationsSQL = "SELECT loccode, locationname FROM locations";
$locationsResult = DB_query($locationsSQL);

// Handle location selection
$SelectedLocation = isset($_POST['Location']) ? trim($_POST['Location']) : '';

// Display location dropdown
echo '<form method="post" action="">
        <input name="FormID" type="hidden" value="' . $_SESSION['FormID'] . '" />
        <label for="Location">' . _('Select Location') . ':</label>
        <select name="Location" id="Location" onchange="this.form.submit()">
            <option value="">' . _('Select a Location') . '</option>';
while ($location = DB_fetch_array($locationsResult)) {
    echo '<option value="' . $location['loccode'] . '"' . ($SelectedLocation == $location['loccode'] ? ' selected' : '') . '>' . $location['locationname'] . '</option>';
}
echo '</select>
      </form>';

// Check if the user is authorized for the selected location
if (!empty($SelectedLocation)) {
    $authSQL = "SELECT COUNT(*) AS authorized
                FROM locationusers
                WHERE userid = ?
                  AND loccode = ?";
    $stmt = $db->prepare($authSQL);
    if (!$stmt) {
        die('SQL Prepare Error: ' . $db->error);
    }
    $stmt->bind_param('ss', $loggedInUser, $SelectedLocation);
    $stmt->execute();
    $result = $stmt->get_result();
    $authRow = $result->fetch_assoc();

    if ($authRow['authorized'] == 0) {
        // If the user is not authorized, show an error message and terminate the script
        prnMsg(_('You are not authorized to access this location. Please contact the administrator to authorize you for this location.'), 'error');
        include('includes/footer.php');
        exit;
    }
}

// Only fetch and display items if a location is selected
if (!empty($SelectedLocation)) {
    $sql = "SELECT salesorders.orderno, 
                   salesorders.debtorno, 
                   salesorders.branchcode, 
                   salesorders.customerref, 
                   salesorders.comments, 
                   salesorders.orddate, 
                   salesorders.deliverydate, 
                   salesorders.freightcost,
                   GROUP_CONCAT(salesorderdetails.stkcode) AS stkcodes, 
                   GROUP_CONCAT(stockmaster.description) AS descriptions, 
                   SUM(salesorderdetails.quantity) AS total_quantity, 
                   GROUP_CONCAT(stockmaster.longdescription) AS stock_available
            FROM salesorders
            INNER JOIN salesorderdetails ON salesorders.orderno = salesorderdetails.orderno
            INNER JOIN stockmaster ON salesorderdetails.stkcode = stockmaster.stockid
            WHERE salesorders.approval_status = 'pending'
              AND salesorders.fromstkloc = '" . $SelectedLocation . "'
            GROUP BY salesorders.orderno";

    $result = DB_query($sql);

    echo '<h1>' . _('Level 1 Approval') . '</h1>';
    if (DB_num_rows($result) > 0) {
        echo '<div class="table-responsive" style="overflow-x:auto;">
        <table class="table table-striped">
                <tr>
                    <th>' . _('Order No') . '</th>
                    <th>' . _('Customer') . '</th>
                    <th>' . _('Branch') . '</th>
                    <th>' . _('Customer Ref') . '</th>
                    <th>' . _('Comments') . '</th>
                    <th>' . _('Order Date') . '</th>
                    <th>' . _('Delivery Date') . '</th>
                    <th>' . _('Freight Cost') . '</th>
                    <th>' . _('Stock Codes') . '</th>
                    <th>' . _('Descriptions') . '</th>
                    <th>' . _('Total Quantity Ordered') . '</th>
                    <th>' . _('Stock Available') . '</th>
                    <th>' . _('Actions') . '</th>
                </tr>';
        while ($row = DB_fetch_array($result)) {
            echo '<tr>
                    <td>' . $row['orderno'] . '</td>
                    <td>' . $row['debtorno'] . '</td>
                    <td>' . $row['branchcode'] . '</td>
                    <td>' . $row['customerref'] . '</td>
                    <td>' . $row['comments'] . '</td>
                    <td>' . $row['orddate'] . '</td>
                    <td>' . $row['deliverydate'] . '</td>
                    <td>' . $row['freightcost'] . '</td>
                    <td>' . $row['stkcodes'] . '</td>
                    <td>' . $row['descriptions'] . '</td>
                    <td>' . $row['total_quantity'] . '</td>
                    <td>' . $row['stock_available'] . '</td>
                    <td>
                        <form method="post" action="">
                            <input name="FormID" type="hidden" value="' . $_SESSION['FormID'] . '" />
                            <input type="hidden" name="OrderNo" value="' . $row['orderno'] . '" />
                            <button type="submit" name="Approve">Approve</button>
                            <button type="submit" name="Reject">Reject</button>
                        </form>
                    </td>
                  </tr>';
        }
        echo '</table>
        </div>';
    } else {
        echo '<p>' . _('No reservations pending Level 1 Approval for the selected location.') . '</p>';
    }
} else {
    echo '<p>' . _('Please select a location to view reservations.') . '</p>';
}

// Handle approval actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['OrderNo'])) {
    $OrderNo = intval($_POST['OrderNo']);
    if (isset($_POST['Approve'])) {
        // Approve the reservation
        $sql = "UPDATE salesorders SET approval_status = 'Level 1 Approved', current_approval_level = 2 WHERE orderno = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $OrderNo);
        $stmt->execute();

        // Log the approval
        $sql = "INSERT INTO order_approvals (orderno, approval_level, approved_by, status)
                VALUES (?, 1, ?, 'Approved')";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('is', $OrderNo, $_SESSION['UserID']);
        $stmt->execute();

        prnMsg(_('Reservation approved at Level 1.'), 'success');
    } elseif (isset($_POST['Reject'])) {
        // Reject the reservation
        $sql = "UPDATE salesorders SET approval_status = 'Rejected' WHERE orderno = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $OrderNo);
        $stmt->execute();

        prnMsg(_('Reservation rejected.'), 'error');
    }
}
include('includes/footer.php');
?>