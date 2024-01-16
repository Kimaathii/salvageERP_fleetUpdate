<?php
$RootPath = '';
$ViewTopic = isset($ViewTopic) ? '?ViewTopic=' . $ViewTopic : '';
$BookMark = isset($BookMark) ? '#' . $BookMark : '';

if (isset($_GET['Theme'])) {
    $_SESSION['Theme'] = $_GET['Theme'];
    $SQL = "UPDATE www_users SET theme='" . $_GET['Theme'] . "' WHERE userid='" . $_SESSION['UserID'] . "'";
    $Result = DB_query($SQL);
}

if ($LanguagesArray[$_SESSION['Language']]['Direction'] == 'rtl' && mb_substr($_SESSION['Theme'], -4) != '-rtl') {
    $_SESSION['Theme'] = $_SESSION['Theme'] . '-rtl';
}

if (isset($Title) && $Title == _('Copy a BOM to New Item Code')) {
    ob_start();
}

?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="application/html; charset=utf-8; cache-control: no-cache, no-store, must-revalidate; Pragma: no-cache" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo _('Savage ERP') . ' - ' . $Title; ?></title>
        <link rel="icon" href="<?php echo $PathPrefix . $RootPath; ?>/icon.png" />
        <link href="<?php echo $PathPrefix . $RootPath; ?>/css/putup/main.css" rel="stylesheet">
        <link href="<?php echo $PathPrefix . $RootPath; ?>/css/putup/dataTables/datatables.min.css" rel="stylesheet">
        <link href="<?php echo $PathPrefix . $RootPath; ?>/css/print.css" rel="stylesheet" type="text/css" media="print" />
        <link href="css/formhandle/RecordDelete.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384H7x7lv6dJjfFDlJZy4cP6CJ9xL4hmGffZbAfx2Cgl4JwZPOXOmYfh0FJfRg" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo $PathPrefix . $RootPath; ?>/css/putup/assets/icon-fonts/pe-icon-7-stroke/css/helper.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script async type="text/javascript" src="<?php echo $PathPrefix . $RootPath; ?>/javascripts/MiscFunctions.js"></script>
        <script async src="<?= cache_bust('/assets/js/image-uploader.js') ?>"></script>
        <script>
            localStorage.setItem("DateFormat", "<?php echo $_SESSION['DefaultDateFormat']; ?>");
            localStorage.setItem("Theme", "<?php echo $_SESSION['Theme']; ?>");
            function removeTable(){
                document.getElementById('expandTable').style.display = 'none'
                document.getElementById('cancel').style.display = 'none'
                goBack();
            }
        </script>
        <style type="text/css">
            /* DATATABLES */
            .dataTables_length {
                display: none !important;
            }

            .dataTables_info {
                display: none !important;
            }

            .dataTables_filter {}

            .dataTables_wrapper {
                padding-bottom: 30px;
            }

            .dataTables_length {
                float: left;
            }

            .dataTables_filter label {
                margin-right: 5px;
            }


            .html5buttons {
                float: right !important;
            }

            .html5buttons a {
                border: 1px solid #e7eaec;
                background: #fff;
                color: #676a6c;
                box-shadow: none;
                padding: 6px 8px;
                font-size: 12px;
            }

            .html5buttons a:hover,
            .html5buttons a:focus:active {
                background-color: #eee;
                color: inherit;
                border-color: #d2d2d2;
            }

            div.dt-button-info {
                z-index: 100;
            }

            .hide {
                display: none !important;
            }

            .show {
                display: inline;
            }

            @media (max-width: 768px) {
                .html5buttons {
                    float: none;
                    margin-top: 10px;
                }
                .app-container {
                    background: #f1f4f6;
                    width: fit-content;
                }
            }

            input[type="text"]:focus {
                border: none !imortant;
            }

            .dpTbl {
                background: #ffffff !important;
            }

            table {
                margin-bottom: 1rem;
                background-color: rgba(0, 0, 0, 0);
            }

            table thead th {
                vertical-align: bottom;
                border-bottom: 2px solid #e9ecef;
            }

            table th,
            table td {
                vertical-align: middle;
                box-sizing: border-box;
            }

            table th,
            table td {
                padding: .55rem;
                vertical-align: top;
                border-top: 1px solid #e9ecef;
            }

            th {
                text-align: inherit;
            }

            table {
                border-collapse: collapse;
            }

            input[type="text"],
            input[type="email"],
            input[type="tel"],
            input[type="password"],
            input[type="number"],
            textarea {
                display: block;
                width: 100%;
                height: calc(1.5em + .75rem + 2px);
                padding: .375rem .75rem;
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #495057;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #ced4da;
                border-radius: .25rem;
                transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            }

            textarea {
                height: 100px;
            }

            select {
                display: block;
                width: 100%;
                height: calc(1.5em + .75rem + 2px);
                padding: .375rem .75rem;
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #495057;
                border: 1px solid #ced4da;
                border-radius: .25rem;
                background-color: #fff;
            }

            btn-primary:hover {
                color: #fff;
                background-color: #2955c8;
                border-color: #2651be;
            }

            .btn-primary:hover {
                color: #fff;
                background-color: #2955c8;
                border-color: #2651be;
            }

            input[type="submit"]:hover {
                color: #fff;
                background-color: #31a66a;
                border-color: #31a66a;
            }

            button:hover {
                color: #fff;
            }

            input[type="submit"],
            button {
                display: inline-block;
                font-weight: 400;
                color: #495057;
                text-align: center;
                vertical-align: middle;
                user-select: none;
                background-color: transparent;
                border: 1px solid transparent;
                border-top-color: transparent;
                border-right-color: transparent;
                border-bottom-color: transparent;
                border-left-color: transparent;
                padding: .375rem .75rem;
                font-size: 1rem;
                line-height: 1.5;
                border-radius: .25rem;
                position: relative;
                transition: color 0.15s, background-color 0.15s, border-color 0.15s, box-shadow 0.15s;
                color: #fff;
                background-color: #3f6ad8;
                border-color: #3f6ad8;
                font-size: 0.8rem;
                font-weight: 500;
                outline: none !important;
            }

            #MessageContainerFoot {
                padding: 10px;
            }

            .Message {
                border-radius: 10px;
                padding: 10px 10px 10px 36px;
                margin-top: 20px;
                /* width: 50%; */
                margin: 0 auto;
                opacity: 1;
                transition: opacity 0.6s;
                /* 600ms to fade out */
            }

            .MessageCloseButton {
                margin-left: 15px;
                color: #C8C8C8;
                font-weight: bold;
                float: right;
                font-size: 20px;
                line-height: 17px;
                cursor: pointer;
                transition: 0.3s;
            }

            .Message.error {
                background: #ffecec;
                border: 1px solid #f5aca6;
            }

            .Message.success {
                background: #e9ffd9;
                border: 1px solid #a6ca8a;
            }

            .Message.info {
                background: #e3f7fc;
                border: 1px solid #8ed9f6;
            }

            .Message.warn {
                background: #fff8c4;
                border: 1px solid #f2c779;
            }

            table{
                width: 100%
             }
            .modal-content{
                z-index: 1;
                margin-top: 70px;
            }
            .modal-backdrop {
                background-color: rgba(0, 0, 0, 0.5); /* This sets the color and opacity of the overlay */
            }
            .button-like-link {
                display: inline-block;
                padding: 6px 16px;
                font-size: 14px;
                font-weight: bold;
                text-align: center;
                text-decoration: none;
                cursor: pointer;
                border: 1px solid #ccc;
                background-color: #f9f9f9;
                color: #333;
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }

            .button-like-link:hover {
                background-color: #ddd;
            }
            .current-page {
                display: inline-block;
                padding: 8px 6px;
                background-color: #007BFF;
                color: #FFFFFF;
                border-radius: 5px;
                font-weight: bold;
                text-decoration: none;
                margin: 0 5px;
                font-size: 24px;
                font-weight: bold;
                color: black;
            }
            .search-container {
                 margin: 20px;
             }

            #searchInput {
                width: 200px;
                padding: 5px;
                font-size: 16px;
                margin-bottom: 10px;
            }
            #cancel{
                display: flex;

                justify-content: end;
            }
            #cancel >button{
                /*padding: 10px;*/
                /*background-color: #e74c3c; !* Red background color *!*/
                color: #fff; /* White text color */
                border: none;
                border-radius: 50%;
                cursor: pointer;
                font-size: 16px;
            }
        </style>
        <?php

        if ($_SESSION['ShowPageHelp'] == 0) {
            echo '<link href="' . $PathPrefix . $RootPath . '/css/' . 'putup' . '/page_help_off.css" rel="stylesheet" type="text/css" media="screen" />';
        } else {
            echo '<link href="' . $PathPrefix . $RootPath . '/css/' . 'putup' . '/page_help_on.css" rel="stylesheet" type="text/css" media="screen" />';
        }

        if ($_SESSION['ShowFieldHelp'] == 0) {
            echo '<link href="' . $PathPrefix . $RootPath . '/css/' . 'putup' . '/field_help_off.css" rel="stylesheet" type="text/css" media="screen" />';
        } else {
            echo '<link href="' . $PathPrefix . $RootPath . '/css/' . 'putup' . '/field_help_on.css" rel="stylesheet" type="text/css" media="screen" />';
        }
        ?>
        <script>
            addEventListener('load', () => {
                initial();
            })
        </script>
    </head>
