<?php
$RootPath = '';
$ViewTopic = isset($ViewTopic) ? '?ViewTopic=' . $ViewTopic : '';
$BookMark = isset($BookMark) ? '#' . $BookMark : '';

// Check if a theme is set in the URL parameters
if (isset($_GET['Theme'])) {
    // Set the theme in the session
    $_SESSION['Theme'] = $_GET['Theme'];
    // Update the theme in the database for the current user
    $SQL = "UPDATE www_users SET theme='" . $_GET['Theme'] . "' WHERE userid='" . $_SESSION['UserID'] . "'";
    $Result = DB_query($SQL); // Execute the query
}

// Check if the language direction is right-to-left and the theme does not end with '-rtl'
if ($LanguagesArray[$_SESSION['Language']]['Direction'] == 'rtl' && mb_substr($_SESSION['Theme'], -4) != '-rtl') {
    // Append '-rtl' to the theme
    $_SESSION['Theme'] = $_SESSION['Theme'] . '-rtl';
}

// Check if the title is 'Copy a BOM to New Item Code'
if (isset($Title) && $Title == _('Copy a BOM to New Item Code')) {
    ob_start(); // Start output buffering
}

// Determine if the body should be wrapped in a div with class 'wrapper'
$exceptions = [
    '/Dashboard.php',
    'savageerp',
    // '/SelectCustomer.php',
    // '/SelectProduct.php',
    // '/SelectSupplier.php'
];
$current_url = $_SERVER['REQUEST_URI']; // Get the requested URL
$should_wrap = (strpos($current_url, '/index.php') === false) && !in_array($current_url, $exceptions);

if ($should_wrap) {
    echo '<div class="wrapper">';
}
?>
<!DOCTYPE html>
<html lang="en"
    style="--primary005: rgba(98, 89, 202, 0.05); --primary02: rgba(98, 89, 202, 0.2); --primary03: rgba(98, 89, 202, 0.3); --primary05: rgba(98, 89, 202, 0.5); --primary07: rgba(98, 89, 202, 0.7); --primary08: rgba(98, 89, 202, 0.8); --primary01: rgba(98, 89, 202, 0.1);">

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="en" http-equiv="Content-Language">
    <title><?php echo _('Salvage ERP') . ' - ' . $Title; ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"
        name="viewport">
    <meta content="no" name="msapplication-tap-highlight">

    <!-- FAVICON -->
    <link rel="icon" href="<?php echo $PathPrefix . $RootPath; ?>/icon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@icon/themify-icons@1.0.1-alpha.3/themify-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Pe-icon-7-stroke/1.2.0/css/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha384-Az6T4M9ZZyWVpFfGA0FWJYOSp0U9mSEtbc3T8ltVOVPz9cC9T/RPtacHHEbrz6U5" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css" rel="stylesheet">
    <link href="./Main Menu_files/bootstrap.min.css" id="style" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="./Main Menu_files/icons.css" rel="stylesheet">
    <link href="Main Menu_files\font-awesome.min.css" rel="stylesheet">
    <link href="./Main Menu_files/plugin.css" rel="stylesheet">
    <link href="./Main Menu_files/style.css" rel="stylesheet">
    <link href="./Main Menu_files/plugins.css" rel="stylesheet">
   <!-- bootstrap for dashboard -->
   <link id="style" href="./assets/Dashboard_files/bootstrap.min.css" rel="stylesheet">
		
		<!-- ICONS CSS -->
		<link href="./assets/Dashboard_files/icons.css" rel="stylesheet">
		<link href="./assets/Dashboard_files/font-awesome.min.css" rel="stylesheet">
		<link href="./assets/Dashboard_files/plugin.css" rel="stylesheet">

		<!-- STYLE CSS -->
		<link href="./assets/Dashboard_files/style.css" rel="stylesheet">
		<link href="./assets/Dashboard_files/plugins.css" rel="stylesheet">
	

	<script defer="defer" src="./assets/Dashboard_files/MiscFunctions.js.download"></script>

    <script defer="defer" src="./Main Menu_files/MiscFunctions.js.download"></script>

    <!-- Morris.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

    <!-- jQuery and Raphael -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>

    <!-- Morris.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script>
        localStorage.setItem("DateFormat", "Y-m-d");
        localStorage.setItem("Theme", "");
    </script>
    <script async type="text/javascript" src="<?php echo $PathPrefix . $RootPath; ?>/javascripts/MiscFunctions.js"></script>
    <script async src="<?= cache_bust('/assets/js/image-uploader.js') ?>"></script>
    <script>
        localStorage.setItem("DateFormat", "<?php echo $_SESSION['DefaultDateFormat']; ?>");
        localStorage.setItem("Theme", "<?php echo $_SESSION['Theme']; ?>");

        function removeTable() {
            document.getElementById('expandTable').style.display = 'none'
            document.getElementById('cancel').style.display = 'none'
            goBack();
        }
    </script>
   <script>
    $(document).ready(function() {
        // Initialize Select2
        if (!$('#SenderBankAccount').hasClass('select2-hidden-accessible')) {
            // $('#SenderBankAccount').select2();
    

        // Listen for changes on the Select2 dropdown
        $('#SenderBankAccount').on('select2:select', function(e) {
            const selectedOption = e.params.data.element; // Get the selected <option> element
            const balance = selectedOption.getAttribute('data-balance');
            const currency = selectedOption.getAttribute('data-currency');

            // Debugging
            console.log('Selected Option:', selectedOption);
            console.log('Balance:', balance);
            console.log('Currency:', currency);

            // Update the Current Balance and Currency fields
            document.getElementById('CurrentBalance').textContent = balance ? balance : '0.00';
            document.getElementById('Currency').value = currency ? currency : '';
        });
    }
  
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const senderBankAccount = document.getElementById('SenderBankAccount');
        const amountInput = document.getElementById('Amount');
        const transferButton = document.getElementById('TransferButton');
        const errorMessage = document.getElementById('ErrorMessage');
        const successMessage = document.getElementById('SuccessMessage');
        const currentBalanceSpan = document.getElementById('CurrentBalance');

        // Update balance when sender bank account changes
        senderBankAccount.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const balance = parseFloat(selectedOption.getAttribute('data-balance')) || 0;

            currentBalanceSpan.textContent = balance.toFixed(2);

            // Check if the balance is negative
            if (balance < 0) {
                errorMessage.textContent = 'The sender\'s account balance is negative. Transfer cannot proceed.';
                errorMessage.style.display = 'inline';
                successMessage.style.display = 'none';
                transferButton.disabled = true;
            } else {
                errorMessage.style.display = 'none';
                successMessage.style.display = 'none';
                transferButton.disabled = false;
            }
        });

        // Validate the transfer amount
        amountInput.addEventListener('input', function () {
            const selectedOption = senderBankAccount.options[senderBankAccount.selectedIndex];
            const balance = parseFloat(selectedOption.getAttribute('data-balance')) || 0;
            const amount = parseFloat(amountInput.value) || 0;

            if (amount > balance) {
                errorMessage.textContent = 'Insufficient funds for this transfer.';
                errorMessage.style.display = 'inline';
                successMessage.style.display = 'none';
                transferButton.disabled = true;
            } else if (balance < 0) {
                errorMessage.textContent = 'The sender\'s account balance is negative. Transfer cannot proceed.';
                errorMessage.style.display = 'inline';
                successMessage.style.display = 'none';
                transferButton.disabled = true;
            } else {
                errorMessage.style.display = 'none';
                successMessage.textContent = 'Transfer details look good!';
                successMessage.style.display = 'inline';
                transferButton.disabled = false;
            }
        });
    });
