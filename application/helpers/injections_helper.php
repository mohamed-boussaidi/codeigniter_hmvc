<?php defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * provide injection methods
 *
 * @author taghoutitarek@gmail.com
 * @company http://mind.engineering
 */


if ( ! function_exists( "readDirectory" ) ) {
	/**
	 * return all the classes and all
	 * their methods located under each
	 * controllers folder located
	 * under modules
	 *
	 * @param string $Directory
	 * @param bool $Recursive
	 * @param int $level
	 *
	 * @return array|bool
	 */
	function readDirectory( $Directory, $Recursive = true, $level = 0 ) {
		if ( is_dir( $Directory ) === false ) {
			return false;
		}
		$level ++;
		try {
			$Resource = opendir( $Directory );
			$Found    = array();

			while ( false !== ( $Item = readdir( $Resource ) ) ) {
				if ( $Item == "." || $Item == ".." || $Item == ".DS_Store" ) {
					continue;
				}
				if ( $Recursive === true && is_dir( $Directory . $Item ) ) {
					$Directoryname = $Directory . $Item . '/';
					if ( $level == 2 && $Item != "controllers" ) {
						continue;
					}
					if ( $Item == 'controllers' ) {
						$Found = readDirectory( $Directoryname, true, $level );
					} else {
						$Found[ $Item ] = readDirectory( $Directoryname, true, $level );
					}
				} else {
					$filename = $Directory . $Item;
					$ext      = pathinfo( $filename, PATHINFO_EXTENSION );
					if ( $ext == "php" ) {
						$classname = ucfirst( str_replace( '.php', '', $Item ) );
						require_once( $filename );
						$reflector      = new ReflectionClass( $classname );
						$methodNames    = array();
						$lowerClassName = strtolower( $classname );
						foreach ( $reflector->getMethods( ReflectionMethod::IS_PUBLIC ) as $method ) {
							if ( strtolower( $method->class ) == $lowerClassName ) {
								if ( $method->name != "__construct" ) {
									$methodNames[] = $method->name;
								}
							}
						}
						$Found[ $classname ] = $methodNames;

					}
				}
			}
		} catch ( Exception $e ) {
			return false;
		}

		//pre($Found,true);
		return $Found;
	}
}

if ( ! function_exists( "build" ) ) {
	/**
	 * make a mark which will be
	 * used by the modules
	 * to replace it by their own
	 * codes using building_name_of_mark
	 * methods, this method
	 * should be located under a
	 * controller of a module
	 * $args is an array that contain
	 * the args of the building method
	 *
	 * @param string $name
	 * @param array $arrays
	 * @param array $args
	 *
	 * @return array
	 * @internal param array $array
	 */
	function build( $name = "", $arrays = array(), $args = array() ) {

		$priority         = false;
		$key              = false;
		$args['priority'] = &$priority;
		if ( function_exists( "building_" . $name ) ) {
			$run = call_user_func_array( "building_" . $name, $args );
			$key = $priority !== false ? $priority : $key;
			if ( $key !== false ) {
				$arrays[ $key ] = $run;
			} else {
				$arrays[] = $run;
			}
		}
		foreach ( get_modules() as $module ) {
			$priority         = false;
			$key              = false;
			$args['priority'] = &$priority;
			if ( function_exists( $module . "_building_" . $name ) ) {
				$run = call_user_func_array( $module . "_building_" . $name, $args );
				$key = $priority !== false ? $priority : $key;
				if ( $key !== false ) {
					$arrays[ $key ] = $run;
				} else {
					$arrays[] = $run;
				}
			}
		}
		asort( $arrays );
		$result = array();
		foreach ( $arrays as $array ) {
			$array = is_array( $array ) ? $array : array();
			foreach ( $array as $key => $element ) {
				$result[ $key ] = $element;
			}
		}
		return $result;
	}
}

if ( ! function_exists( 'building_view_members_left_menu' ) ) {
	/**
	 * only who can view members who have access to this section
	 * @return array
	 */
	function building_view_members_left_menu() {
		return array(
			array( 'name' => 'members', 'url' => 'members', 'class' => 'fa-users' )
		);
	}
}

if ( ! function_exists( 'building_admin_left_menu' ) ) {
	/**
	 * left menu admin section
	 * @return array
	 */
	function building_admin_left_menu() {
		$settings_contain = array_merge(
			build( "settings_tabs_list_contain" ),
			array(
				"roles",
				"email_templates",
				"email_settings",
				"roles",
				"notification_settings",
				"rsa_keys",
				"rest_keys",
				"websocket"
			) );
		return array(
			array(
				'name'    => 'settings',
				'url'     => 'settings',
				'class'   => 'fa-wrench',
				'contain' => $settings_contain
			)
		);
	}
}