<body onload="load()" onunload="GUnload()">

<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
    <div class="app-header header-shadow bg-night-sky header-text-light">
        <div class="app-header__logo">
            <div class=""><img alt="<?php echo stripslashes($_SESSION['CompanyRecord']['coyname']); ?>" src="<?php echo "$RootPath/{$_SESSION['LogoFile']}" ?>"
                               title="<?php echo stripslashes($_SESSION['CompanyRecord']['coyname']); ?>" style="width:50px; border-radius:50%; float:left;" /></div>
            <div class="header__pane ml-auto">
                <div>
                    <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                            data-class="closed-sidebar">
					<span class="hamburger-box">
						<span class="hamburger-inner"></span>
					</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="app-header__mobile-menu">
            <div>
                <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
				<span class="hamburger-box">
					<span class="hamburger-inner"></span>
				</span>
                </button>
            </div>
        </div>
        <div class="app-header__menu">
		<span>
			<button type="button"
                    class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
				<span class="btn-icon-wrapper">
					<i class="fa fa-ellipsis-v fa-w-6"></i>
				</span>
			</button>
		</span>
        </div>
        <div class="app-header__content">
            <div class="app-header-left">
                <div class="search-wrapper">
                    <div class="input-holder">
                        <input type="text" class="search-input" placeholder="Type to search">
                        <button class="search-icon"><span></span></button>
                    </div>
                    <button class="close"></button>
                </div>
                <ul class="header-megamenu nav">
                    <li class="nav-item"><a class="nav-link" href="/Dashboard.php"> <i
                                    class="nav-link-icon pe-7s-settings"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/SelectCustomer.php"> <i
                                    class="nav-link-icon pe-7s-users"></i> Customers</a></li>
                    <li class="nav-item"><a class="nav-link" href="/SelectProduct.php"> <span
                                    class="nav-link-icon pe-7s-box1"></span> Items</a></li>
                    <li class="nav-item"><a class="nav-link" href="/SelectSupplier.php"> <i
                                    class="nav-link-icon pe-7s-network"></i>Vendors</a></li>
                </ul>
            </div>
            <div class="app-header-right">
                <div class="header-dots">
                    <div class="dropdown">
                        <a class="p-0 mr-2 btn btn-link" href="/Logout.php"
                           onclick="return confirm('Are you sure you wish to logout?');">
						<span class="icon-wrapper icon-wrapper-alt rounded-circle">
							<span style="background:rgba(255, 0, 0, 0.5)!important"
                                  class="icon-wrapper-bg bg-danger"></span>
							<i class="icon text-primary ion-android-unlock"></i>
						</span>
                        </a>
                    </div>
                    <div class="dropdown">
                        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown"
                                class="p-0 mr-2 btn btn-link">
						<span class="icon-wrapper icon-wrapper-alt rounded-circle">
							<span class="icon-wrapper-bg bg-primary"></span>
							<i class="icon text-primary ion-android-apps"></i>
						</span>
                        </button>
                        <div tabindex="-1" role="menu" aria-hidden="true"
                             class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right" style="">
                            <div class="dropdown-menu-header">
                                <div class="dropdown-menu-header-inner bg-plum-plate">
                                    <div class="menu-header-image"
                                         style="background-image: url(assets/images/dropdown-header/abstract4.jpg);">
                                    </div>
                                    <div class="menu-header-content text-white">
                                        <h6 class="menu-header-subtitle">System users analytics</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="grid-menu grid-menu-xl grid-menu-3col">
                                <div class="no-gutters row">
                                    <div class="col-sm-6 col-xl-4">
                                        <button
                                                class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">5<i
                                                    class="pe-7s-world icon-gradient bg-night-fade btn-icon-wrapper btn-icon-lg mb-3"></i>
                                            System users
                                        </button>
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <button
                                                class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">36<i
                                                    class="pe-7s-piggy icon-gradient bg-night-fade btn-icon-wrapper btn-icon-lg mb-3">
                                            </i> Salesman
                                        </button>
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <button
                                                class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">947<i
                                                    class="pe-7s-paint-bucket icon-gradient bg-night-fade btn-icon-wrapper btn-icon-lg mb-3">
                                            </i> Suppliers
                                        </button>
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <button
                                                class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">227<i
                                                    class="pe-7s-menu icon-gradient bg-night-fade btn-icon-wrapper btn-icon-lg mb-3">
                                            </i> Stock Item
                                        </button>
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <button
                                                class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">19383<i
                                                    class="pe-7s-hourglass icon-gradient bg-night-fade btn-icon-wrapper btn-icon-lg mb-3"></i>
                                            Sales Orders
                                        </button>
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <button
                                                class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">12148<i
                                                    class="pe-7s-world icon-gradient bg-night-fade btn-icon-wrapper btn-icon-lg mb-3">
                                            </i> Purchase Orders
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <ul class="nav flex-column">
                                <li class="nav-item-divider nav-item"></li>
                                <li class="nav-item-btn text-center nav-item">
                                    <button class="btn-shadow btn btn-primary btn-sm">Follow-ups</button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button type="button" data-toggle="dropdown" class="p-0 mr-2 btn btn-link"
                                aria-expanded="false">
						<span class="icon-wrapper icon-wrapper-alt rounded-circle">
							<span class="icon-wrapper-bg bg-focus"></span>
							<span class="language-icon opacity-8 flag large NG"></span>
						</span>
                        </button>
                    </div>
                </div>
                <div class="header-btn-lg pr-0">
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="btn-group">
                                    <a href="<?php echo $PathPrefix . $RootPath; ?>/UserSettings.php" title="<?php echo _('Change the settings for') . ' ' . $_SESSION['UsersRealName']; ?>"
                                       class="p-0 btn">
                                           <img width="42" class="rounded-circle" src="<?php echo $_SESSION['UserImage'] ?? '/css/putup/assets/images/user.png' ?>"
                                             alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="widget-content-left  ml-3 header-user-info">
                                <div class="widget-heading"><?php echo stripslashes($_SESSION['UsersRealName']); ?> </div>
<!--                                <div class="widget-subheading">--><?php //echo stripslashes($_SESSION['UserID']); ?><!-- </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include('includes/Menu-bar.php'); ?>
