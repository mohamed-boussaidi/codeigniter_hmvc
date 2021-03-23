<?php
$tabs                       = array();
$tabs                       = build( "top_settings_tabs_list" );
$tabs                       = array_merge( $tabs, build( "bottom_settings_tabs_list" ) );
if (!isset($_GET['show_all_settings_tabs'])) {
	$removed_settings_tabs_list = build( 'removed_settings_tabs_list' );
	$final_tabs                 = array();
	if ( is_array( $removed_settings_tabs_list ) ) {
		foreach ( $tabs as $tabs_element ) {
			if ( isset( $tabs_element['name'] ) ) {
				$exists = false;
				foreach ( $removed_settings_tabs_list as $removed_settings_tabs_list_element ) {
					if ( $removed_settings_tabs_list_element == $tabs_element['name'] ) {
						$exists = true;
						break;
					}
				}
				if ( ! $exists ) {
					$final_tabs[] = $tabs_element;
				}
			}
		}
	}
} else {
    $final_tabs = $tabs;
}
if ( is_array( $final_tabs ) && count( $final_tabs ) ) { ?>
    <h4><i class="fa fa-wrench"></i> <?php echo plang( "element_settings", array( "application" ) ) ?> </h4>
    <ul class="nav nav-tabs vertical" role="tablist">
		<?php echo tabs_list( $tab, $final_tabs ) ?>
    </ul>
<?php }

