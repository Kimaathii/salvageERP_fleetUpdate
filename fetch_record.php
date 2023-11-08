<?php
include('includes/session.php');

//$recordId = $_GET['id'];
//// Fetch the record data from the database
//// You'll need to adjust this based on your database structure
//// It should return the HTML markup for the form fields filled with the data
//
//// Example code (replace with your database query):
//$SQL = "SELECT * FROM rearing_houses WHERE id = $recordId";
//$ErrMsg = _('Error retrieving raring house for editing');
//$Result = DB_query($SQL, $ErrMsg);
//$editData = DB_fetch_array($Result);
$edit_id = (int)$_GET['id'];
$SQL = "SELECT * FROM rearing_houses WHERE id = $edit_id";
$ErrMsg = _('Error retrieving rearing house for editing');
$Result = DB_query($SQL, $ErrMsg);
$editData = DB_fetch_array($Result);
$editMode = true;

// Generate HTML for the form fields filled with data
echo '
   <form method="POST" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" id="modalForm">
           <input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '">
           <input type="hidden"   value="' . ($editMode ? $edit_id : '') . '">
           
<table class="selection" >

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
    <div>
       <tr>
        
        <td>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" name="submit" value="' . ($editMode ? _('Update') : _('Create')) . '">  
        </td>
    </tr>
        
      </div>
    </div>
</table>

</form>
';
?>
