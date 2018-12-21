<?php 

namespace Inc\Base;



class Activate {

    public static function activate () {

        $roles = array ('tier-one'=>'Tier One', 'tier-two'=>'Tier Two', 'tier-three'=>'Tier Three');

        foreach($roles as $name=>$role) {
            add_role( $name, $role );

            $role = get_role( $name );
            $role->add_cap( 'read' );
        }

        global $wp_roles;

       /* $roles_to_remove = array('subscriber', 'contributor', 'author', 'editor','shop_manager');

        foreach ($roles_to_remove as $role) {
            if (isset($wp_roles->roles[$role])) {
                $wp_roles->remove_role($role);
            }
        }*/


        if ( get_option( 'hmu_api_plugin' ) ) {
			return;
		}

		$default = array();

		update_option( 'hmu_api_plugin', $default );

		global $wpdb;
	    $table = 'wp_posts';

	    $custom_id = $wpdb->get_row( sprintf("SELECT * FROM %s LIMIT 1", $table) );

	    if(!isset($custom_id->missing_field)) {
		    $wpdb->query( sprintf( "ALTER TABLE %s ADD custom_id INT(1) NOT NULL DEFAULT 1", $table) );


	    }


        flush_rewrite_rules();

    }

}