if ( ! function_exists( "building_notification_new_member_content" ) ) {
	/**
	 * new member notification body
	 *
	 * @param $event
	 *
	 * @return array
	 */
	function building_notification_new_member_content( $event ) {
		$ci     = get_instance( true );
		$member = $ci->Members_model->get_one( get_property( $event, "related" ), true );

		return array(
			"<br>" . plang( "notification_" . get_property( $event, "event" ) . "_title" ) . " " .
			get_property( $member, "first_name" ) . " " .
			get_property( $member, "last_name" )
		);
	}
}

if ( ! function_exists( "building_notification_new_member_email_content" ) ) {
	/**
	 * new member email notification body
	 *
	 * @param $event
	 *
	 * @return array
	 */
	function building_notification_new_member_email_content( $event ) {
		$ci     = get_instance( true );
		$member = $ci->Members_model->get_one( get_array_value( $event, "related" ), true );

		return array(
			"<br>" . plang( "notification_" . get_property( $event, "event" ) . "_title" ) . " " .
			get_property( $member, "first_name" ) . " " .
			get_property( $member, "last_name" )
		);
	}
}

if ( ! function_exists( "building_notification_new_member_url" ) ) {
	/**
	 * new member notification url
	 *
	 * @param $event
	 *
	 * @return array
	 */
	function building_notification_new_member_url( $event ) {
		return array( base_url( "members/view/" . get_property( $event, "related" ) ) );
	}
}

if (!function_exists('building_top_bar_profile_menu')) {
    function building_top_bar_profile_menu() {
        return profile_menu();
    }
}

if ( ! function_exists( "building_permissions_row" ) ) {
	/**
	 * permissions rows
	 *
	 * @param string $role_id
	 *
	 * @return array
	 */
	function building_permissions_row( $role_id = "" ) {
		return array(
			"members" => array(
				"view_members",
				"view_member",
				"edit_member",
				"add_member"
			)
		);
	}
}

if ( ! function_exists( "building_linked_checkbox_list" ) ) {
	/**
	 * permission activation related row affected
	 * @return array
	 */
	function building_linked_checkbox_list() {
		return array(
			"members" => array(
				"main" => array(
					"name"       => "view_members",
					"related"    => array(
						"view_member",
						"edit_member",
						"add_member"
					),
					"to_execute" => array(
						"view_member"
					)
				),
				"1"    => array(
					"name"       => "view_member",
					"related"    => array(
						"edit_member"
					),
					"to_execute" => array()
				)
			)
		);
	}
}

if ( ! function_exists( "building_notification_rows" ) ) {
	/**
	 * add notification to the list
	 * @return array
	 */
	function building_notification_rows() {
		return array( "notification" => create_notification_row() );
	}
}

if ( ! function_exists( "building_websocket_onEvent" ) ) {
	/**
	 * web socket triggered notification action
	 * @return array
	 */
	function building_websocket_onEvent() {
		return array( "notification" => create_websocket_onEvent_notification() );
	}
}

if ( ! function_exists( "building_websocket_onClose" ) ) {
	/**
	 * web socket close notification action
	 * @return array
	 */
	function building_websocket_onClose() {
		return array( "notification" => create_websocket_onClose() );
	}
}

if (!function_exists('building_top_settings_tabs_list')) {
    function building_top_settings_tabs_list() {
        return array(
            array( "url" => "settings", "name" => "general" ),
            array( "url" => "email_settings", "name" => "email_settings" ),
            array( "url" => "email_templates", "name" => "email_templates" ),
            array( "url" => "roles", "name" => "roles" ),
            array( "url" => "websocket", "name" => "websocket" ),
            array( "url" => "notification_settings", "name" => "notification_settings" ),
            array( "url" => "rsa_keys", "name" => "rsa_keys" ),
            array( "url" => "rest_keys", "name" => "rest_keys" )
        );
    }
}

if ( ! function_exists( "is_valid_module_archive" ) ) {
	/**
	 * check if all required files exists in the archive
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	function is_valid_module_archive( $path = "" ) {
		$files          = read_zip_files( $path );
		$required_files = array(
			"helpers/building_helper.php",
		);
		foreach ( $required_files as $file ) {
			if ( ! in_array( $file, $files ) ) {
				return false;
			}
		}

		return true;
	}
}

if ( ! function_exists( "get_modules" ) ) {
	/**
	 * get an array contain all modules names or paths
	 *
	 * @param bool $path
	 *
	 * @return array
	 */
	function get_modules( $path = false ) {
		$modules     = array();
		$dir         = APPPATH . "modules";
		$directories = glob( $dir . '/*', GLOB_ONLYDIR );
		foreach ( $directories as $dir ) {
			$modules[] = $path ? $dir : basename( $dir );
		}

		return $modules;
	}
}

if ( ! function_exists( "get_migration_versions" ) ) {
	/**
	 * get all the migration files numbers
	 * @return array
	 */
	function get_migration_versions() {
		$dir      = APPPATH . "migrations";
		$files    = glob( $dir . '/*_*.php' );
		$versions = array();
		foreach ( $files as $file ) {
			$file = basename( $file );
			if ( contains( $file, "_" ) ) {
				$versions[] = explode( "_", $file )[0];
			}
		}
		sort( $versions );

		return $versions;
	}
}