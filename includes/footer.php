<div id="mask">
	<div id="dialog"></div>
</div>
</div>
</div>
</div>

<?php

if (isset($Messages) && count($Messages) > 0) {
	foreach ($Messages as $Message) {
		switch ($Message[1]) {
			case 'error':
				$Class = 'error';
				$Message[2] = $Message[2] ? $Message[2] : _('ERROR') . ' ' . _('Report');
				if (isset($_SESSION['LogSeverity']) && $_SESSION['LogSeverity'] > 3) {
					fwrite($LogFile, date('Y-m-d h-m-s') . ',' . $Type . ',' . $_SESSION['UserID'] . ',' . trim($Msg, ',') . "\n");
				}
				break;
			case 'warn':
			case 'warning':
				$Class = 'warn';
				$Message[2] = $Message[2] ? $Message[2] : _('WARNING') . ' ' . _('Report');
				if (isset($_SESSION['LogSeverity']) && $_SESSION['LogSeverity'] > 3) {
					fwrite($LogFile, date('Y-m-d h-m-s') . ',' . $Type . ',' . $_SESSION['UserID'] . ',' . trim($Msg, ',') . "\n");
				}
				break;
			case 'success':
				$Class = 'success';
				$Message[2] = $Message[2] ? $Message[2] : _('SUCCESS') . ' ' . _('Report');
				if (isset($_SESSION['LogSeverity']) && $_SESSION['LogSeverity'] > 3) {
					fwrite($LogFile, date('Y-m-d h-m-s') . ',' . $Type . ',' . $_SESSION['UserID'] . ',' . trim($Msg, ',') . "\n");
				}
				break;
			case 'info':
			default:
				$Message[2] = $Message[2] ? $Message[2] : _('INFORMATION') . ' ' . _('Message');
				$Class = 'info';
				if (isset($_SESSION['LogSeverity']) && $_SESSION['LogSeverity'] > 2) {
					fwrite($LogFile, date('Y-m-d h-m-s') . ',' . $Type . ',' . $_SESSION['UserID'] . ',' . trim($Msg, ',') . "\n");
				}
		}
        ?>
		<div id="MessageContainerFoot">
				<div class="Message ', $Class, ' noPrint">
					<span class="MessageCloseButton">&times;</span>
					<b><?= $Message[2] ?></b> : <?= $Message[0] ?>
				</div>
			</div><?php
	}
}
?>
</section>
</div>
</div>
	</div>