</script>
    <style type="text/css">
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
        }

        input[type="text"]:focus {
            border: none ! important;
        }

        table {
            margin-bottom: 1rem;
            background-color: rgba(0, 0, 0, 0);
            border-collapse: collapse;
        }

        .selection {
            border-collapse: collapse;
            width: 100% !important
        }

        .selection tr {
            border-top: 1px solid #e8e8f7;
        }

        .selection tr td:first-child {
            width: 200px !important;
            height: auto !important;
        }

        .page_help_text {
            line-height: 21px;
        }

        .page_title_text {
            font-weight: bold;
            font-size: 15px;
        }

        .field_help_text {
            display: block;
            font-size: 12px;
            margin-top: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"],
        input[type="number"],
        textarea {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #333333;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid #e8e8f7;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            height: 38px;
            border-radius: 5px;
            outline: none !important;
        }

        textarea {
            height: 100px;
        }

        input[type="checkbox"] {
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid #e8e8f7;
        }

        input[type="submit"],
        button {
            display: inline-block;
            font-weight: 400;
            color: #fff;
            line-height: 1.538;
            padding: 7px 20px;
            border-radius: 4px;
            transition: none;
            min-height: 38px;
            display: inline-block;
            font-weight: 400;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            border: 1px solid #6259ca;
            background: #6259ca;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 3px;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            margin-right: 10px !important;
        }

        #app_messages_dropzone {
            width: 100%;
        }

        #MessageContainerFoot {
            padding: 10px;
        }

        .Message {
            border-radius: 10px;
            padding: 10px 10px 10px 36px;
            margin-top: 20px;
            width: 50%;
            margin: 10px auto;
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
            width: fit-content;
        }

        .Message.warn {
            background: #fff8c4;
            /*border:1px solid #f2c779;*/
        }
    </style>
    <?php
    // Include CSS files based on session settings for page and field help
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

