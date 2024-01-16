<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use function Lambdish\Phunctional\repeat;


include ('includes/session.php');
$Title = _('Orders Invoiced Report');

$InputError=0;

// $_SESSION['DefaultDateFormat']
if ($_POST) {
	if (isset($_POST['FromDate']) AND !Is_date($_POST['FromDate'])){
		$msg = _('The date from must be specified in the format') . ' ' . $DefaultDateFormat;
		$InputError=1;
		unset($_POST['FromDate']);
	}
	if (isset($_POST['ToDate']) AND !Is_date($_POST['ToDate'])){
		$msg = _('The date to must be specified in the format') . ' ' . $DefaultDateFormat;
		$InputError=1;
		unset($_POST['ToDate']);
	}
	if (isset($_POST['FromDate']) and isset($_POST['ToDate']) and Date1GreaterThanDate2($_POST['FromDate'], $_POST['ToDate'])){
		$msg = _('The date to must be after the date from');
		$InputError=1;
		unset($_POST['ToDate']);
		unset($_POST['FromoDate']);
	}


}

if ($_POST && !$InputError) {
	include('includes/PDFStarter.php');
	$pdf->addInfo('Title',_('Orders Invoiced Report'));
	$pdf->addInfo('Subject',_('Orders from') . ' ' . $_POST['FromDate'] . ' ' . _('to') . ' ' . $_POST['ToDate']);
	$line_height=12;
	$PageNumber = 1;
	$TotalDiffs = 0;

	if ($_POST['CategoryID']=='All' AND $_POST['Location']=='All'){
		$sql= "SELECT salesorders.orderno,
					salesorders.debtorno,
					salesorders.branchcode,
					salesorders.customerref,
					salesorders.orddate,
					salesorders.fromstkloc,
					salesorders.printedpackingslip,
					salesorders.datepackingslipprinted,
					salesorderdetails.stkcode,
					stockmaster.description,
					stockmaster.units,
					stockmaster.decimalplaces,
					debtorsmaster.name,
					custbranch.brname,
					locations.locationname,
					SUM(salesorderdetails.quantity) AS totqty,
                    SUM(salesorderdetails.qtyinvoiced) AS totqtyinvoiced,
                    salesorderdetails.unitprice
				FROM salesorders
					INNER JOIN salesorderdetails
					ON salesorders.orderno = salesorderdetails.orderno
					INNER JOIN stockmaster
					ON salesorderdetails.stkcode = stockmaster.stockid
					INNER JOIN debtorsmaster
					ON salesorders.debtorno=debtorsmaster.debtorno
					INNER JOIN custbranch
					ON custbranch.debtorno=salesorders.debtorno
					AND custbranch.branchcode=salesorders.branchcode
					INNER JOIN locations
					ON salesorders.fromstkloc=locations.loccode
					INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
				WHERE orddate >='" . FormatDateForSQL($_POST['FromDate']) . "'
					AND orddate <='" . FormatDateForSQL($_POST['ToDate']) . "'";


	} elseif ($_POST['CategoryID']!='All' AND $_POST['Location']=='All') {
		$sql= "SELECT salesorders.orderno,
					salesorders.debtorno,
					salesorders.branchcode,
					salesorders.customerref,
					salesorders.orddate,
					salesorders.fromstkloc,
					salesorders.printedpackingslip,
					salesorders.datepackingslipprinted,
					salesorderdetails.stkcode,
					stockmaster.description,
					stockmaster.units,
					stockmaster.decimalplaces,
					debtorsmaster.name,
					custbranch.brname,
					locations.locationname,
					SUM(salesorderdetails.quantity) AS totqty,
					SUM(salesorderdetails.qtyinvoiced) AS totqtyinvoiced
				FROM salesorders
					INNER JOIN salesorderdetails
					ON salesorders.orderno = salesorderdetails.orderno
					INNER JOIN stockmaster
					ON salesorderdetails.stkcode = stockmaster.stockid
					INNER JOIN debtorsmaster
					ON salesorders.debtorno=debtorsmaster.debtorno
					INNER JOIN custbranch
					ON custbranch.debtorno=salesorders.debtorno
					AND custbranch.branchcode=salesorders.branchcode
					INNER JOIN locations
					ON salesorders.fromstkloc=locations.loccode
					INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
				WHERE stockmaster.categoryid ='" . $_POST['CategoryID'] . "'
					AND orddate >='" . FormatDateForSQL($_POST['FromDate']) . "'
					AND orddate <='" . FormatDateForSQL($_POST['ToDate']) . "'";

	} elseif ($_POST['CategoryID']=='All' AND $_POST['Location']!='All') {
		$sql= "SELECT salesorders.orderno,
					salesorders.debtorno,
					salesorders.branchcode,
					salesorders.customerref,
					salesorders.orddate,
					salesorders.fromstkloc,
					salesorders.printedpackingslip,
					salesorders.datepackingslipprinted,
					salesorderdetails.stkcode,
					stockmaster.description,
					stockmaster.units,
					stockmaster.decimalplaces,
					debtorsmaster.name,
					custbranch.brname,
					locations.locationname,
					SUM(salesorderdetails.quantity) AS totqty,
					SUM(salesorderdetails.qtyinvoiced) AS totqtyinvoiced
				FROM salesorders
					INNER JOIN salesorderdetails
					ON salesorders.orderno = salesorderdetails.orderno
					INNER JOIN stockmaster
					ON salesorderdetails.stkcode = stockmaster.stockid
					INNER JOIN debtorsmaster
					ON salesorders.debtorno=debtorsmaster.debtorno
					INNER JOIN custbranch
					ON custbranch.debtorno=salesorders.debtorno
					AND custbranch.branchcode=salesorders.branchcode
					INNER JOIN locations
					ON salesorders.fromstkloc=locations.loccode
					INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
				WHERE salesorders.fromstkloc ='" . $_POST['Location'] . "'
					AND orddate >='" . FormatDateForSQL($_POST['FromDate']) . "'
					AND orddate <='" . FormatDateForSQL($_POST['ToDate']) . "'";

	} elseif ($_POST['CategoryID']!='All' AND $_POST['location']!='All'){
		$sql= "SELECT salesorders.orderno,
					salesorders.debtorno,
					salesorders.branchcode,
					salesorders.customerref,
					salesorders.orddate,
					salesorders.fromstkloc,
					salesorderdetails.stkcode,
					stockmaster.description,
					stockmaster.units,
					stockmaster.decimalplaces,
					debtorsmaster.name,
					custbranch.brname,
					locations.locationname,
					SUM(salesorderdetails.quantity) AS totqty,
					SUM(salesorderdetails.qtyinvoiced) AS totqtyinvoiced
				FROM salesorders
					INNER JOIN salesorderdetails
					ON salesorders.orderno = salesorderdetails.orderno
					INNER JOIN stockmaster
					ON salesorderdetails.stkcode = stockmaster.stockid
					INNER JOIN debtorsmaster
					ON salesorders.debtorno=debtorsmaster.debtorno
					INNER JOIN custbranch
					ON custbranch.debtorno=salesorders.debtorno
					AND custbranch.branchcode=salesorders.branchcode
					INNER JOIN locations
					ON salesorders.fromstkloc=locations.loccode
					INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
				WHERE stockmaster.categoryid ='" . $_POST['CategoryID'] . "'
					AND salesorders.fromstkloc ='" . $_POST['Location'] . "'
					AND orddate >='" . FormatDateForSQL($_POST['FromDate']) . "'
					AND orddate <='" . FormatDateForSQL($_POST['ToDate']) . "'";
	}

	if ($_SESSION['SalesmanLogin'] != '') {
		$sql .= " AND salesorders.salesperson='" . $_SESSION['SalesmanLogin'] . "'";
	}

	$sql .= " GROUP BY salesorders.orderno,
						salesorders.debtorno,
						salesorders.branchcode,
						salesorders.customerref,
						salesorders.orddate,
						salesorders.fromstkloc,
						salesorderdetails.stkcode,
						stockmaster.description,
						stockmaster.units,
						stockmaster.decimalplaces
				ORDER BY salesorders.orderno";

	$Result=DB_query($sql,'','',false,false); //dont trap errors here

	if (DB_error_no()!=0){
		include('includes/header.php');
		prnMsg(_('An error occurred getting the orders details'),'',_('Database Error'));
		if ($debug==1){
			prnMsg( _('The SQL used to get the orders that failed was') . '<br />' . $sql, '',_('Database Error'));
		}
		include ('includes/footer.php');
		exit;
	} elseif (DB_num_rows($Result)==0){
		include('includes/header.php');
		prnMsg(_('There were no orders found in the database within the period from') . ' ' . $_POST['FromDate'] . ' ' . _('to') . ' '. $_POST['ToDate'] . '. ' . _('Please try again selecting a different date range'), 'warn');
		if ($debug==1) {
			prnMsg(_('The SQL that returned no rows was') . '<br />' . $sql,'',_('Database Error'));
		}
		include('includes/footer.php');
		exit;
	}


	$OrderNo =0; /*initialise */
	$AccumTotalInv =0;
	$AccumOrderTotal =0;

	if ($_POST['CreateSpreadsheet']) {
		require_once(__DIR__ . '/vendor/autoload.php');

		$spreadsheet = new Spreadsheet();

        /** Spreadsheet rows */
        $output = [];

        $orders = [];
        while ($current = DB_fetch_array($Result))
            $orders[] = $current;
        // printf("<pre>%s</pre>", $sql);
        // exit;
        $output[] = [
            _('Order'),
            _('Customer'),
            _('Branch'),
            _('Contract Number/Description'),
            _('Customer Ref (LPO Number)'),
            _('Ord Date'),
            _('Location'),
            _('Code'),
            _('Order Price'),
            _('Ordered'),
            _('Invoiced'),
            _('Outstanding'),
        ];
        foreach ($orders as $order) {
			$output[] = [
				$order['orderno'],
				$order['name'],
				$order['brname'],
				$order['description'],
				$order['customerref'],
				$order['orddate'],
				$order['locationname'],
                $order['stkcode'],
                $order['unitprice'],
                $order['totqty'],
                $order['totqtyinvoiced'],
                ($order['totqty']> $order['totqtyinvoiced'])
                    ? ($order['totqty']-$order['totqtyinvoiced'])
                    : _('Complete')
			];

            //
                // $output[] = array_keys($sectionHeader);
                // $output[] = array_values($sectionHeader);
                //
                // foreach ($orders as $myrow) {
                //     $output[] = [_('Code'), _('Description'), _('Ordered'), _('Invoiced'), _('Outstanding')];
                //     $output[] = [
                //             /* Code */ $myrow['stkcode'] , /* Description */ $myrow['description'] ,
                //             /* Ordered */ locale_number_format($myrow['totqty'], $myrow['decimalplaces']) ,
                //             /* Invoiced */ locale_number_format($myrow['totqtyinvoiced'], $myrow['decimalplaces']),
                //             /* Outstanding */ ($myrow['totqty']> $myrow['totqtyinvoiced'])
                //                 ? locale_number_format($myrow['totqty']-$myrow['totqtyinvoiced'],$myrow['decimalplaces'])
                //                 : _('Complete'),
                //     ];
                // }
                //
                // $output[] = []; // Add empty row to spreadsheet
            //
        }
        // printf("<pre>%s</pre>", json_encode($output, 128));
        // exit;
		$activeWorksheet = $spreadsheet->getActiveSheet()->fromArray($output);
		// $activeWorksheet->setCellValue('A1', 'Hello World !');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header(sprintf('Content-Disposition: attachment;filename=%s.xlsx', $_SESSION['DatabaseName'] . '_OrdersInvoiced_' . date('Y-m-d')));
        (new Xlsx($spreadsheet))->save('php://output');
        exit;

	} else {

		include ('includes/PDFOrdersInvoicedPageHeader.inc');

		while ($myrow=DB_fetch_array($Result)){

			if($OrderNo != $myrow['orderno']){
                // FIXME: This should not be here. Move to end of loop. Will not
                // print any output (Order Total) for last Order Item.
				if ($AccumOrderTotal !=0){
					$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,120,$FontSize,_('Total Invoiced for order') . ' ' . $OrderNo , 'left');
					$LeftOvers = $pdf->addTextWrap($Left_Margin+360,$YPos,80,$FontSize,locale_number_format($AccumOrderTotal,$_SESSION['CompanyRecord']['decimalplaces']), 'right');
					$YPos -= ($line_height);
					$AccumOrderTotal =0;
				}

				$pdf->line($XPos, $YPos,$Page_Width-$Right_Margin, $YPos);

				$YPos -= $line_height;
				/*Set up headings */
				/*draw a line */

				$LeftOvers = $pdf->addTextWrap($Left_Margin+2,$YPos,40,$FontSize,_('Order'), 'left');
				$LeftOvers = $pdf->addTextWrap($Left_Margin+40,$YPos,150,$FontSize,_('Customer'), 'left');
				$LeftOvers = $pdf->addTextWrap($Left_Margin+190,$YPos,110,$FontSize,_('Branch'), 'left');
				$LeftOvers = $pdf->addTextWrap($Left_Margin+300,$YPos,60,$FontSize,_('Customer Ref'), 'left');
				$LeftOvers = $pdf->addTextWrap($Left_Margin+360,$YPos,60,$FontSize,_('Ord Date'), 'left');
				$LeftOvers = $pdf->addTextWrap($Left_Margin+420,$YPos,80,$FontSize,_('Location'), 'left');

				$YPos-=$line_height;

				/*draw a line */
				$pdf->line($XPos, $YPos,$Page_Width-$Right_Margin, $YPos);
				$pdf->line($XPos, $YPos-$line_height*2,$XPos, $YPos+$line_height*2);
				$pdf->line($Page_Width-$Right_Margin, $YPos-$line_height*2,$Page_Width-$Right_Margin, $YPos+$line_height*2);

				$YPos -= ($line_height);
				if ($YPos - (2 *$line_height) < $Bottom_Margin){
					/*Then set up a new page */
					$PageNumber++;
					include ('includes/PDFOrdersInvoicedPageHeader.inc');
				} /*end of new page header  */
			}

			if ($myrow['orderno']!=$OrderNo OR $NewPage){

				$LeftOvers = $pdf->addTextWrap($Left_Margin+2,$YPos,40,$FontSize,$myrow['orderno'], 'left');
				$LeftOvers = $pdf->addTextWrap($Left_Margin+40,$YPos,150,$FontSize,html_entity_decode($myrow['name']), 'left');
				$LeftOvers = $pdf->addTextWrap($Left_Margin+190,$YPos,110,$FontSize,$myrow['brname'], 'left');

				$LeftOvers = $pdf->addTextWrap($Left_Margin+300,$YPos,60,$FontSize,$myrow['customerref'], 'left');
				$LeftOvers = $pdf->addTextWrap($Left_Margin+360,$YPos,60,$FontSize,ConvertSQLDate($myrow['orddate']), 'left');
				$LeftOvers = $pdf->addTextWrap($Left_Margin+420,$YPos,80,$FontSize,$myrow['locationname'], 'left');

				if (isset($PackingSlipPrinted)) {
					$LeftOvers = $pdf->addTextWrap($Left_Margin+400,$YPos,100,$FontSize,$PackingSlipPrinted, 'left');
				}

				$YPos -= ($line_height);
				$pdf->line($XPos, $YPos,$Page_Width-$Right_Margin, $YPos);
				$YPos -= ($line_height);

			}
			$OrderNo = $myrow['orderno'];
			/*Set up the headings for the order */
			$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Code'), 'left');
			$LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,120,$FontSize,_('Description'), 'left');
			$LeftOvers = $pdf->addTextWrap($Left_Margin+180,$YPos,60,$FontSize,_('Ordered'), 'right');
			$LeftOvers = $pdf->addTextWrap($Left_Margin+240,$YPos,60,$FontSize,_('Invoiced'), 'right');
			$LeftOvers = $pdf->addTextWrap($Left_Margin+320,$YPos,60,$FontSize,_('Outstanding'), 'left');
			$YPos -= ($line_height);
			$NewPage = false;

			$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,$myrow['stkcode'], 'left');
			$LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,120,$FontSize,$myrow['description'], 'left');
			$LeftOvers = $pdf->addTextWrap($Left_Margin+180,$YPos,60,$FontSize,locale_number_format($myrow['totqty'],$myrow['decimalplaces']), 'right');
			$LeftOvers = $pdf->addTextWrap($Left_Margin+240,$YPos,60,$FontSize,locale_number_format($myrow['totqtyinvoiced'],$myrow['decimalplaces']), 'right');

			if ($myrow['totqty']>$myrow['totqtyinvoiced']){
				$LeftOvers = $pdf->addTextWrap($Left_Margin+320,$YPos,60,$FontSize,locale_number_format($myrow['totqty']-$myrow['totqtyinvoiced'],$myrow['decimalplaces']), 'right');
			} else {
				$LeftOvers = $pdf->addTextWrap($Left_Margin+320,$YPos,60,$FontSize,_('Complete'), 'left');
			}

			$YPos -= ($line_height);
			if ($YPos - (2 *$line_height) < $Bottom_Margin){
				/*Then set up a new page */
				$PageNumber++;
				include ('includes/PDFOrdersInvoicedPageHeader.inc');
			} /*end of new page header  */


			/*OK now get the invoices where the item was charged */
			$sql = "SELECT debtortrans.order_,
							systypes.typename,
							debtortrans.transno,
							debtortrans.trandate,
							stockmoves.price *(1-stockmoves.discountpercent) AS netprice,
							-stockmoves.qty AS quantity,
							stockmoves.narrative
						FROM debtortrans INNER JOIN stockmoves
							ON debtortrans.type = stockmoves.type
							AND debtortrans.transno=stockmoves.transno
							INNER JOIN systypes ON debtortrans.type=systypes.typeid
						WHERE debtortrans.order_ ='" . $OrderNo . "'
						AND stockmoves.stockid ='" . $myrow['stkcode'] . "'";

			$InvoicesResult =DB_query($sql);
			if (DB_num_rows($InvoicesResult)>0){
				$LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,60,$FontSize,_('Date'),'center');
				$LeftOvers = $pdf->addTextWrap($Left_Margin+150,$YPos,90,$FontSize,_('Transaction Number'), 'center');
				$LeftOvers = $pdf->addTextWrap($Left_Margin+240,$YPos,60,$FontSize,_('Quantity'), 'center');
				$LeftOvers = $pdf->addTextWrap($Left_Margin+300,$YPos,60,$FontSize,_('Price'), 'center');
				$LeftOvers = $pdf->addTextWrap($Left_Margin+380,$YPos,60,$FontSize,_('Total'), 'centre');
				$LeftOvers = $pdf->addTextWrap($Left_Margin+450,$YPos,100,$FontSize,_('Narrative'), 'centre');
				$YPos -= ($line_height);

                while ($InvRow=DB_fetch_array($InvoicesResult)){

                    $ValueInvoiced = $InvRow['netprice']*$InvRow['quantity'];
                    $LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,60,$FontSize,ConvertSQLDate($InvRow['trandate']),'center');

                    $LeftOvers = $pdf->addTextWrap($Left_Margin+150,$YPos,90,$FontSize,$InvRow['typename'] . ' ' . $InvRow['transno'], 'left');
                    $LeftOvers = $pdf->addTextWrap($Left_Margin+240,$YPos,60,$FontSize,locale_number_format($InvRow['quantity'],$myrow['decimalplaces']), 'right');
                    $LeftOvers = $pdf->addTextWrap($Left_Margin+300,$YPos,60,$FontSize,locale_number_format($InvRow['netprice'],$_SESSION['CompanyRecord']['decimalplaces']), 'right');
                    $LeftOvers = $pdf->addTextWrap($Left_Margin+360,$YPos,80,$FontSize,locale_number_format($ValueInvoiced,$_SESSION['CompanyRecord']['decimalplaces']), 'right');
                    $LeftOvers = $pdf->addTextWrap($Left_Margin+450,$YPos,100,$FontSize,$InvRow['narrative'], 'center');
                    if (mb_strlen($LeftOvers)>0) {

                        $YPos -= ($line_height);

                        if ($YPos - (2 *$line_height) < $Bottom_Margin){
                            /*Then set up a new page */
                            $PageNumber++;
                            include ('includes/PDFOrdersInvoicedPageHeader.inc');
                        } /*end of new page header  */
                        $LeftOvers = $pdf->addTextWrap($Left_Margin+450,$YPos,100,$FontSize,$LeftOvers, 'center');
                    }
                    $YPos -= ($line_height);

                    if ($YPos - (2 *$line_height) < $Bottom_Margin){
                        /*Then set up a new page */
                        $PageNumber++;
                        include ('includes/PDFOrdersInvoicedPageHeader.inc');
                    } /*end of new page header  */
                    $AccumOrderTotal += $ValueInvoiced;
                    $AccumTotalInv += $ValueInvoiced;
                }
			}

			$YPos -= ($line_height);
			if ($YPos - (2 *$line_height) < $Bottom_Margin){
				/*Then set up a new page */
					$PageNumber++;
				include ('includes/PDFOrdersInvoicedPageHeader.inc');
			} /*end of new page header  */
		} /* end of while there are invoiced orders to print */

		$YPos -= ($line_height);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+260,$YPos,100,$FontSize,_('GRAND TOTAL INVOICED'), 'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+360,$YPos,80,$FontSize,locale_number_format($AccumTotalInv,$_SESSION['CompanyRecord']['decimalplaces']), 'right');
		$YPos -= ($line_height);

		$pdf->OutputD($_SESSION['DatabaseName'] . '_OrdersInvoiced_' . date('Y-m-d') . '.pdf');
		$pdf->__destruct();

	}

} else {
	include ('includes/header.php');
	if ($InputError==1){
		prnMsg($msg,'error');
	}

	echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/transactions.png" title="' . $Title . '" alt="" />' . ' '
		. _('Orders Invoiced Report') . '</p>';

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
	echo '<div>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		<table class="selection">
		<tr>
			<td>' . _('Enter the date from which orders are to be listed') . ':</td>
			<td><input type="text" alt="d/m/Y" required="required" autofocus="autofocus" class="date form-control" name="FromDate" maxlength="10" size="11" value="' . Date($_SESSION['DefaultDateFormat'], Mktime(0,0,0,Date('m'),Date('d')-1,Date('y'))) . '" /></td>
		</tr>
		<tr>
			<td>' . _('Enter the date to which orders are to be listed') . ':</td>
			<td><input type="text" alt="d/m/Y" required="required" class="date form-control" name="ToDate" maxlength="10" size="11" value="' . Date($_SESSION['DefaultDateFormat']) . '" /></td>
		</tr>
		<tr>
			<td>' . _('Inventory Category') . '</td>
			<td>';

	$sql = "SELECT categorydescription, categoryid FROM stockcategory";
	$result = DB_query($sql);

	echo '<select required="required" name="CategoryID">';
	echo '<option selected="selected" value="All">' . _('Over All Categories') . '</option>';

	while ($myrow=DB_fetch_array($result)){
	echo '<option value="' . $myrow['categoryid'] . '">' . $myrow['categorydescription'] . '</option>';
	}
	echo '</select></td>
		</tr>
		<tr>
			<td>' . _('Inventory Location') . ':</td>
			<td><select required="required" name="Location">
				<option selected="selected" value="All">' . _('All Locations') . '</option>';

	$result= DB_query("SELECT locations.loccode, locationname FROM locations INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1");
	while ($myrow=DB_fetch_array($result)){
		echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
	}
	echo '</select></td>
		</tr>
		</table>
		<br />
		<div class="centre">
			<input type="submit" name="Go" value="' . _('Create PDF') . '" />
			<input type="submit" name="CreateSpreadsheet" value="' . _('Create Spreadsheet') . '" />
		</div>
		</div>
	</form>';

	include('includes/footer.php');
	exit;

}
?>
