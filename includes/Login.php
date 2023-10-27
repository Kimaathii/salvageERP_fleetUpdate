<?php
// Display demo user name and password within login form if $AllowDemoMode is true
if ((isset($AllowDemoMode)) and ($AllowDemoMode == True) and (!isset($demo_text))) {
	$demo_text = _('Login as user') . ': <i>' . _('admin') . '</i><br />' . _('with password') . ': <i>' . _('weberp') . '</i>' . '<br /><a href="../">' . _('Return') . '</a>'; // This line is to add a return link.

} elseif (!isset($demo_text)) {
	$demo_text = '';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Language" content="en">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>SAVAGE ERP Login screen</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
	<!-- Disable tap highlight on IE -->
	<meta name="msapplication-tap-highlight" content="no">
	<link rel="icon" href="<?php echo $PathPrefix . $RootPath; ?>/icon.png" />
	<link href="/css/putup/main.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
</head>
<style type="text/css">
	@media screen and (min-width: 990px) {
		.applogo {
			display: none !important;
		}
	}

	@media screen and (max-width 1000px) {
		.applogo {
			margin-top: 20% !important;
		}
	}
</style>

<body>
	<div class="app-container app-theme-white body-tabs-shadow">
		<div class="app-container">
			<div class="h-100" style="background-image: url('assets/background.jpg')">
				<div class="d-flex h-100 justify-content-center align-items-center">
					<div class="mx-auto app-login-box col-md-8">


						<div class="row">

							<div class="col-md-4 bg-night-sky pb-0 card-shadow-danger" style="border-radius: 0.25rem;">
								<div style="text-align:center;">
									<img src="./css/putup/assets/logo.png" style="max-width:100%; margin-top:10%; margin-bottom:1%;" />
								</div>

								<div class="mb-3 card text-white bg-night-sky">
									<div class="card-header"> <i class="header-icon lnr-bicycle icon-gradient bg-love-kiss"> </i> SAVAGE ERP</div>
									<div class="card-body">...a complete business solution at your finger snap.</div>
								</div>

							</div>
							<div class="col-md-8 card card-shadow-danger card-btm-border">
								<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="post">
								<div class="card-header">
										<i class="header-icon lnr-gift icon-gradient bg-mixed-hopes"> </i>
										<span>Please sign in to your account.
											<div class="btn-actions-pane-right actions-icon-btn">
												<div role="group" class="btn-group-sm nav btn-group">

												</div>
											</div>
									</div>
									<div class="card-body">
									<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID']; ?>" />

									<?php
									if (isset($CompanyList) and is_array($CompanyList)) {
										foreach ($CompanyList as $key => $CompanyEntry) {
											if ($DefaultDatabase == $CompanyEntry['database']) {
												$CompanyNameField = "$key";
												$DefaultCompany = $CompanyEntry['company'];
											}
										}
										if ($AllowCompanySelectionBox === 'Hide') {
											// do not show input or selection box
											echo '<input type="hidden" name="CompanyNameField"  value="' . $CompanyNameField . '" />';
										} elseif ($AllowCompanySelectionBox === 'ShowInputBox') {
											// show input box
											echo _('Company') . ': <br />' . '<input type="text" class="form-control" name="DefaultCompany"  autofocus="autofocus" required="required" value="' . htmlspecialchars($DefaultCompany, ENT_QUOTES, 'UTF-8') . '" disabled="disabled"/>'; //use disabled input for display consistency
											echo '<input type="hidden" name="CompanyNameField"  value="' . $CompanyNameField . '" />';
										} else {
											// Show selection box ($AllowCompanySelectionBox == 'ShowSelectionBox')


											echo '<div class="form-group">';
											echo '<label for="company" class="form-label">Company</label>';
											echo '<select class="custom-select" name="CompanyNameField" aria-label="Company">';
											foreach ($CompanyList as $key => $CompanyEntry) {
												if (is_dir('companies/' . $CompanyEntry['database'])) {
													if ($CompanyEntry['database'] == $DefaultDatabase) {
														echo '<option selected="selected" label="' . htmlspecialchars($CompanyEntry['company'], ENT_QUOTES, 'UTF-8') . '" value="' . $key . '">' . htmlspecialchars($CompanyEntry['company'], ENT_QUOTES, 'UTF-8') . '</option>';
													} else {
														echo '<option label="' . htmlspecialchars($CompanyEntry['company'], ENT_QUOTES, 'UTF-8') . '" value="' . $key . '">' . htmlspecialchars($CompanyEntry['company'], ENT_QUOTES, 'UTF-8') . '</option>';
													}
												}
											}
											echo '</select> </div>';
										}
									} else { //provision for backward compat - remove when we have a reliable upgrade for config.php
										if ($AllowCompanySelectionBox === 'Hide') {
											// do not show input or selection box
											echo '<input type="hidden" name="CompanyNameField"  value="' . $DefaultCompany . '" />';
										} else if ($AllowCompanySelectionBox === 'ShowInputBox') {
											// show input box
											echo _('Company') . '<input type="text" name="CompanyNameField" class="form-control"  autofocus="autofocus" required="required" value="' . $DefaultCompany . '" />';
										} else {
											// Show selection box ($AllowCompanySelectionBox == 'ShowSelectionBox')
											echo '<div class="form-group">';
											echo '<label for="company" class="form-label">Company</label>';
											echo '<select class="custom-select" name="CompanyNameField" aria-label="Company">';
											$Companies = scandir('companies/', 0);
											foreach ($Companies as $CompanyEntry) {
												if (is_dir('companies/' . $CompanyEntry) and $CompanyEntry != '..' and $CompanyEntry != '' and $CompanyEntry != '.svn' and $CompanyEntry != '.') {
													if ($CompanyEntry == $DefaultDatabase) {
														echo '<option selected="selected" label="' . $CompanyEntry . '" value="' . $CompanyEntry . '">' . $CompanyEntry . '</option>';
													} else {
														echo '<option label="' . $CompanyEntry . '" value="' . $CompanyEntry . '">' . $CompanyEntry . '</option>';
													}
												}
											}
											echo '</select> </div>';
										}
									} //end provision for backward compat

									?>


<div class="form-group">
										<label for="username" class="form-label"><?php echo _('User name'); ?></label>
										<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="lnr-users"> </i></span>
										</div>
										<input type="text" class="form-control" name="UserNameEntryField" required="required" autofocus="autofocus" maxlength="20" placeholder="<?php echo _('User name'); ?>" />
										</div>
									</div>
									<div class="form-group">

										<label class="form-label" for="password-input">Password</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="lnr-lock"> </i></span>
											</div>
											<input type="password" class="form-control pe-5 password-input" required="required" name="Password" placeholder="<?php echo _('Enter Password'); ?>" id="password-input">

										</div>
									</div>
									<p id="demo_text">

									</p>
									<div class="d-block text-right card-footer">
										<input class="btn btn-danger btn-shadow btn-block" type="submit" value="<?php echo _('Login'); ?>"
											name="SubmitUser" />
									</div>
								</form>
							</div>


						</div> <!-- row -->


						<div class="text-center text-white opacity-8 mt-3">
							<p class="mb-0">&copy;
								<script>
									document.write(new Date().getFullYear())
								</script> Savage ERP.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="/css/putup/assets/scripts/main.d810cf0ae7f39f28f336.js"></script>
</body>

</html>