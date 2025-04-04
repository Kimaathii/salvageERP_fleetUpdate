<?php


/* Display outstanding debtors, creditors, etc */

include('includes/session.php');
$Title = _('Dashboard');
$ViewTopic = 'GeneralLedger'; // RChacon: You should be in this topic ?
$BookMark = 'Dashboard';

// include header section
include('includes/header.php');


$Sql = "SELECT pagesecurity
		FROM scripts
		WHERE scripts.script = 'AgedDebtors.php'";
$ErrMsg = _('The security for Aging Debtors cannot be retrieved because');
$DbgMsg = _('The SQL that was used and failed was');
$Security1Result = DB_query($Sql, $ErrMsg, $DbgMsg);
$MyUserRow = DB_fetch_array($Security1Result);
$DebtorSecurity = $MyUserRow['pagesecurity'];

$Sql = "SELECT pagesecurity
		FROM scripts
		WHERE scripts.script = 'SuppPaymentRun.php'";
$ErrMsg = _('The security for upcoming payments cannot be retrieved because');
$DbgMsg = _('The SQL that was used and failed was');
$Security2Result = DB_query($Sql, $ErrMsg, $DbgMsg);
$MyUserRow = DB_fetch_array($Security2Result);
$PayeeSecurity = $MyUserRow['pagesecurity'];

$Sql = "SELECT pagesecurity
		FROM scripts
		WHERE scripts.script = 'GLAccountInquiry.php'";
$ErrMsg = _('The security for G/L Accounts cannot be retrieved because');
$DbgMsg = _('The SQL that was used and failed was');
$Security2Result = DB_query($Sql, $ErrMsg, $DbgMsg);
$MyUserRow = DB_fetch_array($Security2Result);
$CashSecurity = $MyUserRow['pagesecurity'];

$Sql = "SELECT pagesecurity
		FROM scripts
		WHERE scripts.script = 'SelectSalesOrder.php'";
$ErrMsg = _('The security for Aging Debtors cannot be retrieved because');
$DbgMsg = _('The SQL that was used and failed was');
$Security1Result = DB_query($Sql, $ErrMsg, $DbgMsg);
$MyUserRow = DB_fetch_array($Security1Result);
$OrderSecurity = $MyUserRow['pagesecurity'];
?>



<!-- column for database starts  -->

