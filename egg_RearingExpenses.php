<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/session.php');
$Title = _('Rearing Houses Maintenance');
$ViewTopic = 'RearingHouses';
include('includes/header.php');
echo '<div class="centre">
    <p class="page_title_text">
        <img src="'.$RootPath.'/css/'.$Theme.'/images/money_add.png" title="' . _('REARING HOUSES MAINTENANCE') . '" alt="" />' . ' ' . $Title . '
    </p>
</div>';

if (isset($_POST['submit'])) {
    $rearing_stage = $_POST['rearing_stage'];
    $category_id = (int)$_POST['category_id'];
    $created_by = $_POST['created_by'];
    $date = $_POST['date'];

    // Validate and sanitize input data as needed

    if (isset($_POST['edit_id'])) {
        $edit_id = (int)$_POST['edit_id'];
        $sql = "UPDATE poultry_stage_expenses SET
                rearing_stage = '$rearing_stage',
                category_id = $category_id,
                created_by = '$created_by',
                date = '$date'
                WHERE id = $edit_id";

        $ErrMsg = _('Error updating stage expense');
        $Result = DB_query($sql, $ErrMsg);
    } else {
        $sql = "INSERT INTO poultry_stage_expenses (rearing_stage, category_id, created_by, date)
                VALUES ('$rearing_stage', $category_id, '$created_by', '$date')";
        $ErrMsg = _('Error creating stage expense');
        $Result = DB_query($sql, $ErrMsg);
    }
}

$SQL = "SELECT id, rearing_stage, category_id, created_by, date
        FROM poultry_stage_expenses";
$ErrMsg = _('Error retrieving stage expenses');
$Result = DB_query($SQL, $ErrMsg);

echo '<table class="selection">';
echo '<tr>
        <th>' . _('Rearing Stage') . '</th>
        <th>' . _('Category ID') . '</th>
        <th>' . _('Created By') . '</th>
        <th>' . _('Date') . '</th>
        <th></th>
    </tr>';

while ($row = DB_fetch_array($Result)) {
    echo '<tr>
            <td>' . $row['rearing_stage'] . '</td>
            <td>' . $row['category_id'] . '</td>
            <td>' . $row['created_by'] . '</td>
            <td>' . $row['date'] . '</td>
            <td>
                <a href="?edit_id=' . $row['id'] . '">' . _('Edit') . '</a>
            </td>
        </tr>';
}

echo '</table>';

if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $SQL = "SELECT * FROM poultry_stage_expenses WHERE id = $edit_id";
    $ErrMsg = _('Error retrieving stage expense for editing');
    $Result = DB_query($SQL, $ErrMsg);
    $editData = DB_fetch_array($Result);
    $editMode = true;
} else {
    $editMode = false;
    $editData = array(
        'rearing_stage' => '',
        'category_id' => '',
        'created_by' => '',
        'date' => date('Y-m-d'),
    );
}

echo '<form method="POST" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">
<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '">
<input type="hidden" name="category_id" value="1">

<h3>' . ($editMode ? _('Edit Stage Expense') : _('Create Stage Expense')) . '</h3>
<input type="hidden"  value="' . ($editMode ? $edit_id : '') . '">
<table class="selection">
    <tr>
        <td>' . _('Rearing Stage') . ':</td>
        <td>
            <select name="rearing_stage" class="form-control">
                <option value="BROODING PHASE" ' . ($editData['rearing_stage'] == 'BROODING PHASE' ? 'selected' : '') . '>' . _('BROODING PHASE') . '</option>
                <option value="REARING PHASE" ' . ($editData['rearing_stage'] == 'REARING PHASE' ? 'selected' : '') . '>' . _('REARING PHASE') . '</option>
                <option value="PRODUCTION PHASE" ' . ($editData['rearing_stage'] == 'PRODUCTION PHASE' ? 'selected' : '') . '>' . _('PRODUCTION PHASE') . '</option>
                <option value="ALL PHASE" ' . ($editData['rearing_stage'] == 'ALL PHASE' ? 'selected' : '') . '>' . _('ALL PHASE') . '</option>
            </select>
        </td>
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
