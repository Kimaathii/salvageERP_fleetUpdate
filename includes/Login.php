<?php
// Display demo user name and password within login form if $AllowDemoMode is true
if ((isset($AllowDemoMode)) and ($AllowDemoMode == True) and (!isset($demo_text))) {
    $demo_text = _('Login as user') . ': <i>' . _('admin') . '</i><br />' . _('with password') . ': <i>' . _('weberp') . '</i>' . '<br /><a href="../">' . _('Return') . '</a>'; // This line is to add a return link.
} elseif (!isset($demo_text)) {
    $demo_text = '';
}
?>
<!doctype html>
<html lang="en">
<meta content="text/html;charset=UTF-8" http-equiv="content-type" />
<meta content="text/html;charset=UTF-8" http-equiv="content-type" />

<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="en" http-equiv="Content-Language">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title><?php echo _('Salvage ERP') . ' - ' . $Title; ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" name="viewport" />
    <!-- FAVICON -->
    <link href="favicon.html" rel="icon">

    <!-- BOOTSTRAP CSS -->
    <link href="assets\plugins\bootstrap\css\bootstrap.min.css" id="style" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- ICONS CSS -->
    <link href="assets\plugins\web-fonts\icons.css" rel="stylesheet">
    <link href="assets\plugins\web-fonts\font-awesome\font-awesome.min.css" rel="stylesheet">
    <link href="assets\plugins\web-fonts\plugin.css" rel="stylesheet">

    <!-- STYLE CSS -->
    <link href="\assets\js\css\style.css" rel="stylesheet">
    <link href="\assets\css\plugins.css" rel="stylesheet">
</head>

<body class="ltr main-body leftmenu error-1">

    <div class="page main-signin-wrapper">

        <!-- Row -->
        <div class="row signpages text-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="row row-sm">
                        <div class="col-lg-6 col-xl-5 d-none d-lg-block text-center bg-primary details">
                            <div class="mt-5 pt-4 p-2 pos-absolute">
                                <img alt="logo" class="header-brand-img mb-4" src="companies\salvage\logo.jpg">
                                <div class="clearfix"></div>
                                <img alt="user" class="ht-100 mb-2 mt-2" src="assets\img\brand\logo-login.png" style="border-radius:50%; border:10px solid #8883ca; box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                                <h5 class="mt-4 text-white">Create Your Account</h5>
                                <span class="tx-white-6 tx-13 mb-5 mt-xl-0">Take business productivity to a whole new level!</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-7 col-xs-12 col-sm-12 login_form">
                            <div class="main-container container-fluid">
                                <div class="row row-sm">
                                    <div class="card-body mt-2 mb-2">
                                        <img alt="logo" class="d-lg-none header-brand-img text-start float-start mb-4 error-logo-light" src="companies\salvage\logo.jpg">
                                        <img alt="logo" class="d-lg-none header-brand-img text-start float-start mb-4 error-logo" src="assets\img\brand\logo-login.png" style="max-width:25%">
                                        <div class="clearfix"></div>
                                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="post">
                                            <input name="FormID" type="hidden" value="<?php echo $_SESSION['FormID']; ?>" />

                                            <h5 class="text-start mb-2">Please sign in to your account</h5>
                                            <p class="mb-2 text-muted tx-13 ms-0 text-start">Sign in to create, analyse, and export your business data</p>

                                            <div class="form-group text-start">
                                                <label>Company</label>
                                                <select class="custom-select" id="exampleCustomSelect" name="CompanyNameField">
                                                    <?php
                                                    // Populate the company list dropdown
                                                    if (isset($CompanyList) && is_array($CompanyList)) {
                                                        foreach ($CompanyList as $key => $CompanyEntry) {
                                                            if (is_dir('companies/' . $CompanyEntry['database'])) {
                                                                $selected = ($CompanyEntry['database'] == $DefaultDatabase) ? 'selected' : '';
                                                                echo '<option value="' . $key . '" ' . $selected . '>' . htmlspecialchars($CompanyEntry['company'], ENT_QUOTES, 'UTF-8') . '</option>';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group text-start">
                                                <label>User name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text border-end-0"><i class="fa fa-user-plus"></i></span>
                                                    <input autofocus class="form-control" maxlength="20" name="UserNameEntryField" placeholder="User name" required="required" type="text" />
                                                </div>
                                            </div>

                                            <div class="form-group text-start">
                                                <label>Password</label>
                                                <div class="input-group">
                                                    <span class="fa fa-fw fa-eye field-icon toggle-password input-group-text border-end-0" style="padding-right:25px;" id="togglePassword"></span>
                                                    <input class="form-control" id="Password" name="Password" placeholder="Password" required="required" type="password">
                                                </div>
                                            </div>

                                            <input class="btn btn-main-primary btn-block text-white" name="SubmitUser" type="submit" value="Sign In" />
                                            <p id="demo_text"></p>
                                        </form>
                                        <div class="text-start mt-3 ms-0 text-muted tx-13">
                                            <div>&copy; salvage ERP <i class="fa fa-heart text-danger"></i>. All rights reserved.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->

    </div>
    <!-- END PAGE -->

    <!-- JQUERY JS -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- PERFECT SCROLLBAR JS -->
    <script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <!-- SELECT2 JS -->
    <script src="assets/plugins/select2/js/select2.min.js"></script>
    <script src="assets/js/select2.js"></script>
    <script src="assets/js/js/custom.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Toggle password visibility
            $(".toggle-password").click(function() {
                $(this).toggleClass("fa-eye fa-eye-slash");
                var input = $($(this).attr("toggle"));
                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        });
    </script>
</body>

</html>
