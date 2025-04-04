<?php
// Check if the content should be wrapped in multiple divs
if ($should_wrap) {
    echo '</div></div></div></div></div></div></div>';
}
?>
<div style="clear:both">&nbsp;</div>
<div id="MessageContainerFoot">
    <?php
    // Check if there are any messages to display
    if (isset($Messages) && count($Messages) > 0) {
        foreach ($Messages as $Message) {
            // Determine the class and default message based on the message type
            switch ($Message[1]) {
                case 'error':
                    $Class = 'error';
                    $Message[2] = $Message[2] ? $Message[2] : _('ERROR') . ' ' . _('Report');
                    // Log the error message if the log severity is greater than 3
                    if (isset($_SESSION['LogSeverity']) && $_SESSION['LogSeverity'] > 3) {
                        fwrite($LogFile, date('Y-m-d h-m-s') . ',' . $Type . ',' . $_SESSION['UserID'] . ',' . trim($Msg, ',') . "\n");
                    }
                    break;
                case 'warn':
                case 'warning':
                    $Class = 'warn';
                    $Message[2] = $Message[2] ? $Message[2] : _('WARNING') . ' ' . _('Report');
                    // Log the warning message if the log severity is greater than 3
                    if (isset($_SESSION['LogSeverity']) && $_SESSION['LogSeverity'] > 3) {
                        fwrite($LogFile, date('Y-m-d h-m-s') . ',' . $Type . ',' . $_SESSION['UserID'] . ',' . trim($Msg, ',') . "\n");
                    }
                    break;
                case 'success':
                    $Class = 'success';
                    $Message[2] = $Message[2] ? $Message[2] : _('SUCCESS') . ' ' . _('Report');
                    // Log the success message if the log severity is greater than 3
                    if (isset($_SESSION['LogSeverity']) && $_SESSION['LogSeverity'] > 3) {
                        fwrite($LogFile, date('Y-m-d h-m-s') . ',' . $Type . ',' . $_SESSION['UserID'] . ',' . trim($Msg, ',') . "\n");
                    }
                    break;
                case 'info':
                default:
                    $Message[2] = $Message[2] ? $Message[2] : _('INFORMATION') . ' ' . _('Message');
                    $Class = 'info';
                    // Log the info message if the log severity is greater than 2
                    if (isset($_SESSION['LogSeverity']) && $_SESSION['LogSeverity'] > 2) {
                        fwrite($LogFile, date('Y-m-d h-m-s') . ',' . $Type . ',' . $_SESSION['UserID'] . ',' . trim($Msg, ',') . "\n");
                    }
            }
    ?>
            <!-- Display the message with the appropriate class and close button -->
            <div class="Message <?= $Class ?> noPrint">
                <span class="MessageCloseButton">&times;</span>
                <b><?= $Message[2] ?></b> : <?= $Message[0] ?>
            </div>
    <?php
        }
    }
    ?>
</div>

<script>
    // Move the message container to the app messages dropzone
    document.getElementById('app_messages_dropzone').append(document.getElementById('MessageContainerFoot'))
</script>
</div>
</div>
</div>
<!-- <div class="centre noprint">
                        <form action="/index.php" method="post">
                            <input name="FormID"
                                                                                                          type="hidden"
                                                                                                          value="dcf42489338d804eb326c21b115e1a0bf2abb8c7"><input
                                name="ScriptName" type="hidden" value=""><input name="Title" type="hidden"
                                                                                value="Main Menu"></form>
                    </div>
                </div>
            </div>
        </div> -->

<div class="sidebar sidebar-right sidebar-animate">
    <div class="sidebar-icon">
        <a class="text-end float-end text-dark fs-20"
            data-bs-target=".sidebar-right" data-bs-toggle="sidebar-right"
            href="index.php?Application=Sales#"><i class="fa fa-x"></i></a>
    </div>
    <div class="sidebar-body">
        <h5>Security &amp; Fraud Prevention Tips</h5>
        <div class="d-flex p-3">
            <label><span>Already posted transaction should be reversed not editted </span></label>
        </div>
        <div class="d-flex p-3 border-top">
            <label><span>Journal Should have at least one approval line</span></label>
        </div>
        <div class="d-flex p-3 border-top">
            <label><span>Credit Note Should have at leat one approval line</span></label>
        </div>
        <div class="d-flex p-3 border-top">
            <label><span>Auditor should have Read-Only Access</span></label>
        </div>
        <div class="d-flex p-3 border-top">
            <label><span>Always logout your account all devices</span></label>
        </div>
        <div class="d-flex p-3 border-top">
            <label><span>Only System Adimistrator shoul be able to change employee password</span></label>
        </div>
        <div class="d-flex p-3 border-top">
            <label><span>All Payements (GL Payment, Supplier Payments) should at least have some level of approval </span></label>
        </div>
        <div class="d-flex p-3 border-top mb-0">
            <label><span>As an employee do not allow your account to be used by another person in any circumstances</span></label>
        </div>
    </div>
</div>
</div>
<!-- END RIGHT-SIDEBAR -->

