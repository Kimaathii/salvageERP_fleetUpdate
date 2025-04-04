<?php
$PageSecurity = 0;

include('includes/session.php');

// Redirect to InitialScripts.php if it's the first login
if (isset($_SESSION['FirstLogIn']) and $_SESSION['FirstLogIn'] == '1' and isset($_SESSION['DatabaseName'])) {
	$_SESSION['FirstRun'] = true;
	echo '<meta http-equiv="refresh" content="0; url=' . $RootPath . '/InitialScripts.php">';
	exit;
} else {
	$_SESSION['FirstRun'] = false;
}

$Title = _('Main Menu');

// Set the module based on the Application parameter
if (isset($_GET['Application']) and ($_GET['Application'] != '')) {
	$_SESSION['Module'] = $_GET['Application'];
	setcookie('Module', $_GET['Application'], time() + 3600 * 24 * 30);
}

include('includes/header.php');

// Main content wrapper
echo '<div class="main-content side-content pt-0">';
echo '<div class="main-container container-fluid" >';
echo '<div class="inner-body">';

// Header section
echo '  <div class="page-header bg-deafult p-2">
						<div>
							<h3 class="main-content-title tx-20 mg-b-5" style="font-weight:normal mb-2">SALVAGE ERP</h3>
							<ol class="breadcrumb">
								<li class="breadcrumb-item">
									<a>
										<i aria-hidden="true" class="fa fa-home"></i>
									</a>
								</li>
								<li class="breadcrumb-item">
									<a>' . $Title . '</a>
								</li>
								<li aria-current="page" class="active breadcrumb-item">
									savage ERP
								</li>
							</ol>
						</div>
						<div class="d-flex">
							<div class="justify-content-center">
								<button class="btn btn-white btn-icon-text my-2 me-2" type="button">
									<i class="fa fa-download me-2"></i> Import
								</button>

								<a class="btn btn-primary my-2 btn-icon-text"
								   href="#"><i class="fa fa-cloud-download me-2"></i>
										View Customer Report
								</a>
							</div>
						</div>
					</div>';

// Start of the main row
echo '<div class="row">';

// Transactions section
echo '<div class="col-lg-12 col-xl-4">';
echo '<div class="card custom-card" id="TransactionsDiv">
								<div class="card-header custom-card-header bg-primary">
									<h6 class="main-content-label mb-3 tx-white"><i class="pe-7s-alarm"></i>&nbsp;&nbsp;<b>';
if ($_SESSION['Module'] == 'system') {
	echo '<b>', _('General Setup Options'), '</b>';
} elseif ($_SESSION['Module'] == 'hospsetup') {
	echo '<b>', _('General Hospital Setup'), '</b>';
} else {
	echo '<b>', _('Transactions'), '</b>';
}
echo '</b></h6></div><ul class="todo-list-wrapper list-group list-group-flush">';

// Display transaction menu items
$i = 0;
foreach ($MenuItems[$_SESSION['Module']]['Transactions']['Caption'] as $Caption) {
	$ScriptNameArray = explode('?', substr($MenuItems[$_SESSION['Module']]['Transactions']['URL'][$i], 1));
	if (isset($_SESSION['PageSecurityArray'][$ScriptNameArray[0]])) {
		$PageSecurity = $_SESSION['PageSecurityArray'][$ScriptNameArray[0]];
	}
	if ((in_array($PageSecurity, $_SESSION['AllowedPageSecurityTokens']) and $PageSecurity != '')) {
		echo '<li class="list d-flex align-items-center p-2 border-bottom " style="padding-bottom:5px; padding-top:5px;">
				<a href="', $RootPath, $MenuItems[$_SESSION['Module']]['Transactions']['URL'][$i], '"  style="padding-left:15px;">', $Caption, '</a>
			</li>';
	}
	++$i;
}
echo '</ul>
		</div>
			</div>';

// Inquiries and Reports section
echo '<div class="col-lg-12 col-xl-4">';
echo ' <div class="card custom-card" id="InquiriesDiv">
								<div class="card-header custom-card-header bg-secondary">
									<h6 class="main-content-label mb-3 tx-white">
										';
