<div id="sidebar" class="box-content ani-width">
    <div id="sidebar-scroll">
        <ul class="" id="sidebar-menu">
			<?php
			$sidebar_menu   = array();
			$sidebar_menu[] = array( 'name' => 'dashboard', 'url' => 'dashboard', 'class' => 'fa-home' );
			$injected_menus = build( "top_left_menu" );
			if ( count( $injected_menus ) > 0 ) {
				foreach ( $injected_menus as $injected_menu ) {
					if ( is_array( $injected_menu ) ) {
						$sidebar_menu[] = $injected_menu;
					}
				}
			}
			if ( logged_have_permission( "view_members" ) ) {
				$injected_menus = build( "view_members_left_menu" );
				if ( count( $injected_menus ) > 0 ) {
					foreach ( $injected_menus as $injected_menu ) {
						if ( is_array( $injected_menu ) ) {
							$sidebar_menu[] = $injected_menu;
						}
					}
				}
			}
			if ( logged_is_admin() ) {
				$injected_menus = build( "admin_left_menu" );
				if ( count( $injected_menus ) > 0 ) {
					foreach ( $injected_menus as $injected_menu ) {
						if ( is_array( $injected_menu ) ) {
							$sidebar_menu[] = $injected_menu;
						}
					}
				}
			}
			$injected_menus = build( "bottom_left_menu" );
			if ( count( $injected_menus ) > 0 ) {
				foreach ( $injected_menus as $injected_menu ) {
					if ( is_array( $injected_menu ) ) {
						$sidebar_menu[] = $injected_menu;
					}
				}
			}
			$removed_left_menu  = build( 'removed_left_menu' );
			$final_sidebar_menu = array();
			if ( is_array( $removed_left_menu ) ) {
				foreach ( $sidebar_menu as $sidebar_menu_element ) {
					if ( isset( $sidebar_menu_element['name'] ) ) {
						$exists = false;
						foreach ( $removed_left_menu as $removed_left_menu_element ) {
							if ( $removed_left_menu_element == $sidebar_menu_element['name'] ) {
								$exists = true;
								break;
							}
						}
						if ( ! $exists ) {
							$final_sidebar_menu[] = $sidebar_menu_element;
						}
					}
				}
			}
			echo sidebar_menu_items( $final_sidebar_menu );
			?>
        </ul>
    </div>
</div>

<script language="JavaScript">
    $(document).ready(function () {
        $(".submenu-parent").click(function () {
            $(this).toggleClass("open");
        });
    });
</script>