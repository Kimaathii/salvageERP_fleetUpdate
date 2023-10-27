<?php

include('includes/session.php');
$Title = _('Customer Ledger');// Screen identification.
$ViewTopic = 'ARInquiries';// Filename's id in ManualContents.php's TOC.
$BookMark = 'CustomerInquiry';// Anchor's id in the manual's html document.
include('includes/header.php');

if (!isset($_POST['RunReport'])) {

	$CustomersResult = DB_query("SELECT debtorno, name FROM debtorsmaster ORDER BY name");

	echo '<form id="Form1" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post">
		<div>
			<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
			<table cellpadding="2" class="selection">
				<tr>
					<td>Customer:</td>
					<td>
						<select name="Customer">
							<option selected="selected" value="">Select customer name</option>';

	while ($CustomerRow = DB_fetch_array($CustomersResult)) {
		echo '<option value="' . $CustomerRow['debtorno'] . '">' . $CustomerRow['name'] . '</option>';
	}

	echo '</select>
					</td>
				</tr>
				<tr>
					<td>Date From:</td>
					<td><input type="text" class="date" name="FromDate" maxlength="10" size="11" value="' . date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, date('m') - $_SESSION['NumberOfMonthMustBeShown'], date('d'), date('Y'))) . '" /></td>
				</tr>
				<tr>
					<td>Date To:</td>
					<td><input type="text" class="date" name="ToDate" maxlength="10" size="11" value="' . date($_SESSION['DefaultDateFormat']) . '" /></td>
				</tr>
			</table>
			<br />
			<div class="centre">
				<input tabindex="4" type="submit" name="RunReport" value="Show Customer Ledger" />
			</div>
		</div>
	</form>
	<br />';
	include('includes/footer.php');
	exit;
}

if ($_POST['Customer'] != '') {
	$WhereClause = "debtorsmaster.debtorno='" . $_POST['Customer'] . "'";
}

$sql = "SELECT debtorsmaster.debtorno,
			debtorsmaster.name,
			debtorsmaster.paymentterms,
			debtortrans.transno,
			debtortrans.trandate,
			debtortrans.reference,
			debtortrans.ovamount AS debit,
			debtortrans.alloc AS credit
		FROM debtortrans
		INNER JOIN debtorsmaster
		ON debtortrans.debtorno=debtorsmaster.debtorno";

if (strlen($WhereClause) > 0) {
	$sql .= " WHERE " . $WhereClause;
}

$result = DB_query($sql);

if (!isset($_POST['CreateCSV'])) {

	echo '<table>
		<thead>
			<tr>
				<th>S/N</th>
				<th>CustomerID</th>
				<th>Type</th>
				<th>Trans No.</th>
				<th>Trans Date</th>
				<th>Reference</th>
				<th>Debit</th>
				<th>Credit</th>
				<th>Balances</th>
			</tr>
		</thead>
		<tbody>';
} else {
	$CSVFile = 'S/N,CustomerID,Type,Trans No.,Trans Date,Reference,Debit,Credit,Balances' . "\n";
}

$sn = 1;
$balances = 0;

while ($myrow = DB_fetch_array($result)) {
	$balances += $myrow['debit'] - $myrow['credit'];

	if (!isset($_POST['CreateCSV'])) {
		echo '<tr>
			<td>' . $sn . '</td>
			<td>' . $myrow['debtorno'] . '</td>
			<td>' . $myrow['paymentterms'] . '</td>
			<td>' . $myrow['transno'] . '</td>
			<td>' . $myrow['trandate'] . '</td>
			<td>' . $myrow['reference'] . '</td>
			<td class="number">' . $myrow['debit'] . '</td>
			<td class="number">' . $myrow['credit'] . '</td>
			<td class="number">' . $balances . '</td>
		</tr>';
	} else {
		$CSVFile .= $sn . ',' . $myrow['debtorno'] . ',' . $myrow['paymentterms'] . ',' . $myrow['transno'] . ',' . $myrow['trandate'] . ',' . $myrow['reference'] . ',' . $myrow['debit'] . ',' . $myrow['credit'] . ',' . $balances . "\n";
	}

	$sn++;
}

if (!isset($_POST['CreateCSV'])) {
	echo '</tbody></table>';
}

if (isset($_POST['CreateCSV'])) {
	header('Content-Encoding: UTF-8');
	header('Content-type: text/csv; charset=UTF-8');
	header("Content-disposition: attachment; filename=CustomerLedger_" . date('Ymd') . '.csv');
	header("Pragma: public");
	header("Expires: 0");
	echo "\xEF\xBB\xBF"; // UTF-8 BOM
	echo $CSVFile;
	exit;
}

include('includes/footer.php');
?>
