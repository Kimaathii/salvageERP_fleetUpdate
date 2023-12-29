<div class="app-main">
    <div class="app-sidebar sidebar-shadow bg-night-sky sidebar-text-light">
        <div class="app-header__logo">
            <div class="logo-src"></div>
            <div class="header__pane ml-auto">
                <div>
                    <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
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
                <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                    <span class="btn-icon-wrapper">
                        <i class="fa fa-ellipsis-v fa-w-6"></i>
                    </span>
                </button>
            </span>
        </div>
        <div class="scrollbar-sidebar">
            <div class="scrollbar-container ps--active-y ps">
                <div class="app-sidebar__inner">
                    <?php
                        include ('includes/MainMenuLinksArray.php');
                        echo '<ul class="vertical-nav-menu">';
                        $groupHeading = "";
                        $i = 0; // Initialize $i
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
                                    echo '<li class="app-sidebar__heading">' . $moduleGroup . '</li>';
                                    echo '<li class="mm-active"><a href="', 'index.php', '?Application=', urlencode($ModuleLink[$i]), '">', $moduleIcon, ' ', $moduleName, '</a></li>';
                                    $groupHeading = $moduleGroup;
                                } else {
                                    // Output the module within the same group
                                    echo '<li class="main_menu_unselected"><a href="', 'index.php', '?Application=', urlencode($ModuleLink[$i]), '">', $moduleIcon, ' ', $moduleName, '</a></li>';
                                }
                            }
                            ++$i;
                        }

                        echo '</li>';
                        echo '</ul>';

                        ?>
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