if ($_SESSION['Module'] == 'system') {
	$Header = '<i class="pe-7s-tools"></i>&nbsp;&nbsp;<b><b>' . _('Receivables/Payables Setup') . '</b>';
} elseif ($_SESSION['Module'] == 'hospsetup') {
	$Header = '<img src="' . $RootPath . '/css/' . $_SESSION['Theme'] . '/images/ar.png" data-title="' . _('ERP Integration') . '" alt="' . _('ERP Integration') . '" /><b>' . _('ERP Integration') . '</b>';
} else {
	$Header = '<i class="pe-7s-note2"></i>&nbsp;&nbsp;<b>' . _('Inquiries and Reports') . '</b>';
}
echo $Header;
echo '</b></h6></div> <ul class="todo-list-wrapper list-group list-group-flush">';

// Display report menu items
$i = 0;
if (isset($MenuItems[$_SESSION['Module']]['Reports'])) {
	foreach ($MenuItems[$_SESSION['Module']]['Reports']['Caption'] as $Caption) {
		$ScriptNameArray = explode('?', substr($MenuItems[$_SESSION['Module']]['Reports']['URL'][$i], 1));
		$PageSecurity = $_SESSION['PageSecurityArray'][$ScriptNameArray[0]];
		if ((in_array($PageSecurity, $_SESSION['AllowedPageSecurityTokens']) or !isset($PageSecurity))) {
			echo ' <li class="list d-flex align-items-center p-2 border-bottom "
										style="padding-bottom:5px; padding-top:5px;">
				<a href="' . $RootPath . $MenuItems[$_SESSION['Module']]['Reports']['URL'][$i] . '" style="padding-left:15px;">' . $Caption . '</a>
			</li>';
		}
		++$i;
	}
}

// Get additional report links
echo GetRptLinks($_SESSION['Module']);
echo '</ul>
	</div>
	</div>';

// Maintenance section
echo '<div class="col-lg-12 col-xl-4">';
echo '<div class="card custom-card" id="MaintenanceDiv">
<div class="card-header custom-card-header bg-primary">
 <h6 class="main-content-label mb-3 tx-white">
	';
if ($_SESSION['Module'] == 'system') {
	$Header = '<i class="pe-7s-box2"></i>&nbsp;&nbsp;<b>' . _('Inventory Setup') . '</b>';
} elseif ($_SESSION['Module'] == 'hospsetup') {
	$Header = '<b>' . _('Maintain Types') . '</b>';
} else {
	$Header = '<i class="pe-7s-config"></i>&nbsp;&nbsp;<b><b>' . _('Maintenance') . '</b>';
}
echo $Header;
echo '</b></h6></div><ul class="todo-list-wrapper list-group list-group-flush">';

// Display maintenance menu items
$i = 0;
if (isset($MenuItems[$_SESSION['Module']]['Maintenance'])) {


	foreach ($MenuItems[$_SESSION['Module']]['Maintenance']['Caption'] as $Caption) {
		$ScriptNameArray = explode('?', substr($MenuItems[$_SESSION['Module']]['Maintenance']['URL'][$i], 1));
		if (isset($_SESSION['PageSecurityArray'][$ScriptNameArray[0]])) {
			$PageSecurity = $_SESSION['PageSecurityArray'][$ScriptNameArray[0]];
		} else {
			$PageSecurity = ''; // Default to an empty string if not set
		}
		
		if ((empty($PageSecurity) || in_array($PageSecurity, $_SESSION['AllowedPageSecurityTokens']))) {
			echo '<li class="list d-flex align-items-center p-2 border-bottom" style="padding-bottom:5px; padding-top:5px;">
					
			<a href="' . $RootPath . $MenuItems[$_SESSION['Module']]['Maintenance']['URL'][$i] . '">' . $Caption . '</a>
				</li>';
		}
		++$i;
	}
} else {
	// Debugging: Inform if the Maintenance array is not set
	echo '<p>No Maintenance items found for the selected module.</p>';
}
echo '</ul>
</div></div>';

// Close the main content wrapper if needed
if ($should_wrap) {
	echo '</div></form></div></div></div>';
}
include('includes/footer.php');