<!-- MAIN-CONTENT -->
<div class="main-content side-content pt-0">
	<div class="main-container container-fluid">
		<div class="inner-body">


			<!-- Page Header -->
			<div class="page-header bg-deafult p-2">
				<div>
					<h3 class="main-content-title tx-20 mg-b-5" style="font-weight:normal mb-2"> SALVAGE ERP</h3>
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a>
								<i aria-hidden="true" class="fa fa-home"></i>
							</a>
						</li>
						<li class="breadcrumb-item">
							<a>Dashboard</a>
						</li>
						<li class="active breadcrumb-item" aria-current="page">
							Salvage ERP
						</li>
					</ol>
				</div>
				<div class="d-flex">
					<div class="justify-content-center">
						<button type="button" class="btn btn-white btn-icon-text my-2 me-2">
							<i class="fa fa-download me-2"></i> Import
						</button>

						<a href="https://hybrid.leadingedgecloud.com/Dashboard_CustomerReport.php" class="btn btn-primary my-2 btn-icon-text">
							<i class="fa fa-download-cloud me-2"></i> View Customer Report
						</a>
					</div>
				</div>
			</div>
			<!-- End Page Header --> <!--Row-->
			<div class="row row-sm">
				<div class="col-sm-12 col-lg-12 col-xl-8">
					<!--Row-->
					<div class="row row-sm  mt-lg-4">
						<div class="col-sm-12 col-lg-12 col-xl-12">
							<div class="card bg-primary custom-card card-box">
								<div class="card-body p-4">
									<div class="row align-items-center">
										<div class="offset-xl-3 offset-sm-6 col-xl-8 col-sm-6 col-12 img-bg ">
											<h4 class="d-flex mb-3">
												<span class="font-weight-bold text-white ">Good Morning, <?php echo stripslashes($_SESSION['UsersRealName']); ?></span>
											</h4>
											
											<p class="tx-white-7 mb-1">Your department is <b class="text-warning">INTERNAL CONTROL</b>, you operate
												from stock location <b class="text-warning">KADUNA HEAD OFFICE WAREHOUSE</b>, your last login was on
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;March 5, 2025, 5:47 am, keep the data flowing</p>
										</div>
										<img src="assets\js\img\Dashboard_files\work3.png" alt="user-img">
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--Row -->

					<!--Row-->
					<div class="row row-sm">
						<div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
							<div class="card custom-card">
								<div class="card-body">
									<div class="card-item">
										<div class="card-item-icon card-icon">
											<svg class="text-primary" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24" viewBox="0 0 24 24" width="24">
												<g>
													<rect height="14" opacity=".3" width="14" x="5" y="5"></rect>
													<g>
														<rect fill="none" height="24" width="24"></rect>
														<g>
															<path d="M19,3H5C3.9,3,3,3.9,3,5v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V5C21,3.9,20.1,3,19,3z M19,19H5V5h14V19z"></path>
															<rect height="5" width="2" x="7" y="12"></rect>
															<rect height="10" width="2" x="15" y="7"></rect>
															<rect height="3" width="2" x="11" y="14"></rect>
															<rect height="2" width="2" x="11" y="10"></rect>
														</g>
													</g>
												</g>
											</svg>
										</div>
										<div class="card-item-title mb-2">
											<label class="main-content-label tx-13 font-weight-bold mb-1">Total Cust. Balances</label>
											<span class="d-block tx-12 mb-0 text-muted">Total customer balanaces</span>
										</div>
										<div class="card-item-body">
											<div class="card-item-stat">
												<?php
												$Sql = "SELECT SUM(debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc) AS TotalBalance, 
													MONTHNAME(NOW()) AS CurrentMonth
													FROM debtortrans";
												$Result = DB_query($Sql);
												$Row = DB_fetch_array($Result);
												$TotalBalance = locale_number_format($Row['TotalBalance'], $CurrDecimalPlaces);
												$CurrentMonth = $Row['CurrentMonth'];
												?>
												<h6 class="font-weight-bold "><?php echo $TotalBalance; ?></h6>
												<small><b class="text-success">As @ </b><?php echo $CurrentMonth; ?></small>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div><?php
								$percentDue = ($TotDue / $TotBal) * 100;
								$percentOD2 = ($TotOD2 / $TotBal) * 100;
								?>
						<div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
							<div class="card custom-card">
								<div class="card-body">
									<div class="card-item">
										<div class="card-item-icon card-icon">
											<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
												<path d="M0 0h24v24H0V0z" fill="none"></path>
												<path d="M12 4c-4.41 0-8 3.59-8 8 0 1.82.62 3.49 1.64 4.83 1.43-1.74 4.9-2.33 6.36-2.33s4.93.59 6.36 2.33C19.38 15.49 20 13.82 20 12c0-4.41-3.59-8-8-8zm0 9c-1.94 0-3.5-1.56-3.5-3.5S10.06 6 12 6s3.5 1.56 3.5 3.5S13.94 13 12 13z" opacity=".3"></path>
												<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM7.07 18.28c.43-.9 3.05-1.78 4.93-1.78s4.51.88 4.93 1.78C15.57 19.36 13.86 20 12 20s-3.57-.64-4.93-1.72zm11.29-1.45c-1.43-1.74-4.9-2.33-6.36-2.33s-4.93.59-6.36 2.33C4.62 15.49 4 13.82 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 1.82-.62 3.49-1.64 4.83zM12 6c-1.94 0-3.5 1.56-3.5 3.5S10.06 13 12 13s3.5-1.56 3.5-3.5S13.94 6 12 6zm0 5c-.83 0-1.5-.67-1.5-1.5S11.17 8 12 8s1.5.67 1.5 1.5S12.83 11 12 11z"></path>
											</svg>
										</div>
										<div class="card-item-title mb-2">
											<label class="main-content-label tx-13 font-weight-bold mb-1">New
												Total Overdue</label>
											<span class="d-block tx-12 mb-0 text-muted">Total current overdue</span>
										</div>
										<div class="card-item-body">
											<div class="card-item-stat">
												<?php
												$Sql = "SELECT SUM(debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc) AS TotalOverdue, 
													MONTHNAME(NOW()) AS CurrentMonth
													FROM debtortrans
													WHERE (debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc) > 0";
												$Result = DB_query($Sql);
												$Row = DB_fetch_array($Result);
												$TotalOverdue = locale_number_format($Row['TotalOverdue'], $CurrDecimalPlaces);
												$CurrentMonth = $Row['CurrentMonth'];
												?>
												<h6 class="font-weight-bold"><?php echo $TotalOverdue; ?></h6>
												<small><b class="text-success">As @ </b><?php echo $CurrentMonth; ?></small>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
							<div class="card custom-card">
								<div class="card-body">
									<div class="card-item">
										<div class="card-item-icon card-icon">
											<svg class="text-primary" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
												<path d="M0 0h24v24H0V0z" fill="none"></path>
												<path d="M12 4c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8zm1.23 13.33V19H10.9v-1.69c-1.5-.31-2.77-1.28-2.86-2.97h1.71c.09.92.72 1.64 2.32 1.64 1.71 0 2.1-.86 2.1-1.39 0-.73-.39-1.41-2.34-1.87-2.17-.53-3.66-1.42-3.66-3.21 0-1.51 1.22-2.48 2.72-2.81V5h2.34v1.71c1.63.39 2.44 1.63 2.49 2.97h-1.71c-.04-.97-.56-1.64-1.94-1.64-1.31 0-2.1.59-2.1 1.43 0 .73.57 1.22 2.34 1.67 1.77.46 3.66 1.22 3.66 3.42-.01 1.6-1.21 2.48-2.74 2.77z" opacity=".3"></path>
												<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"></path>
											</svg>
										</div>
										<div class="card-item-title  mb-2">
											<label class="main-content-label tx-13 font-weight-bold mb-1">60 Days Overdue</label>
											<span class="d-block tx-12 mb-0 text-muted">Total 60day overdue
											</span>
										</div>
										<div class="card-item-body">
											<div class="card-item-stat">
												<h6 class="font-weight-bold"><?php echo locale_number_format($TotOD2, $CurrDecimalPlaces) ?></h6>
												<small><b class="text-danger">as @ </b> March</small>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--End row-->

					<!-- logic  -->
					<?php
					if (in_array($DebtorSecurity, $_SESSION['AllowedPageSecurityTokens']) or !isset($DebtorSecurity)) {
						echo '
						<div class="row row-sm">
						<div class="col-lg-12">
			<div class="card custom-card mg-b-20">
				<div class="card-body">
				
					<div class="card-header border-bottom-0 pt-0 pe-0 d-flex">
						<div>
							<div class="d-flex justify-content-between align-items-center">
								<label class="main-content-label mb-2">', _('Overdue Customer Balances'), '</label> 
								<a href="OverdueCustomerBalances.php" class="ms-2" title="View All Details">
									<i class="fa fa-eye text-primary"></i>
								</a>
							</div>
							<span class="d-block tx-12 mb-3 text-muted">A task is accomplished by
															a set deadline, and must contribute toward work-related
															objectives.</span>
						</div>
						</div>
		<div class="table-responsive tasks">
		<table class="table text-nowrap text-md-nowrap table-striped mg-b-0">
		<thead>
			<tr>
				<th class="wd-lg-10p">', _('Customer'), '</th>
				<th class="wd-lg-20p">', _('Reference'), '</th>
				<th class="wd-lg-20p">', _('Trans Date'), '</th>
				<th class="wd-lg-20p">', _('Due Date'), '</th>
				<th class="wd-lg-20p">', _('Balance'), '</th>
				<th class="wd-lg-20p">', _('Current'), '</th>
				<th class="wd-lg-20p">', _('Due Now'), '</th>
				<th class="wd-lg-20p">', '> ', $_SESSION['PastDueDays1'], ' ', _('Days Over'), '</th>
				<th class="wd-lg-20p">', '> ', $_SESSION['PastDueDays2'], ' ', _('Days Over'), '</th>
			</tr>
		</thead><tbody><tr>';
						$j = 1;
						$TotBal = 0;
						$TotCurr = 0;
						$TotDue = 0;
						$TotOD1 = 0;
						$TotOD2 = 0;
						$CurrDecimalPlaces = 2; //By default.
						if (!isset($_POST['Salesman'])) {
							$_POST['Salesman'] = '';
						}
						if ($_SESSION['SalesmanLogin'] != '') {
							$_POST['Salesman'] = $_SESSION['SalesmanLogin'];
						}
						if (trim($_POST['Salesman']) != '') {
							$SalesLimit = " AND custbranch.salesman = '" . $_POST['Salesman'] . "' ";
						} else {
							$SalesLimit = '';
						}
						$Sql = "SELECT debtorsmaster.debtorno,
							debtorsmaster.name,
							currencies.currency,
							currencies.decimalplaces,
							paymentterms.terms,
							debtorsmaster.creditlimit,
							holdreasons.dissallowinvoices,
							holdreasons.reasondescription,
							SUM(
								debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
							) AS balance,
							SUM(
								CASE WHEN (paymentterms.daysbeforedue > 0)
								THEN
								CASE WHEN (TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate)) >= paymentterms.daysbeforedue
								THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
								ELSE 0 END
								ELSE
								CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(debtortrans.trandate), paymentterms.dayinfollowingmonth)) >= 0
								THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
								END
							) AS due,
							SUM(
								CASE WHEN (paymentterms.daysbeforedue > 0)
								THEN
								CASE WHEN (TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate)) > paymentterms.daysbeforedue AND TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) >= (paymentterms.daysbeforedue + " . $_SESSION['PastDueDays1'] . ")
								THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
								ELSE
								CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(debtortrans.trandate), paymentterms.dayinfollowingmonth)) >= " . $_SESSION['PastDueDays1'] . "
								THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
								ELSE 0 END
								END
							) AS overdue1,
							SUM(
								CASE WHEN (paymentterms.daysbeforedue > 0)
								THEN
								CASE WHEN (TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate)) > paymentterms.daysbeforedue AND TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) >= (paymentterms.daysbeforedue + " . $_SESSION['PastDueDays2'] . ")
								THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
								ELSE
								CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(debtortrans.trandate), paymentterms.dayinfollowingmonth)) >= " . $_SESSION['PastDueDays2'] . "
								THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
								ELSE 0 END
								END
							) AS overdue2
							FROM debtorsmaster,
								paymentterms,
								holdreasons,
								currencies,
								debtortrans,
								custbranch
							WHERE debtorsmaster.paymentterms = paymentterms.termsindicator
								AND debtorsmaster.currcode = currencies.currabrev
								AND debtorsmaster.holdreason = holdreasons.reasoncode
								AND debtorsmaster.debtorno = debtortrans.debtorno
								AND custbranch.debtorno=debtorsmaster.debtorno
								AND custbranch.branchcode=debtortrans.branchcode
								" . $SalesLimit . "
							GROUP BY debtorsmaster.debtorno,
								debtorsmaster.name,
								currencies.currency,
								paymentterms.terms,
								paymentterms.daysbeforedue,
								paymentterms.dayinfollowingmonth,
								debtorsmaster.creditlimit,
								holdreasons.dissallowinvoices,
								holdreasons.reasondescription
							HAVING
								ROUND(ABS(SUM(debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc)),currencies.decimalplaces) > 0
							ORDER BY debtorsmaster.debtorno DESC
							LIMIT 2";

						$CustomerResult = DB_query($Sql, '', '', False, False); /*dont trap errors handled below*/

					 // Limit for the number of invoices to display

						if (DB_error_no() != 0) {
							prnMsg(_('The customer details could not be retrieved by the SQL because') . ' ' . DB_error_msg(), 'error');
							echo '<br /><a href="' . $RootPath . '/index.php">' . _('Back to the menu') . '</a>';
							if ($debug == 1) {
								echo '<br />', $Sql;
							}
							include('includes/footer.php');
							exit;
						}

						while ($AgedAnalysis = DB_fetch_array($CustomerResult)) {
							$CurrDecimalPlaces = $AgedAnalysis['decimalplaces'];
							$DisplayDue = locale_number_format($AgedAnalysis['due'] - $AgedAnalysis['overdue1'], $CurrDecimalPlaces);
							$DisplayCurrent = locale_number_format($AgedAnalysis['balance'] - $AgedAnalysis['due'], $CurrDecimalPlaces);
							$DisplayBalance = locale_number_format($AgedAnalysis['balance'], $CurrDecimalPlaces);
							$DisplayOverdue1 = locale_number_format($AgedAnalysis['overdue1'] - $AgedAnalysis['overdue2'], $CurrDecimalPlaces);
							$DisplayOverdue2 = locale_number_format($AgedAnalysis['overdue2'], $CurrDecimalPlaces);
							if ($DisplayDue <> 0 or $DisplayOverdue1 <> 0 or $DisplayOverdue2 <> 0) {
								$TotBal += $AgedAnalysis['balance'];
								$TotCurr += ($AgedAnalysis['balance'] - $AgedAnalysis['due']);
								$TotDue += ($AgedAnalysis['due'] - $AgedAnalysis['overdue1']);
								$TotOD1 += ($AgedAnalysis['overdue1'] - $AgedAnalysis['overdue2']);
								$TotOD2 += $AgedAnalysis['overdue2'];
								echo '
					<tr class="main-row">
						<td class="font-weight-semibold d-flex"><a href="CustomerInquiry.php?CustomerID=', $AgedAnalysis['debtorno'], '"><b>', $AgedAnalysis['debtorno'], ' - ', $AgedAnalysis['name'], '</b></a></td>
						<td class="text-nowrap"><b>', $DisplayBalance, '</b></td>
						<td class="text-nowrap"><b>', $DisplayCurrent, '</b></td>
						<td class="text-nowrap" style="color:orange;"><b>', $DisplayDue, '</b></td>
						<td class="text-primary" style="color:red;"><b>', $DisplayOverdue1, '</b></td>
						<td class="text-primary" style="color:red;"><b>', $DisplayOverdue2, '</b></td>
					</tr>
					<tr class="sub-row" style="display:none;">
						<td colspan="6">
							<div class="sub-row-content">
								<p>Additional details for customer: ', $AgedAnalysis['name'], '</p>
								<!-- Add any additional content here -->
							</div>
						</td>
					</tr>
					</div>
											</div>
										</div>
									</div>';

								if ($_SESSION['SalesmanLogin'] != '') {
									$SalesLimit = " AND custbranch.salesman='" . $_SESSION['SalesmanLogin'] . "'";
								} else {
									$SalesLimit = '';
								}

								$Sql = "SELECT systypes.typename,
						debtortrans.transno,
						debtortrans.trandate,
						daysbeforedue,
						dayinfollowingmonth,
						(debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc) as balance,
						(CASE WHEN (paymentterms.daysbeforedue > 0)
							THEN
								(CASE WHEN (TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate)) >= paymentterms.daysbeforedue
								THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
								ELSE 0 END)
							ELSE
								(CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(ADDDATE(last_day(debtortrans.trandate), 1), paymentterms.dayinfollowingmonth)) >= 0
								THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
								ELSE 0 END)
						END) AS due,
						(CASE WHEN (paymentterms.daysbeforedue > 0)
							THEN
								(CASE WHEN TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) > paymentterms.daysbeforedue AND TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) >= (paymentterms.daysbeforedue + " . $_SESSION['PastDueDays1'] . ") THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END)
							ELSE
								(CASE WHEN (TO_DAYS(Now()) - TO_DAYS(ADDDATE(ADDDATE(last_day(debtortrans.trandate), 1), paymentterms.dayinfollowingmonth)) >= " . $_SESSION['PastDueDays1'] . ")
								THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
								ELSE 0 END)
						END) AS overdue1,
						(CASE WHEN (paymentterms.daysbeforedue > 0)
							THEN
								(CASE WHEN TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) > paymentterms.daysbeforedue AND TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) >= (paymentterms.daysbeforedue + " . $_SESSION['PastDueDays2'] . ")
								THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
								ELSE 0 END)
							ELSE
								(CASE WHEN (TO_DAYS(Now()) - TO_DAYS(ADDDATE(ADDDATE(last_day(debtortrans.trandate), 1), paymentterms.dayinfollowingmonth)) >= " . $_SESSION['PastDueDays2'] . ")
								THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
								ELSE 0 END)
						END) AS overdue2
				   FROM debtorsmaster,
						paymentterms,
						debtortrans,
						systypes,
						custbranch
				   WHERE systypes.typeid = debtortrans.type
						AND debtorsmaster.paymentterms = paymentterms.termsindicator
						AND debtorsmaster.debtorno = debtortrans.debtorno
						AND debtortrans.debtorno = '" . $AgedAnalysis['debtorno'] . "'
						AND ABS(debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc)>0.004
						AND custbranch.debtorno=debtorsmaster.debtorno
						AND custbranch.branchcode=debtortrans.branchcode
						" . $SalesLimit . "
					ORDER BY debtortrans.trandate
					LIMIT 1";

								$DetailResult = DB_query($Sql, '', '', False, False);
								if (DB_error_no() != 0) {
									prnMsg(_('The details of outstanding transactions for customer') . ' - ' . $AgedAnalysis['debtorno'] . ' ' . _('could not be retrieved because') . ' - ' . DB_error_msg(), 'error');
									echo '<br /><a href="' . $RootPath . '/index.php">' . _('Back to the menu') . '</a>';
									if ($debug == 1) {
										echo '<br />' . _('The SQL that failed was') . '<br />' . $Sql;
									}
									include('includes/footer.php');
									exit;
								}

								while ($DetailTrans = DB_fetch_array($DetailResult)) {
									$DisplayTranDate = ConvertSQLDate($DetailTrans['trandate']);
									$DisplayBalance = locale_number_format($DetailTrans['balance'], $CurrDecimalPlaces);
									$DisplayCurrent = locale_number_format($DetailTrans['balance'] - $DetailTrans['due'], $CurrDecimalPlaces);
									$DisplayDue = locale_number_format($DetailTrans['due'] - $DetailTrans['overdue1'], $CurrDecimalPlaces);
									$DisplayOverdue1 = locale_number_format($DetailTrans['overdue1'] - $DetailTrans['overdue2'], $CurrDecimalPlaces);
									$DisplayOverdue2 = locale_number_format($DetailTrans['overdue2'], $CurrDecimalPlaces);

									$DisplayDueDate = CalcDueDate($DisplayTranDate, $DetailTrans['dayinfollowingmonth'], $DetailTrans['daysbeforedue']);

									echo '<tr>
			
						<td class="text-nowrap">', _($DetailTrans['typename']), '</td>', // Should it be left (text field) ?
									'<td class="text-primary">', $DetailTrans['transno'], '</td>
						<td class="text-nowrap">', $DisplayTranDate, '</td>
						<td class="text-nowrap">', $DisplayDueDate, '</td>
						<td class="text-primary">', $DisplayBalance, '</td>
						<td class="text-primary">', $DisplayCurrent, '</td>
						<td class="text-primary" style="color:orange;">', $DisplayDue, '</td>
						<td class="text-primary" style="color:red;">', $DisplayOverdue1, '</td>
						<td class="text-primary" style="color:red;">', $DisplayOverdue2, '</td>
					<tr>';
								} //end while there are detail transactions to show

							} //has Due now or overdue

						} //end customer aged analysis while loop
						// Print totals of 'Overdue Customer Balances':
						echo '<tr>
			<td class="text-nowrap"><b>', _('Totals'), '</b></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="text-primary"><b>', locale_number_format($TotBal, $CurrDecimalPlaces), '</b></td>
			<td class="text-primary"><b>', locale_number_format($TotCurr, $CurrDecimalPlaces), '</b></td>
			<td class="text-primary"><b>', locale_number_format($TotDue, $CurrDecimalPlaces), '</b></td>
			<td class="text-primary" style="color:red;"><b>', locale_number_format($TotOD1, $CurrDecimalPlaces), '</b></td>
			<td class="text-primary" style="color:red;"><b>', locale_number_format($TotOD2, $CurrDecimalPlaces), '</b></td>
		</tr>
		</tbody></table>
		</div></div></div>';
					} //DebtorSecurity
					if (in_array($PayeeSecurity, $_SESSION['AllowedPageSecurityTokens']) or !isset($PayeeSecurity)) {
						echo '<br />
	<div class="">
			<div class="card custom-card mg-b-20">
				<div class="card-body">
				
					<div class="card-header border-bottom-0 pt-0 pe-0 d-flex">
						<div>
							<label class="main-content-label mb-2">', _('Supplier Invoices Due within 1 Month'), '</label> 
							<a href="SupplierInvoicesDue.php" class="ms-2 float-end" title="View All Details">
								<i class="fa fa-eye text-primary"></i>
							</a>
							<span class="d-block tx-12 mb-3 text-muted">A task is accomplished by
															a set deadline, and must contribute toward work-related
															objectives.</span>
						</div>
						</div>
		<div class="table-responsive tasks">
		<table class="table text-nowrap text-md-nowrap table-striped mg-b-0">
		<thead>
			<tr>
				<th>', _('Supplier'), '</th>
				<th>', _('Invoice Date'), '</th>
				<th>', _('Invoice'), '</th>
				<th>', _('Amount Due'), '</th>
				<th>', _('Due Date'), '</th>
			</tr>
		</thead><tbody><tr>';
						$SupplierID = '';
						$TotalPayments = 0;
						$TotalAccumDiffOnExch = 0;
						$AccumBalance = 0;

						$Sql = "SELECT suppliers.supplierid,
					currencies.decimalplaces AS currdecimalplaces,
					SUM(supptrans.ovamount + supptrans.ovgst - supptrans.alloc) AS balance
			FROM suppliers INNER JOIN paymentterms
			ON suppliers.paymentterms = paymentterms.termsindicator
			INNER JOIN supptrans
			ON suppliers.supplierid = supptrans.supplierno
			INNER JOIN systypes
			ON systypes.typeid = supptrans.type
			INNER JOIN currencies
			ON suppliers.currcode=currencies.currabrev
			WHERE supptrans.ovamount + supptrans.ovgst - supptrans.alloc !=0
			AND supptrans.hold=0
			GROUP BY suppliers.supplierid,
					currencies.decimalplaces
			HAVING SUM(supptrans.ovamount + supptrans.ovgst - supptrans.alloc) <> 0
			ORDER BY suppliers.supplierid DESC
			LIMIT 1";
						$SuppliersResult = DB_query($Sql);

						while ($SuppliersToPay = DB_fetch_array($SuppliersResult)) {

							$CurrDecimalPlaces = $SuppliersToPay['currdecimalplaces'];

							$Sql = "SELECT suppliers.supplierid,
						suppliers.suppname,
						systypes.typename,
						paymentterms.terms,
						supptrans.suppreference,
						supptrans.trandate,
						supptrans.rate,
						supptrans.transno,
						supptrans.type,
						supptrans.duedate,
						(supptrans.ovamount + supptrans.ovgst - supptrans.alloc) AS balance,
						(supptrans.ovamount + supptrans.ovgst ) AS trantotal,
						supptrans.diffonexch,
						supptrans.id
				FROM suppliers
				INNER JOIN paymentterms ON suppliers.paymentterms = paymentterms.termsindicator
				INNER JOIN supptrans ON suppliers.supplierid = supptrans.supplierno
				INNER JOIN systypes ON systypes.typeid = supptrans.type
				WHERE supptrans.supplierno = '" . $SuppliersToPay['supplierid'] . "'
					AND supptrans.ovamount + supptrans.ovgst - supptrans.alloc !=0
					AND supptrans.duedate <='" . Date('Y-m-d', mktime(0, 0, 0, Date('n'), Date('j') + 30, date('Y'))) . "'
					AND supptrans.hold = 0
				ORDER BY supptrans.supplierno,
					supptrans.type,
					supptrans.transno
				LIMIT 2";

							$TransResult = DB_query($Sql, '', '', false, false);
							if (DB_error_no() != 0) {
								prnMsg(_('The details of supplier invoices due could not be retrieved because') . ' - ' . DB_error_msg(), 'error');
								echo '<br /><a href="' . $RootPath . '/index.php">' . _('Back to the menu') . '</a>';
								if ($debug == 1) {
									echo '<br />' . _('The SQL that failed was') . ' ' . $Sql;
								}
								include('includes/footer.php');
								exit;
							}

							unset($Allocs);
							$Allocs = array();
							$AllocCounter = 0;

							while ($DetailTrans = DB_fetch_array($TransResult)) {
								if ($DetailTrans['supplierid'] != $SupplierID) { /*Need to head up for a new suppliers details */
									$SupplierID = $DetailTrans['supplierid'];
									$SupplierName = $DetailTrans['suppname'];
									//$AccumBalance = 0;
									$AccumDiffOnExch = 0;
									echo '
						<td class="font-weight-semibold "><b>', $DetailTrans['supplierid'], ' - ', $DetailTrans['suppname'], ' - ', $DetailTrans['terms'], '</b></td>
					';
								}

								$DisplayFormat = '';
								if ((time() - (60 * 60 * 24)) > strtotime($DetailTrans['duedate'])) {
									$DisplayFormat = ' style="color:red;"';
								}
								$DislayTranDate = ConvertSQLDate($DetailTrans['trandate']);
								$AccumBalance += $DetailTrans['balance'];
								if ($DetailTrans['type'] == 20) { // If Purchase Invoice:
									echo '<tr>
						<td class="font-weight-semibold ">', _($DetailTrans['typename']), '</td>
						<td class="text-nowrap">', $DislayTranDate, '</td>
						<td class="text-nowrap"><a href="', $RootPath, '/Payments.php?&SupplierID=', $SupplierID, '&amp;Amount=', $DetailTrans['balance'], '&amp;BankTransRef=', $DetailTrans['suppreference'], '">', $DetailTrans['suppreference'], '</a></td>
						<td class="font-weight-semibold "', $DisplayFormat, '>', locale_number_format($DetailTrans['balance'], $CurrDecimalPlaces), '</td>
						<td class="text-primary"', $DisplayFormat, '>', ConvertSQLDate($DetailTrans['duedate']), '</td>
					</tr>';
								} else { // If NOT Purchase Invoice (Creditors Payment):
									echo '<tr>
						<td class="text-primary">', _($DetailTrans['typename']), '</td>
						<td class="font-weight-semibold">', $DislayTranDate, '</td>
						<td class="text-nowrap"><a href="', $RootPath, '/SupplierAllocations.php?AllocTrans=', $DetailTrans['id'], '">', $DetailTrans['suppreference'], '</a></td>
						<td class="text-primary"', $DisplayFormat, '>', locale_number_format($DetailTrans['balance'], $CurrDecimalPlaces), '</td>
						<td class="font-weight-semibold"', $DisplayFormat, '>', ConvertSQLDate($DetailTrans['duedate']), '</td>
					</tr>';
								}
							} /*end while there are detail transactions to show */
						} /* end while there are suppliers to retrieve transactions for */

						echo '
			<td class="text-primary">', _('Grand Total Payments Due'), '</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="text-primary"><b>', locale_number_format($AccumBalance, $CurrDecimalPlaces), '</b></td>
			<td>&nbsp;</td>
		</tr>
		</tbody>
		</table>
		</div></div></div></div>';
					} //PayeeSecurity
					if (in_array($CashSecurity, $_SESSION['AllowedPageSecurityTokens']) or !isset($CashSecurity)) {
						include('includes/GLPostings.inc');
						echo '
	<div class="">
			<div class="card custom-card mg-b-20">
				<div class="card-body">
				
					<div class="card-header border-bottom-0 pt-0 pe-0 d-flex">
						<div>
							<div class="d-flex justify-content-between align-items-center">
								<label class="main-content-label mb-2">', _('Bank and Credit Card Balances'), '</label> 
								<a href="BankAndCreditCardDetails.php" class="ms-2" title="View All Details">
									<i class="fa fa-eye text-primary"></i>
								</a>
							</div>
							<span class="d-block tx-12 mb-3 text-muted">A task is accomplished by
															a set deadline, and must contribute toward work-related
															objectives.</span>
						</div>
						</div>
		<div class="table-responsive tasks">
		<table class="table text-nowrap text-md-nowrap table-striped mg-b-0">
		<thead>
		
			<tr>
				<th class="ascending">', _('GL Account'), '</th>
				<th class="ascending">', _('Account Name'), '</th>
				<th class="ascending">', _('Balance'), '</th>
			</tr>
		</thead>
		<tbody><tr>';

						$FirstPeriodSelected = GetPeriod(date($_SESSION['DefaultDateFormat']));
						$LastPeriodSelected = GetPeriod(date($_SESSION['DefaultDateFormat']));
						$SelectedPeriod = $LastPeriodSelected;
						$Sql = "SELECT bankaccounts.accountcode,
					bankaccounts.bankaccountcode,
					chartmaster.accountname,
					bankaccountname
			FROM bankaccounts
			INNER JOIN chartmaster
			ON bankaccounts.accountcode = chartmaster.accountcode
			INNER JOIN bankaccountusers
			ON bankaccounts.accountcode=bankaccountusers.accountcode
			AND userid='" . $_SESSION['UserID'] . "'
			LIMIT 4";

						$ErrMsg = _('The bank accounts set up could not be retrieved because');
						$DbgMsg = _('The SQL used to retrieve the bank account details was') . '<br />' . $Sql;
						$Result1 = DB_query($Sql, $ErrMsg, $DbgMsg);

						while ($MyRow = DB_fetch_array($Result1)) {
							/*Is the account a balance sheet or a profit and loss account */
							$Result = DB_query("SELECT pandl
						FROM accountgroups
						INNER JOIN chartmaster ON accountgroups.groupname=chartmaster.group_
						WHERE chartmaster.accountcode='" . $MyRow['accountcode'] . "'");
							$PandLRow = DB_fetch_row($Result);
							if ($PandLRow[0] == 1) {
								$PandLAccount = True;
							} else {
								$PandLAccount = False; /*its a balance sheet account */
							}

							$Sql = "SELECT counterindex,
						type,
						typename,
						gltrans.typeno,
						trandate,
						narrative,
						amount,
						periodno,
						gltrans.tag,
						tagdescription
					FROM gltrans INNER JOIN systypes
					ON systypes.typeid=gltrans.type
					LEFT JOIN tags
					ON gltrans.tag = tags.tagref
					WHERE gltrans.account = '" . $MyRow['accountcode'] . "'
					AND posted=1
					AND periodno>='" . $FirstPeriodSelected . "'
					AND periodno<='" . $LastPeriodSelected . "'
					ORDER BY periodno, gltrans.trandate, counterindex
					LIMIT 4";
							$TransResult = DB_query($Sql, $ErrMsg);
							if ($PandLAccount == True) {
								$RunningTotal = 0;
							} else { // added to fix bug with Brought Forward Balance always being zero
								$Sql = "SELECT bfwd,
						actual,
						period
					FROM chartdetails
					WHERE chartdetails.accountcode='" . $MyRow['accountcode'] . "'
					AND chartdetails.period='" . $FirstPeriodSelected . "'";

								$ErrMsg = _('The chart details for account') . ' ' . $MyRow['accountcode'] . ' ' . _('could not be retrieved');
								$ChartDetailsResult = DB_query($Sql, $ErrMsg);
								$ChartDetailRow = DB_fetch_array($ChartDetailsResult);
								$RunningTotal = $ChartDetailRow['bfwd'];
							}
							$PeriodTotal = 0;
							$PeriodNo = -9999;
							while ($MyRow2 = DB_fetch_array($TransResult)) {
								if ($MyRow2['periodno'] != $PeriodNo) {
									if ($PeriodNo != -9999) { //ie its not the first time around
										/*Get the ChartDetails balance b/fwd and the actual movement in the account for the period as recorded in the chart details - need to ensure integrity of transactions to the chart detail movements. Also, for a balance sheet account it is the balance carried forward that is important, not just the transactions*/

										$Sql = "SELECT bfwd,
							actual,
							period
						FROM chartdetails
						WHERE chartdetails.accountcode='" . $MyRow['accountcode'] . "'
						AND chartdetails.period='" . $PeriodNo . "'";
										$ErrMsg = _('The chart details for account') . ' ' . $MyRow['accountcode'] . ' ' . _('could not be retrieved');
										$ChartDetailsResult = DB_query($Sql, $ErrMsg);
										$ChartDetailRow = DB_fetch_array($ChartDetailsResult);
										if ($PeriodTotal < 0) { //its a credit balance b/fwd
											if ($PandLAccount == True) {
												$RunningTotal = 0;
											}
										} else { //its a debit balance b/fwd
											if ($PandLAccount == True) {
												$RunningTotal = 0;
											}
										}
									}
									$PeriodNo = $MyRow2['periodno'];
									$PeriodTotal = 0;
								}
								$RunningTotal += $MyRow2['amount'];
								$PeriodTotal += $MyRow2['amount'];
							}
							$DisplayBalance = locale_number_format(($RunningTotal), $_SESSION['CompanyRecord']['decimalplaces']);
							echo '
				<td class="font-weight-semibold">', $MyRow['accountcode'], ' - ', $MyRow['accountname'], '</td>
				<td class="font-weight-semibold d-flex">', $MyRow['bankaccountname'], '</td>
				<td class="text-primary">', $DisplayBalance, '</td>
			</tr>';
						} //each bank account
						echo '</tbody>
		</table>
		</div></div></div></div></div>';
					} //CashSecurity
					if (in_array($OrderSecurity, $_SESSION['AllowedPageSecurityTokens']) or !isset($OrderSecurity)) {
						echo '<br />
	<div class="col-lg-12">
			<div class="card custom-card mg-b-20">
				<div class="card-body">
				
					<div class="card-header border-bottom-0 pt-0 pe-0 d-flex">
						<div>
							<label class="main-content-label mb-2">', _('Outstanding Orders'), '</label> 
							<a href="OutstandingOrders.php" class="ms-2 float-end" title="View All Details">
								<i class="fa fa-eye text-primary"></i>
							</a>
							<span class="d-block tx-12 mb-3 text-muted">A task is accomplished by
															a set deadline, and must contribute toward work-related
															objectives.</span>
						</div>
						</div>
		<div class="table-responsive tasks">
		<table class="table text-nowrap text-md-nowrap table-striped mg-b-0">
		
		<thead>', '<tr>
				<th>', _('View Order'), '</th>
				<th>', _('Customer'), '</th>
				<th>', _('Branch'), '</th>
				<th>', _('Cust Order'), ' </th>
				<th>', _('Order Date'), '</th>
				<th>', _('Req Del Date'), '</th>
				<th>', _('Delivery To'), '</th>
				<th>', _('Order Total'), ' ', _('in'), ' ', $_SESSION['CompanyRecord']['currencydefault'], '</th>
			</tr>
		</thead><tbody>';

						$Sql = "SELECT salesorders.orderno,
						debtorsmaster.name,
						custbranch.brname,
						salesorders.customerref,
						salesorders.orddate,
						salesorders.deliverto,
						salesorders.deliverydate,
						salesorders.printedpackingslip,
						salesorders.poplaced,
						SUM(salesorderdetails.unitprice*salesorderdetails.quantity*(1-salesorderdetails.discountpercent)/currencies.rate) AS ordervalue
					FROM salesorders INNER JOIN salesorderdetails
						ON salesorders.orderno = salesorderdetails.orderno
						INNER JOIN debtorsmaster
						ON salesorders.debtorno = debtorsmaster.debtorno
						INNER JOIN custbranch
						ON debtorsmaster.debtorno = custbranch.debtorno
						AND salesorders.branchcode = custbranch.branchcode
						INNER JOIN currencies
						ON debtorsmaster.currcode = currencies.currabrev
					WHERE salesorderdetails.completed=0
					AND salesorders.quotation =0
					GROUP BY salesorders.orderno,
						debtorsmaster.name,
						custbranch.brname,
						salesorders.customerref,
						salesorders.orddate,
						salesorders.deliverto,
						salesorders.deliverydate,
						salesorders.printedpackingslip
					ORDER BY salesorders.orddate DESC, salesorders.orderno
					LIMIT 2";
						$ErrMsg = _('No orders or quotations were returned by the SQL because');
						$SalesOrdersResult = DB_query($Sql, $ErrMsg);

						/*show a table of the orders returned by the SQL */
						if (DB_num_rows($SalesOrdersResult) > 0) {
							$OrdersTotal = 0;
							$FontColor = '';

							while ($MyRow = DB_fetch_array($SalesOrdersResult)) {
								$OrderDate = ConvertSQLDate($MyRow['orddate']);
								$FormatedDelDate = ConvertSQLDate($MyRow['deliverydate']);
								$FormatedOrderValue = locale_number_format($MyRow['ordervalue'], $_SESSION['CompanyRecord']['decimalplaces']);
								if (DateDiff(Date($_SESSION['DefaultDateFormat']), $OrderDate, 'd') > 5) {
									$FontColor = ' style="color:green; font-weight:bold"';
								}

								echo '<tr class="striped_row">
					<td class="number"><a href="', $RootPath, '/OrderDetails.php?OrderNumber=', $MyRow['orderno'], '" target="_blank">', $MyRow['orderno'], '</a></td>
					<td class="text"', $FontColor, '>', $MyRow['name'], '</td>
					<td class="text"', $FontColor, '>', $MyRow['brname'], '</td>
					<td class="number"', $FontColor, '>', $MyRow['customerref'], '</td>
					<td class="centre"', $FontColor, '>', $OrderDate, '</td>
					<td class="centre"', $FontColor, '>', $FormatedDelDate, '</td>
					<td class="text"', $FontColor, '>', html_entity_decode($MyRow['deliverto'], ENT_QUOTES, 'UTF-8'), '</td>
					<td class="number"', $FontColor, '>', $FormatedOrderValue, '</td>
				</tr>';
								$OrdersTotal += $MyRow['ordervalue'];
							} // END while($MyRow=DB_fetch_array($SalesOrdersResult))
							echo '<tr>
					<td class="number" colspan="7"><b>', _('Total Order(s) Value in'), ' ', $_SESSION['CompanyRecord']['currencydefault'], ' :</b></td>
					<td class="number"><b>', locale_number_format($OrdersTotal, $_SESSION['CompanyRecord']['decimalplaces']), '</b></td>
				</tr>';
						} //rows > 0
						echo '</tbody></table></div>	</div>
										</div>
									</div>';
					}
					 //OrderSecurity
												if (in_array($CashSecurity, $_SESSION['AllowedPageSecurityTokens']) or !isset($CashSecurity)) {
													$SQL = "SELECT 
														MONTH(trandate) AS Month, 
														SUM(CASE WHEN type = 20 THEN ovamount ELSE 0 END) AS TotalPurchases,
														SUM(CASE WHEN type = 10 THEN ovamount + ovgst ELSE 0 END) AS TotalSales
													FROM (
														SELECT trandate, ovamount, 0 AS ovgst, type FROM supptrans
														UNION ALL
														SELECT trandate, ovamount, ovgst, type FROM debtortrans
													) AS combined
													WHERE YEAR(trandate) = YEAR(CURDATE())
													GROUP BY Month
													ORDER BY Month";

													$Result = DB_query($SQL);

													$data = [];

													while ($MyRow = DB_fetch_array($Result)) {
														$data[] = [
															'y' => date("F", mktime(0, 0, 0, $MyRow['Month'], 1)), // Convert month number to name
															'a' => (float) $MyRow['TotalPurchases'], // Purchases
															'b' => (float) $MyRow['TotalSales'] // Sales
														];
													}
												}

// // Convert to JSON for Morris.js
// echo json_encode($data);


					echo '
									<div class="col-sm-12 col-lg-12 col-xl-12">
										<div class="card custom-card overflow-hidden">
											<div class="card-header border-bottom-0">
												<div>
													<label class="main-content-label mb-2">Total Sales &amp; Purchases For The Year 2025</label> <span class="d-block tx-12 mb-0 text-muted">This graphical presentation of monthly
														completed sales and purchases 
														</span>
												</div>
											</div>
											<div class="card-body ps-0">
												<div class="">
											<div class="morris-wrapper-demo" id="morrisBar1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
											</div>
											<div class="morris-hover morris-default-style" style="display: none;"></div>
												</div>
											</div>

										</div>
									</div>
									<!-- col end -->
									
									
								<!-- col end -->
							</div><!-- Row end -->
							</div><!-- col end -->
							<div class="col-sm-12 col-lg-12 col-xl-4 mt-xl-4">
								<div class="card custom-card card-dashboard-calendar pb-0">
									<label class="main-content-label mb-2 pt-1">Recent Sales Transactions</label>
									<span class="d-block tx-12 mb-2 text-muted">Projects where development work is on
										completion</span>
									<table class="table table-hover m-b-0 transcations mt-2">
										<tbody>
											';
											$Sql = "SELECT debtorsmaster.name AS CustomerName, 
															custbranch.brname AS Location, 
															debtortrans.ovamount + debtortrans.ovgst AS Amount, 
															debtortrans.trandate AS TransactionDate
													FROM debtortrans
													INNER JOIN debtorsmaster ON debtortrans.debtorno = debtorsmaster.debtorno
													INNER JOIN custbranch ON debtortrans.branchcode = custbranch.branchcode
													ORDER BY debtortrans.trandate DESC
													LIMIT 4";
											$Result = DB_query($Sql);

											while ($Row = DB_fetch_array($Result)) {
												$CustomerName = $Row['CustomerName'];
												$Location = $Row['Location'];
												$Amount = locale_number_format($Row['Amount'], $_SESSION['CompanyRecord']['decimalplaces']);
												$TransactionDate = ConvertSQLDate($Row['TransactionDate']);
												echo '
												<tr style="border-bottom:1px solid #f7f7f7">
													<td class="wd-5p" width="10%">
														<div class="main-img-user avatar-md">
															<img alt="avatar" class="rounded-circle me-3" src="assets\js\img\Dashboard_files\offer.png">
														</div>
													</td>
													<td>
														<div class="d-flex align-middle ms-3">
															<div class="d-inline-block">
																<h6 class="mb-0 tx-12">' . $CustomerName . '</h6>
																<small class="mb-0 tx-11 text-muted">Location: ' . $Location . '</small>
															</div>
														</div>
													</td>
													<td class="text-end">
														<div class="d-inline-block">
															<h6 class="mb-2 tx-15 font-weight-semibold">₦' . $Amount . '</h6>
															<p class="mb-0 tx-11 text-muted">' . $TransactionDate . '</p>
														</div>
													</td>
												</tr>';
											}
											echo '
										</tbody>
									</table>
								</div>
								<div class="card custom-card">
									<div class="card-body">
										<div class="row row-sm">
											<div class="col-6">
												<div class="card-item-title">
													<label class="main-content-label tx-13 font-weight-bold mb-2">Systems Users</label>
													<span class="d-block tx-12 mb-0 text-muted">the total number of system users</span>
												</div>
												';
												$Sql = "SELECT COUNT(*) AS UserCount FROM www_users";
												$Result = DB_query($Sql);
												$Row = DB_fetch_array($Result);
												$UserCount = $Row['UserCount'];
												$CurrentDate = date('jS l, F Y');
												echo '
												<p class="mb-0 tx-24 mt-2"><b class="text-primary">' . $UserCount . '</b></p>
												<a href="javascript:void(0)" class="text-muted">' .$CurrentDate . '</a>
											</div>
											<div class="col-6">
												<img src="assets\js\img\Dashboard_files\work.png" alt="image" class="best-emp">
											</div>
										</div>
									</div>
								</div>
								<div class="card custom-card">
									<div class="card-header border-bottom-0 pb-0 d-flex ps-3 ms-1">
										<div>
											<label class="main-content-label mb-2 pt-2">Supplier and Customer Ledgers</label>
											<span class="d-block tx-12 mb-2 text-muted">Supplier and Customer ledgers listing on the system</span>
										</div>
									</div>
									<div class="card-body pt-2 mt-0">
										<div class="list-card">
											<div class="card-item">
												<div class="card-item-body">
													';
													$Sql = "SELECT COUNT(*) AS TotalCustomers, 
																   SUM(debtortrans.ovamount + debtortrans.ovgst - debtortrans.alloc) AS TotalVolume
															FROM debtorsmaster
															LEFT JOIN debtortrans ON debtorsmaster.debtorno = debtortrans.debtorno";
													$Result = DB_query($Sql);
													$Row = DB_fetch_array($Result);
													$TotalCustomers = $Row['TotalCustomers'];
													$TotalVolume = locale_number_format($Row['TotalVolume'], $_SESSION['CompanyRecord']['decimalplaces']);
													echo '
													<div class="card-item-stat">
														<small class="tx-12 text-primary font-weight-semibold">Total Customer</small>
														<h6 class="mt-2"><span class="fs-30 me-2">' .$TotalCustomers . '</span>
															<span class="badge bg-success">100%</span>
														</h6>
														<span class="text-muted">' . $TotalVolume . ' in transaction volume</span>
													</div>
												</div>
											</div>
										</div>
										<div class="list-card mb-0">
											<div class="card-item">
												<div class="card-item-body">
													';
													$Sql = "SELECT COUNT(*) AS TotalSuppliers, 
																   SUM(supptrans.ovamount + supptrans.ovgst - supptrans.alloc) AS TotalVolume
															FROM suppliers
															LEFT JOIN supptrans ON suppliers.supplierid = supptrans.supplierno";
													$Result = DB_query($Sql);
													$Row = DB_fetch_array($Result);
													$TotalSuppliers = $Row['TotalSuppliers'];
													$TotalVolume = locale_number_format($Row['TotalVolume'], $_SESSION['CompanyRecord']['decimalplaces']);
													echo '
													<div class="card-item-stat">
														<small class="tx-12 text-primary font-weight-semibold">Total Supplier</small>
														<h6 class="mt-2"><span class="fs-30 me-2">' . $TotalSuppliers . '</span>
															<span class="badge bg-success">100%</span>
														</h6>
														<span class="text-muted">' .$TotalVolume. ' in transaction <br>volume</span>
													</div>
												</div>
											</div>
										</div>
										
									</div>
								</div>
								<div class="card custom-card">
											<div class="card-body">
												<div class="card-widget custom-back">
													<label class="main-content-label mb-3 pt-1">Total Outstanding Orders</label>
													';
													$Sql = "SELECT COUNT(*) AS TotalOrders, 
																   SUM(salesorderdetails.unitprice * salesorderdetails.quantity * (1 - salesorderdetails.discountpercent)) AS TotalVolume
															FROM salesorders
															INNER JOIN salesorderdetails ON salesorders.orderno = salesorderdetails.orderno
															WHERE salesorderdetails.completed = 0 AND salesorders.quotation = 0";
													$Result = DB_query($Sql);
													$Row = DB_fetch_array($Result);
													$TotalOrders = $Row['TotalOrders'];
													$TotalVolume = locale_number_format($Row['TotalVolume'], $_SESSION['CompanyRecord']['decimalplaces']);
													echo '
													<h2 class="text-end">
														<i class="mdi mdi-cart icon-size float-start text-primary"></i>
														<span class="font-weight-bold">' .  $TotalOrders . '</span>
													</h2>
													<p class="mb-0 text-muted">
														Outstanding Orders Value
														<span class="float-end">' . $TotalVolume . '</span>
													</p>
												</div>
											</div>
										</div>
										<div class="card custom-card">
											<div class="card custom-card overflow-hidden">
												<div class="card-header border-bottom-0 pb-0">
													<div>
														<div class="d-md-flex">
															<label class="main-content-label my-auto pt-2">Payments Due</label>
														</div>
														<span class="d-block tx-12 mt-2 mb-0 text-muted">Supplier Accounts</span>
													</div>
												</div>
												<div class="card-body" style="padding-top:8px;">
													<div class="text-start">
														';
														$Sql = "SELECT SUM(supptrans.ovamount + supptrans.ovgst - supptrans.alloc) AS TotalPaymentsDue
															FROM supptrans
															WHERE supptrans.ovamount + supptrans.ovgst - supptrans.alloc > 0";
														$Result = DB_query($Sql);
														$Row = DB_fetch_array($Result);
														$TotalPaymentsDue = locale_number_format($Row['TotalPaymentsDue'], $_SESSION['CompanyRecord']['decimalplaces']);
														echo '
														<h6 class="font-weight-bold me-3 mb-2 text-primary">₦' .$TotalPaymentsDue . '</h6>
														<p class="tx-13 my-auto text-muted">Payment due to suppliers</p>
													</div>
												</div>
											</div>
										</div>
										<div class="card custom-card">
											<div class="card custom-card overflow-hidden">
												<div class="card-header border-bottom-0 pb-0">
													<div>
														<div class="d-md-flex">
															<label class="main-content-label my-auto pt-2">Creditors Payment</label>
														</div>
														<span class="d-block tx-12 mt-2 mb-0 text-muted">Supplier Accounts</span>
													</div>
												</div>
												<div class="card-body" style="padding-top:8px;">
													<div class="text-start">
														';
														$Sql = "SELECT SUM(supptrans.ovamount + supptrans.ovgst - supptrans.alloc) AS TotalCreditorsPayment
															FROM supptrans
															WHERE supptrans.ovamount + supptrans.ovgst - supptrans.alloc < 0";
														$Result = DB_query($Sql);
														$Row = DB_fetch_array($Result);
														$TotalCreditorsPayment = locale_number_format(abs($Row['TotalCreditorsPayment']), $_SESSION['CompanyRecord']['decimalplaces']);
														echo '
														<h6 class="font-weight-bold me-3 mb-2 text-primary">₦' . $TotalCreditorsPayment . '</h6>
														<p class="tx-13 my-auto text-muted">Payment made to suppliers</p>
													</div>
												</div>
											</div>
										</div>
										<div class="card custom-card">
											<a class="card  card-body bg-primary tx-white" style="padding:14px" href="Dashboard_SupplierReport.php">
												<i class="fa fa-download-cloud me-2 mb-2"></i>
												View Supplier Invoices Due within 1 Month
											</a>
										</div>
							</div>
							<!-- col end -->
						</div>
						<!-- Row end -->    <div style="clear:both">&nbsp;</div><div id="MessageContainerFoot"></div><div class="centre noprint"><form action="/Dashboard.php" method="post"><input name="FormID" type="hidden" value="59800b8b61aa66ace0ec31e7ba9e1d273c12fa5f"><input name="ScriptName" type="hidden" value=""><input name="Title" type="hidden" value="Dashboard"></form></div> </div>
            </div>
            </div>';
                    
                
             
					// include('includes/footer.php');
					include('includes/footer.php');
					?>

					<!--row-->