<?php
include('includes/session.php');
$Title = _('Rearing Houses Maintenance');
$ViewTopic = 'RearingHouses';
include('includes/header.php');
echo '<div class="centre">
    <p class="page_title_text">
        <img src="'.$RootPath.'/css/'.$Theme.'/images/money_add.png" title="' . _('REARING HOUSES MAINTENANCE') . '" alt="" />' . ' ' . $Title . '
    </p>
</div>';

// Handle form submissions for creating and editing rearing houses
if (isset($_POST['submit'])) {
    $tag_id = (int)$_POST['tag_id'];
    $rearing_stage = $_POST['rearing_stage'];
    $branch_code = $_POST['branch_code'];
    $location_code = $_POST['location_code'];
    $location_name = $_POST['location_name'];
    $holding_capacity = (int)$_POST['holding_capacity'];
    $contact_for_deliveries = $_POST['contact_for_deliveries'];
    $phone = $_POST['phone'];
    $created_by = $_POST['created_by'];
    $date = $_POST['date'];

    // Validate and sanitize input data as needed

    if (isset($_POST['edit_id'])) {
        // Handle edit operation and update the database
        $edit_id = (int)$_POST['edit_id'];

        // Write SQL query to update the rearing house
        $sql = "UPDATE rearing_houses SET
                tag_id = $tag_id,
                rearing_stage = '$rearing_stage',
                branch_code = '$branch_code',
                location_code = '$location_code',
                location_name = '$location_name',
                holding_capacity = $holding_capacity,
                contact_for_deliveries = '$contact_for_deliveries',
                phone = '$phone',
                created_by = '$created_by',
                date = '$date'
                WHERE id = $edit_id";

        // Execute the query
        $ErrMsg = _('Error updating rearing house');
        $Result = DB_query($sql, $ErrMsg);
    } else {
        // Handle create operation and insert into the database
        // Write SQL query to insert a new rearing house
        $sql = "INSERT INTO rearing_houses (tag_id, rearing_stage, branch_code, location_code, location_name, holding_capacity, contact_for_deliveries, phone, created_by, date)
                VALUES ($tag_id, '$rearing_stage', '$branch_code', '$location_code', '$location_name', $holding_capacity, '$contact_for_deliveries', '$phone', '$created_by', '$date')";

        // Execute the query
        $ErrMsg = _('Error creating rearing house');
        $Result = DB_query($sql, $ErrMsg);
    }
}

// List rearing houses
$SQL = "SELECT id, tag_id, rearing_stage, branch_code, location_code, location_name, holding_capacity, contact_for_deliveries, phone, created_by, date
        FROM rearing_houses";
$ErrMsg = _('Error retrieving rearing houses');
$Result = DB_query($SQL, $ErrMsg);

echo '<table class="selection">';
echo '<tr>
        <th>' . _('Tag ID') . '</th>
        <th>' . _('Rearing Stage') . '</th>
        <th>' . _('Branch Code') . '</th>
        <th>' . _('Location Code') . '</th>
        <th>' . _('Location Name') . '</th>
        <th>' . _('Holding Capacity') . '</th>
        <th>' . _('Contact for Deliveries') . '</th>
        <th>' . _('Phone') . '</th>
        <th>' . _('Created By') . '</th>
        <th>' . _('Date') . '</th>
        <th></th>
    </tr>';

while ($row = DB_fetch_array($Result)) {
    echo '<tr>
            <td>' . $row['tag_id'] . '</td>
            <td>' . $row['rearing_stage'] . '</td>
            <td>' . $row['branch_code'] . '</td>
            <td>' . $row['location_code'] . '</td>
            <td>' . $row['location_name'] . '</td>
            <td>' . $row['holding_capacity'] . '</td>
            <td>' . $row['contact_for_deliveries'] . '</td>
            <td>' . $row['phone'] . '</td>
            <td>' . $row['created_by'] . '</td>
            <td>' . $row['date'] . '</td>
            <td>
                <a href="?edit_id=' . $row['id'] . '">' . _('Edit') . '</a>
            </td>
        </tr>';
}