<div class="app-wrapper-footer">
	<div class="app-footer">
		<div class="app-footer__inner">
			<div class="app-footer-left">
				<div class="footer-dots">

					<div class="dropdown">
						<a aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dot-btn-wrapper">
							<i class="dot-btn-icon lnr-bullhorn icon-gradient bg-mean-fruit"></i>
						</a>

					</div>

					<div class="dots-separator"></div>

					<div class="dropdown">
						<a class="dot-btn-wrapper" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
							<i class="dot-btn-icon lnr-earth icon-gradient bg-happy-itmeo"></i>
						</a>
					</div>

					<div class="dots-separator"></div>

					<div class="dropdown">
						<a class="dot-btn-wrapper dd-chart-btn-2" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
							<i class="dot-btn-icon lnr-pie-chart icon-gradient bg-love-kiss"></i>
							<div class="badge badge-dot badge-abs badge-dot-sm badge-warning">
								Notifications</div>
						</a>
						<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu" style="">
							<div class="dropdown-menu-header">
								<div class="dropdown-menu-header-inner bg-premium-dark">
									<div class="menu-header-image" style="background-image: url('assets/images/dropdown-header/abstract4.jpg');">
									</div>
									<div class="menu-header-content text-white">
										<h5 class="menu-header-title">Users Online</h5>
										<h6 class="menu-header-subtitle">Recent Account Activity
											Overview</h6>
									</div>
								</div>
							</div>
							<div class="widget-chart">
								<div class="widget-chart-content">
									<div class="icon-wrapper rounded-circle">
										<div class="icon-wrapper-bg opacity-9 bg-focus"></div>
										<i class="lnr-users text-white"></i>
									</div>
									<div class="widget-numbers">
										<span>
											4</span>
									</div>
									<div class="widget-subheading pt-2"> Profile views since last login
									</div>
									<div class="widget-description text-danger">
										<span class="pr-1"> <span>80 %</span></span>
										<i class="fa fa-arrow-left"></i>
									</div>
								</div>
								<div class="widget-chart-wrapper">
									<div id="dashboard-sparkline-carousel-4-pop" style="min-height: 120px;">
										<div id="apexcharts9gah5qwo" class="apexcharts-canvas apexcharts9gah5qwo" style="width: 0px; height: 120px;"><svg id="SvgjsSvg1558" width="0" height="120" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;">
												<g id="SvgjsG1560" class="apexcharts-inner apexcharts-graphical">
													<defs id="SvgjsDefs1559"></defs>
												</g>
											</svg>
											<div class="apexcharts-legend"></div>
										</div>
									</div>
									<div class="resize-triggers">
										<div class="expand-trigger">
											<div style="width: 399px; height: 121px;"></div>
										</div>
										<div class="contract-trigger"></div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>

			</div>
			<div class="app-footer-right">
				<ul class="header-megamenu nav">
					<li class="nav-item">
						<a data-placement="top" rel="popover-focus" data-offset="300" data-toggle="popover-custom" class="nav-link" data-original-title="" title="">
							About
							<i class="fa fa-angle-up ml-2 opacity-8"></i>
						</a>
						<div class="rm-max-width rm-pointers">
							<div class="d-none popover-custom-content">
								<div class="dropdown-mega-menu dropdown-mega-menu-sm">
									<div class="grid-menu grid-menu-2col">
										<div class="no-gutters row">
											<div class="col-sm-6 col-xl-6">
												<ul class="nav flex-column">
													<li class="nav-item-header nav-item">Contacts</li>
													<!-- <li class="nav-item">
													<a class="nav-link">
														<i class="nav-link-icon lnr-inbox"></i>
														<span>Afolabi David +23470 3808 4494</span>
													</a>
												</li> -->
													<li class="nav-item">
														<a class="nav-link">
															<i class="nav-link-icon lnr-book"></i>
															<span>Akinbami Olawale +23480 3367
																7498</span>
														</a>
													</li>
													<li class="nav-item">
														<a class="nav-link">
															<i class="nav-link-icon lnr-picture"></i>
															<span>Software: Savage ERP</span>
														</a>
													</li>
												</ul>
											</div>
											<div class="col-sm-6 col-xl-6">
												<ul class="nav flex-column">
													<li class="nav-item-header nav-item">About
														Savage ERP</li>
													<li class="nav-item"><a class="nav-link"><?php echo _('version'); ?> <?php echo $_SESSION['VersionNumber']; ?></a></li>
													<li class="nav-item"><a class="nav-link">Cross
															Platform Technologies</a></li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</li>
					<li class="nav-item">
						<a data-placement="top" rel="popover-focus" data-offset="300" data-toggle="popover-custom" class="nav-link" data-original-title="" title="">
							<script>
								document.write(new Date().getFullYear())
							</script> © SavageERP. <div class="badge badge-alternate ml-0 ml-1">
								<small><?php echo _('version'); ?> <?php echo $_SESSION['VersionNumber']; ?></small>
							</div>
						</a>

					</li>
				</ul>
			</div>
		</div>
	</div>
</div>


<?php echo implode("\n", @$STACKS['modals']); ?>


<script src="<?php echo $PathPrefix . $RootPath; ?>/css/<?php echo 'putup'; ?>/jquery-3.1.1.min.js"></script>
<!--<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

<!--<script type="text/javascript" src="ajaxprocessing.js"></script>-->
<script type="text/javascript" src="<?php echo $PathPrefix . $RootPath; ?>/css/<?php echo 'putup'; ?>/assets/scripts/main.d810cf0ae7f39f28f336.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="<?php echo $PathPrefix . $RootPath; ?>/css/<?php echo 'putup'; ?>/dataTables/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/metisMenu/1.1.3/metisMenu.min.js"></script>
<script src="javascripts/RecordDelete.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>