// Function to get report links
function GetRptLinks($GroupID)
{
	global $RootPath;
	if (!isset($_SESSION['FormGroups'])) {
		$_SESSION['FormGroups'] = array(
			'gl:chk' => _('Bank Checks'), // Bank checks grouped with the gl report group
			'ar:col' => _('Collection Letters'),
			'ar:cust' => _('Customer Statements'),
			'gl:deps' => _('Bank Deposit Slips'),
			'ar:inv' => _('Invoices and Packing Slips'),
			'ar:lblc' => _('Labels - Customer'),
			'prch:lblv' => _('Labels - Vendor'),
			'prch:po' => _('Purchase Orders'),
			'ord:quot' => _('Customer Quotes'),
			'ar:rcpt' => _('Sales Receipts'),
			'ord:so' => _('Sales Orders'),
			'misc:misc' => _('Miscellaneous')
		); // do not delete misc category
	}
	if (isset($_SESSION['ReportList'][$GroupID])) {
		$GroupID = $_SESSION['ReportList'][$GroupID];
	}
	$Title = array(_('Custom Reports'), _('Standard Reports and Forms'));

	if (!isset($_SESSION['ReportList'])) {
		$SQL = "SELECT id,
						reporttype,
						defaultreport,
						groupname,
						reportname
					FROM reports
					ORDER BY groupname,
							reportname";
		$Result = DB_query($SQL, '', '', false, true);
		$_SESSION['ReportList'] = array();
		while ($Temp = DB_fetch_assoc($Result)) {
			$_SESSION['ReportList'][] = $Temp;
		}
	}
	$RptLinks = '';
	for ($Def = 1; $Def >= 0; $Def--) {
		$RptLinks .= '<li class="CustomMenuList list d-flex align-items-center p-2 border-bottom " style="padding-bottom:5px; padding-top:5px;">';
		$RptLinks .= '<b>' . $Title[$Def] . '</b>';
		$RptLinks .= '</li>';
		$NoEntries = true;
		if (isset($_SESSION['ReportList']['groupname']) and count($_SESSION['ReportList']['groupname']) > 0) { // then there are reports to show, show by grouping
			foreach ($_SESSION['ReportList'] as $Report) {
				if (isset($Report['groupname']) and $Report['groupname'] == $GroupID and $Report['defaultreport'] == $Def) {
					$RptLinks .= '<li class="menu_group_item  list d-flex align-items-center p-2 border-bottom " style="padding-bottom:5px; padding-top:5px;">';
					$RptLinks .= '<p><a href="' . $RootPath . '/reportwriter/ReportMaker.php?action=go&amp;reportid=' . urlencode($Report['id']) . '">&nbsp; ' . _($Report['reportname']) . '</a></p>';
					$RptLinks .= '</li>';
					$NoEntries = false;
				}
			}
			// now fetch the form groups that are a part of this group (List after reports)
			$NoForms = true;
			foreach ($_SESSION['ReportList'] as $Report) {
				$Group = explode(':', $Report['groupname']); // break into main group and form group array
				if ($NoForms and $Group[0] == $GroupID and $Report['reporttype'] == 'frm' and $Report['defaultreport'] == $Def) {
					$RptLinks .= '<li class="menu_group_item list d-flex align-items-center p-2 border-bottom" style="padding-bottom:5px; padding-top:5px;">';
					$RptLinks .= '<img src="' . $RootPath . '/css/' . $_SESSION['Theme'] . '/images/folders.gif" width="16" height="13" alt="" />&nbsp; ';
					$RptLinks .= '<p><a href="' . $RootPath . '/reportwriter/FormMaker.php?id=' . urlencode($Report['groupname']) . '">' . $_SESSION['FormGroups'][$Report['groupname']] . '</a></p>';
					$RptLinks .= '</li>';
					$NoForms = false;
					$NoEntries = false;
				}
			}
		}
		if ($NoEntries) $RptLinks .= '<li class="menu_group_item list d-flex align-items-center p-2 border-bottom" style="padding-bottom:5px; padding-top:5px;">' . _('There are no reports to show!') . '</li>';
	}
	return $RptLinks;
}
