<?php
$PageSecurity = 2; // Set the page security level for operation managers
include('includes/session.php');
$Title = _('Stage 2 Approval');
include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');

// Fetch the logged-in user
$loggedInUser = $_SESSION['UserID']; // Assuming the logged-in user's ID is stored in the session

// Check if the logged-in user is authorized for Level 2 Approval
$authSQL = "SELECT username 
            FROM user_approvals 
            WHERE username = ? 
              AND approval_level = 2";
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

// Handle location selection
$SelectedLocation = isset($_POST['Location']) ? trim($_POST['Location']) : '';

// Fetch available locations
$locationsSQL = "SELECT loccode, locationname FROM locations";
$locationsResult = DB_query($locationsSQL);

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

// Fetch available vehicles
$vehicles = DB_query("SELECT id, license_number FROM vehicles");

// Fetch available drivers
$drivers = DB_query("SELECT id, full_name FROM drivers");

// Fetch available salesmen
$salesmen = DB_query("SELECT salesmancode, salesmanname FROM salesman");

echo '<h1>' . _('Stage 2 Approval') . '</h1>';
echo '<p><a href="supervisor.php"><i class="fas fa-user-cog"></i> ' . _('Add or Modify Supervisors') . '</a> | <a href="vechilemaintenance.php"><i class="fas fa-car"></i> ' . _('Add or Modify Vehicles') . '</a> | <a href="drivermaintenance.php"><i class="fas fa-user-tie"></i> ' . _('Add or Modify Drivers') . '</a></p>';

// Only fetch and display items if a location is selected
if (!empty($SelectedLocation)) {
    // Fetch orders pending Stage 2 Approval with stock details for the selected location
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
                   GROUP_CONCAT(stockmaster.longdescription) AS stock_available,
                   SUM(salesorderdetails.unitprice * salesorderdetails.quantity) AS total_price
            FROM salesorders
            INNER JOIN salesorderdetails ON salesorders.orderno = salesorderdetails.orderno
            INNER JOIN stockmaster ON salesorderdetails.stkcode = stockmaster.stockid
            WHERE salesorders.current_approval_level = 2 
              AND salesorders.approval_status = 'Level 1 Approved'
              AND salesorders.fromstkloc = '" . $SelectedLocation . "'
            GROUP BY salesorders.orderno";

    $result = DB_query($sql);

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
                    <th>' . _('Total Price') . '</th>
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
                    <td>' . $row['total_price'] . '</td>
                  </tr>';
            echo '<tr>
                    <td colspan="13">
                       <form method="post" action="">
                           <input name="FormID" type="hidden" value="' . $_SESSION['FormID'] . '" />
                           <input type="hidden" name="OrderNo" value="' . $row['orderno'] . '" />
                           <div class="row">
                               <div class="col">
                                   <label>' . _('Salesman') . ':</label>
                                   <select name="SalesmanCode">
                                       <option value="">' . _('Select Salesman') . '</option>';
                                       while ($salesman = DB_fetch_array($salesmen)) {
                                           echo '<option value="' . $salesman['salesmancode'] . '">' . $salesman['salesmanname'] . '</option>';
                                       }
                                   echo '</select>
                               </div>
                               <div class="col">
                                   <label>' . _('Vehicle') . ':</label>
                                   <select name="VehicleID">
                                       <option value="">' . _('Select Vehicle') . '</option>';
                                       while ($vehicle = DB_fetch_array($vehicles)) {
                                           echo '<option value="' . $vehicle['id'] . '">' . $vehicle['license_number'] . '</option>';
                                       }
                                   echo '</select>
                               </div>
                               <div class="col">
                                   <label>' . _('Driver') . ':</label>
                                   <select name="DriverID">
                                       <option value="">' . _('Select Driver') . '</option>';
                                       while ($driver = DB_fetch_array($drivers)) {
                                           echo '<option value="' . $driver['id'] . '">' . $driver['full_name'] . '</option>';
                                       }
                                   echo '</select>
                               </div>
                               <div class="col">
                                   <button type="submit" name="Approve" class="btn btn-success">' . _('Authorize') . '</button>
                                   <button type="submit" name="Reject" class="btn btn-danger">' . _('Reject') . '</button>
                               </div>
                           </div>
                       </form>
                    </td>
                  </tr>';
        }
        echo '</table>
        </div>';
    } else {
        echo '<p>' . _('No reservations pending Stage 2 Approval for the selected location.') . '</p>';
    }
} else {
    echo '<p>' . _('Please select a location to view reservations.') . '</p>';
}

include('includes/footer.php');
?>