<div class="main-footer text-center">
    <div class="container">
        <div class="row row-sm">
            <div class="col-md-12">
                <div>© salvage ERP <i class="fa fa-heart text-danger"></i>. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END FOOTER -->

<!-- END PAGE -->

<!-- BACK TO TOP -->
<a href="index.php?Application=Sales#top" id="back-to-top"><i
        class="fa fa-arrow-up"></i></a>

<!-- Include necessary JS files -->
<script src="./Main Menu_files/jquery.min.js.download"></script>
<!-- BOOTSTRAP JS jquery-3.1.1.min.js -->
<script src="./Main Menu_files/popper.min.js.download"></script>
<script src="./Main Menu_files/bootstrap.min.js.download"></script>

<!-- PERFECT SCROLLBAR JS -->
<script src="./Main Menu_files/perfect-scrollbar.min.js.download"></script>

<!-- SIDEMENU JS -->
<script id="leftmenu" src="./Main Menu_files/sidemenu.js.download"></script>

<!-- SIDEBAR JS -->
<script src="./Main Menu_files/sidebar.js.download"></script>

<!-- INTERNAL DATA TABLE JS -->
<script src="./Main Menu_files/jquery.dataTables.min.js.download"></script>
<script src="./Main Menu_files/dataTables.bootstrap5.js.download"></script>
<script src="./Main Menu_files/dataTables.buttons.min.js.download"></script>
<script src="./Main Menu_files/buttons.bootstrap5.min.js.download"></script>
<script src="./Main Menu_files/jszip.min.js.download"></script>
<script src="./Main Menu_files/pdfmake.min.js.download"></script>
<script src="./Main Menu_files/vfs_fonts.js.download"></script>
<script src="./Main Menu_files/buttons.html5.min.js.download"></script>
<script src="./Main Menu_files/buttons.print.min.js.download"></script>
<script src="./Main Menu_files/buttons.colVis.min.js.download"></script>
<script src="./Main Menu_files/dataTables.responsive.min.js.download"></script>
<script src="./Main Menu_files/responsive.bootstrap5.min.js.download"></script>
<script src="./Main Menu_files/table-data.js.download"></script>

<!-- SELECT2 JS -->
<script src="./Main Menu_files/select2.min.js.download"></script>
<script src="./Main Menu_files/select2.js.download"></script>

<!-- INTERNAL JQUERY-UI JS -->
<script src="./Main Menu_files/datepicker.js.download"></script>

<!-- BOOTSTRAP-DATEPICKER JS -->
<script src="./Main Menu_files/bootstrap-datepicker.js.download"></script>

<!-- INTERNAL FORM-ELEMENTS JS -->
<script src="./Main Menu_files/form-elements.js.download"></script>

<!-- STICKY JS -->
<script src="./Main Menu_files/sticky.js.download"></script>

<!-- COLOR THEME JS -->
<script src="./Main Menu_files/themeColors.js.download"></script>

<!-- CUSTOM JS -->
<script src="./Main Menu_files/custom.js.download"></script>
<script src="assets/js/js/custom.js" type="text/javascript"></script>
<!-- SWITCHER JS -->
<script src="./Main Menu_files/switcher.js.download"></script>

<script>
    var year = (new Date).getFullYear();
    // Initialize datepicker for the first set of date fields
    $('.date').datepicker({
        maxDate: 0,
        showOtherMonths: false,
        selectOtherMonths: false,
        maxDate: new Date(year, 11, 31),
        dateFormat: 'yy-mm-dd'
    });
    // Initialize datepicker for the second set of date fields
    $('.dateHat').datepicker({
        maxDate: 0,
        showOtherMonths: true,
        selectOtherMonths: true,
        minDate: new Date(year, 0, 1),
        dateFormat: 'yy-mm-dd'
    });
    // var pather = "https://hybrid.leadingedgecloud.com?hybrid";
    var numx = "2";
</script>
<script src="./Main Menu_files/ajaxprocessing.js.download" type="text/javascript"></script>
<script src="assets/js/js/custom.js" type="text/javascript"></script>

                  		<!-- INTERNAL MORRIS CHART JS -->
        <script src="./assets/Dashboard_files/raphael.min.js.download"></script>
		<script src="./assets/Dashboard_files/morris.min.js.download"></script>
		<script src="./assets/Dashboard_files/chart.morris.js.download"></script>
        <script>
        $(function() {
	'use strict';
	/* Morris Chart1*/
    <?php
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
    ?>

    var morrisData = <?= json_encode($data, JSON_NUMERIC_CHECK); ?>;
	/* Morris Chart1*/
	new Morris.Bar({
		element: 'morrisBar1',
		data: morrisData,
		xkey: 'y',
		ykeys: ['a', 'b'],
		labels: ['Sales', 'Purchase'],
		barColors: ['#6259ca', '#53caed'],
		gridTextSize: 11,
		hideHover: 'auto',
		resize: true
	});
 });
 </script>
</div>
<div class="main-navbar-backdrop"></div>
</body>

</html>