echo '</table>';

// Create or edit rearing house form
if (isset($_GET['edit_id'])) {
    // Load data for editing
    $edit_id = (int)$_GET['edit_id'];
    $SQL = "SELECT * FROM rearing_houses WHERE id = $edit_id";
    $ErrMsg = _('Error retrieving rearing house for editing');
    $Result = DB_query($SQL, $ErrMsg);
    $editData = DB_fetch_array($Result);
    $editMode = true;
} else {
    $editMode = false;
    $editData = array(
        'tag_id' => '',
        'rearing_stage' => '',
        'branch_code' => '',
        'location_code' => '',
        'location_name' => '',
        'holding_capacity' => '',
        'contact_for_deliveries' => '',
        'phone' => '',
        'created_by' => '',
        'date' => date('Y-m-d'),
    );
}

echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">
<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '">

<h3>' . ($editMode ? _('Edit Rearing House') : _('Create Rearing House')) . '</h3>
<input type="hidden" name="edit_id" value="' . ($editMode ? $edit_id : '') . '">
<table class="selection">
    <tr>
        <td>' . _('Tag ID') . ':</td>
        <td>
            <select name="tag_id">
                <option value="">Select Branch Code</option>'; // An empty option for a default selection';

$SQL = "SELECT tagref, tagdescription FROM tags";
$ErrMsg = _('Error retrieving tag options');
$Result = DB_query($SQL, $ErrMsg);

while ($row = DB_fetch_array($Result)) {
    $selected = ($editData['tag_id'] == $row['tagref']) ? 'selected' : '';
    echo '<option value="' . $row['tagref'] . '" ' . $selected . '>' . $row['tagdescription'] . '</option>';
}

echo '</select>
        </td>
    </tr>
    <tr>
        <td>' . _('Rearing Stage') . ':</td>
        <td>
            <select name="rearing_stage" class="form-control">
                <option value="BROODING PHASE" ' . ($editData['rearing_stage'] == 'BROODING PHASE' ? 'selected' : '') . '>' . _('BROODING PHASE') . '</option>
                <option value="REARING PHASE" ' . ($editData['rearing_stage'] == 'REARING PHASE' ? 'selected' : '') . '>' . _('REARING PHASE') . '</option>
                <option value="PRODUCTION PHASE" ' . ($editData['rearing_stage'] == 'PRODUCTION PHASE' ? 'selected' : '') . '>' . _('PRODUCTION PHASE') . '</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>' . _('Branch Code') . ':</td>
        <td><input type="text" name="branch_code" value="' . $editData['branch_code'] . '"></td>
    </tr>
    <tr>
        <td>' . _('Location Code') . ':</td>
        <td><input type="text" name="location_code" value="' . $editData['location_code'] . '"></td>
    </tr>
    <tr>
        <td>' . _('Location Name') . ':</td>
        <td><input type="text" name="location_name" value="' . $editData['location_name'] . '"></td>
    </tr>
    <tr>
        <td>' . _('Holding Capacity') . ':</td>
        <td><input type="text" name="holding_capacity" value="' . $editData['holding_capacity'] . '"></td>
    </tr>
    <tr>
        <td>' . _('Contact for Deliveries') . ':</td>
        <td><input type="text" name="contact_for_deliveries" value="' . $editData['contact_for_deliveries'] . '"></td>
    </tr>
    <tr>
        <td>' . _('Phone') . ':</td>
        <td><input type="text" name="phone" value="' . $editData['phone'] . '"></td>
    </tr>
    <tr>
        <td>' . _('Created By') . ':</td>
        <td><input type="text" name="created_by" value="' . $editData['created_by'] . '"></td>
    </tr>
    <tr>
        <td>' . _('Date') . ':</td>
        <td><input type="date" name="date" value="' . $editData['date'] . '"></td>
    </tr>
    <tr>
        <td></td>
        <td>
            <input type="submit" name="submit" value="' . ($editMode ? _('Update') : _('Create')) . '">
        </td>
    </tr>
</table>
</form>';



include('includes/footer.php');
?>