<body class="ltr main-body leftmenu" style="overflow-y:scroll !important">
    <div class="horizontalMenucontainer">

        <!-- PAGE -->

        <input id="Lang" name="Lang" type="hidden" value="US">

        <div class="main-header side-header sticky" style="margin-bottom: -64px;">
            <div class="main-container container-fluid">
                <div class="main-header-left">
                    <a class="main-header-menu-icon" href="javascript:void(0);" id="mainSidebarToggle"><span></span></a>
                    <div class="hor-logo">
                        <img alt="<?php echo stripslashes($_SESSION['CompanyRecord']['coyname']); ?>" src="<?php echo "$RootPath/{$_SESSION['LogoFile']}" ?>"
                            title="<?php echo stripslashes($_SESSION['CompanyRecord']['coyname']); ?>" style="width:50px; border-radius:50%; float:left;" />
                    </div>
                </div>

                <div class="main-header-center">
                    <div class="responsive-logo">
                        <a href="#">
                            <img alt="<?php echo stripslashes($_SESSION['CompanyRecord']['coyname']); ?>" src="<?php echo "$RootPath/{$_SESSION['LogoFile']}" ?>"
                                title="<?php echo stripslashes($_SESSION['CompanyRecord']['coyname']); ?>" style="width:50px; border-radius:50%; float:left;" />
                        </a>
                    </div>
                    <div class="input-group">
                        <input class="form-control rounded-0" placeholder="Search for anything..." type="search">
                        <button class="btn search-btn"><i class="fa-duotone fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>
                <div class="main-header-right">
                    <div class="navbar navbar-expand-lg  nav nav-item  navbar-nav-right responsive-navbar navbar-dark  ">
                        <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                            <div class="d-flex order-lg-2 ms-auto">
                                <!-- Menu Items Directly Displayed -->

                                <a class="nav-link" href="/Dashboard.php">
                                    <i class="nav-link-icon pe-7s-settings"></i> Dashboard
                                </a>
                                <a class="nav-link" href="/SelectCustomer.php">
                                    <i class="nav-link-icon pe-7s-users"></i> Customers
                                </a>
                                <a class="nav-link" href="/SelectProduct.php">
                                    <i class="nav-link-icon pe-7s-box1"></i> Items
                                </a>
                                <a class="nav-link" href="/SelectSupplier.php">
                                    <i class="nav-link-icon pe-7s-network"></i> Suppliers
                                </a>
                                <!-- Theme-Layout -->
                                <div class="dropdown d-flex main-header-theme">
                                    <a class="nav-link icon layout-setting">
                                        <span class="dark-layout">
                                            <i class="fa fa-sun header-icons"></i>
                                        </span>
                                        <span class="light-layout">
                                            <i class="fa-solid fa-moon"></i>
                                        </span>
                                    </a>
                                </div>
                                <!-- Full screen -->
                                <div class="dropdown ">
                                    <a class="nav-link icon full-screen-link">
                                        <i class="fa fa-maximize fullscreen-button fullscreen header-icons"></i>
                                        <i class="fa fa-minimize fullscreen-button exit-fullscreen header-icons"></i>
                                    </a>
                                </div>
                                <!-- Notification -->
                                <div class="dropdown main-header-notification">
                                    <a class="nav-link icon" href="javascript:void(0);">
                                        <i class="fa-regular fa-bell"></i>
                                        <span class="badge bg-danger nav-link-badge">208</span>
                                    </a>
                                </div>
                                <!-- Messages -->
                                <div class="main-header-notification hide">
                                    <a class="nav-link icon" href="https://hybrid.leadingedgecloud.com/chat.html">
                                        <i class="fa fa-message-square header-icons"></i>
                                        <span class="badge bg-success nav-link-badge">6</span>
                                    </a>
                                </div>
                                <!-- Profile -->
                                <div class="dropdown main-profile-menu">
                                    <a class="d-flex" href="javascript:void(0);">
                                        <span class="main-img-user">
                                            <img width="42" class="rounded-circle" src="<?php echo $_SESSION['UserImage'] ?? '/css/putup/assets/images/user.png' ?>"
                                                alt=""></span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="header-navheading">
                                            <h6 class="main-notification-title"><?php echo stripslashes($_SESSION['UsersRealName']); ?> </h6>
                                            <p class="main-notification-text"><?php echo stripslashes($_SESSION['UserID']); ?></p>
                                            <a href="<?php echo $PathPrefix . $RootPath; ?>/UserSettings.php" title="<?php echo _('Change the settings for') . ' ' . $_SESSION['UsersRealName']; ?>">User Settings</a>
                                        </div>
                                        <a class="dropdown-item" href="/Logout.php"
                                            onclick="return confirm('Are you sure you wish to logout?');">
                                            <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
                                        </a>
                                    </div>
                                </div>
                                <!-- Profile -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php include('includes/Menu-bar.php'); ?>
