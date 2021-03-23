<?php $member = get_logged_member() ?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle"
                data-toggle="collapse"
                data-target="#navbar"
                aria-expanded="false"
                aria-controls="navbar">
            <span class="fa fa-cog"></span>
        </button>
        <button id="sidebar-toggle" type="button" class="navbar-toggle" data-target="#sidebar">
            <span class="sr-only">Toggle navigation</span>
            <span class="fa fa-bars"></span>
        </button>
        <a class="navbar-brand" href="<?php echo site_url() ?>">
            <img src="<?php echo files_url("system", get_setting("site_logo")) ?>">
        </a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-left">
            <li class="hidden-xs pl15 pr15  b-l">
                <button class="hidden-xs" id="sidebar-toggle-md">
                    <span class="fa fa-dedent"></span>
                </button>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <?php
            $notifications_rows = build("notification_rows");
            foreach ($notifications_rows as $notifications_row) {
                echo $notifications_row;
            }
            ?>
            <li class="dropdown pr15 dropdown-member">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    <span class="avatar-xs avatar pull-left mt-5 mr10">
                        <img alt="..." src="<?php echo get_avatar(get_property($member, "image")) ?>">
                    </span>
                    <?php echo get_property($member, "first_name") . " " . get_property($member, "last_name") ?>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu p0" role="menu">
                    <?php
                        $profile_menu_elements = build('top_bar_profile_menu');
                        if (count($profile_menu_elements)) {
                            foreach ($profile_menu_elements as $profile_menu_element) {
                                echo $profile_menu_element;
                            }
                        }
                    ?>
                    <li class="divider"></li>
                    <li>
                        <a href="<?php echo get_uri('signin/signout') ?>">
                            <i class="fa fa-power-off mr10"></i>
                            <?php echo plang("signout") ?>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
