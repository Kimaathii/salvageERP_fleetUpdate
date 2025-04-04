<!-- Sidebar -->
<div class="dropdown header-settings">
    <a class="nav-link icon" data-bs-target=".sidebar-right" data-bs-toggle="sidebar-right" href="javascript:void(0);">
        <i class="fa fa-align-right header-icons"></i>
    </a>
</div>
<!-- Sidebar -->
</div>
</div>
</div>
<div class="d-flex header-setting-icon demo-icon fa-spin hide">
    <a class="nav-link icon" href="javascript:void(0);">
        <i class="fa fa-settings settings-icon"></i>
    </a>
</div>
</div>
</div>
</div>
<!-- remove padding to prevent extra body space -->
<div class="jumps-prevent" ></div>
<!-- END HEADER -->

<!-- SIDEBAR -->

<div class="sticky" style="">
    <div class="main-menu main-sidebar main-sidebar-sticky side-menu ps ps--active-y">
        <div class="main-sidebar-header main-container-1 active">
            <div class="sidemenu-logo">
                <a class="main-logo" href="#">
                    <!-- Display company logo -->
                    <img alt="<?php echo stripslashes($_SESSION['CompanyRecord']['coyname']); ?>" src="<?php echo "$RootPath/{$_SESSION['LogoFile']}" ?>"
                        title="<?php echo stripslashes($_SESSION['CompanyRecord']['coyname']); ?>" style="width:50px; border-radius:50%; float:left;" />
                </a>
            </div>
            <div class="main-sidebar-body main-body-1">
                <div class="slide-left disabled d-none" id="slide-left"><i class="fe fe-chevron-left"></i></div>
                <?php
                include('includes/MainMenuLinksArray.php');
                // Check if the current URL is /Dashboard.php and add "active" class to the Dashboard menu item
                $dashboardActiveClass = ($_SERVER['REQUEST_URI'] === '/Dashboard.php') ? 'active ' : '';
                echo '<ul class="menu-nav nav">
                <li class="nav-item '. $dashboardActiveClass. '"><a class="nav-link" href="/Dashboard.php"><span class="shape1"></span>
                <span class="shape2"></span><i class="metismenu-icon pe-7s-display1 sidemenu-icon menu-icon"></i><span class="sidemenu-label"> ' . _('Dashboard') . '</span></a></li>';
                $groupHeading = "";
                $i = 0; // Initialize $i

                // Get the currently active module from the URL
                $currentApplication = isset($_GET['Application']) ? $_GET['Application'] : "";

                foreach ($ModuleList as $module) {
                    $moduleName = $module['name'];
                    $moduleGroup = $module['group'];
                    $moduleIcon = $module['icon'];

                    if ($_SESSION['ModulesEnabled'][$i] == 1) {
                        if ($moduleGroup != $groupHeading) {
                            // Output the group heading when it changes
                            if ($groupHeading != "") {
                                echo '</li>';
                            }
                            echo '<li class="nav-header mb-2"><span class="nav-label">' . $moduleGroup . '</span></li>';
                        }

                        // Determine if the current module is active
                        $isActive = ($currentApplication === $ModuleLink[$i]) ? ' active' : '';

                        echo ' <li class="nav-item' . $isActive . '"><a class="nav-link" href="', 'index.php', '?Application=', urlencode($ModuleLink[$i]), '">', '  <span class="shape1"></span>
                            <span class="shape2"></span>', $moduleIcon, '<span class="sidemenu-label"> ', $moduleName, '</span></a></li>';

                        $groupHeading = $moduleGroup;
                        } else {
                            // Output the module within the same group
                            echo ' <li class="nav-item' . $isActive . '"><a class="nav-link" href="', 'index.php', '?Application=', urlencode($ModuleLink[$i]), '">', '  <span class="shape1"></span>
                                <span class="shape2"></span>', $moduleIcon, '<span class="sidemenu-label"> ', $moduleName, '</span></a></li>';
                        }
                    
                    ++$i;
                }

                echo '</li>';
                echo '</ul>';
                ?>

                <div class="slide-right" id="slide-right"><i class="fe fe-chevron-right"></i></div>
            </div>
        </div>
        <div class="ps__rail-x" style="left: 0px; top: 0px;">
            <div class="ps__thumb-x" style="left: 0px; width: 0px;" tabindex="0"></div>
        </div>
        <div class="ps__rail-y" style="top: 0px; height: 633px; right: 0px;">
            <div class="ps__thumb-y" style="top: 0px; height: 433px;" tabindex="0"></div>
        </div>
    </div>
</div>
<!-- removed padding from this attribute to prevent top spacing -->
<div class="jumps-prevent" ></div>
<!-- END SIDEBAR -->

<!-- 
    </div>
</div>
</div>
</div>
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title app-page-title-simple">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <div class="page-title-head center-elem">
                            <span class="d-inline-block pr-2">
                                <i class="lnr-apartment opacity-6"></i>
                            </span>
                            <span class="d-inline-block"><?php echo stripslashes($_SESSION['CompanyRecord']['coyname']); ?></span>
                        </div>
                        <div class="page-title-subheading opacity-10">
                            <nav class="" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a>
                                            <i aria-hidden="true" class="fa fa-home"></i>
                                        </a>
                                    </li>
                                    <li class="active breadcrumb-item" aria-current="page">
                                        <?php echo $Title ?>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <div class="d-inline-block pr-3">
                        <select id="custom-inp-top" type="select" class="custom-select">
                            <option>Select action...</option>
                            <?php
                            if (!isset($_SESSION['Favourites'][$ScriptName]) || $_SESSION['Favourites'][$ScriptName] == '') {
                                echo '<option>Add to commonly used</option>';
                            } else {
                                echo '<option>Remove from commonly used</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <button type="button" data-toggle="tooltip" data-placement="left" class="btn btn-dark" title="" data-original-title="Perform Action">
                        <i class="fa fa-battery-three-quarters"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="app_messages_dropzone"></div>

        <div class="main-card mb-3 card"><div class="card-body">
-->

<?php
if ($should_wrap) {
    echo '
 <div class="main-content side-content pt-0">
    
            <div class="main-container container-fluid">
                <div class="inner-body">
                <div class="card mb-3 custom-card">
                                <div class="card-header custom-card-header border-bottom bg-primary" style="padding-top:2px">
                                    <h5 class="main-content-label tx-white my-auto tx-medium mb-0">' . $Title . '</h5>
                                    <div class="card-options">
                                        <a href="javascript:void(0);" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fa fa-chevron-up tx-white"></i></a>
                                <a href="javascript:void(0);" class="card-options-fullscreen" data-bs-toggle="card-fullscreen"><i class="fa fa-maximize tx-white"></i></a>
                                    </div>
                                </div> 
                                  <div class="card-body">
                                    
                                    
     <div>
                                  <div id="app_messages_dropzone"></div>
     ';
}
?>
