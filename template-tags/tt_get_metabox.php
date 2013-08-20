<?php

	/* ------------------------------------------------------------------
	 * Function to check if the given metabox exists
	 *
	 * @Param 	box name (string), post ID (int)
	 * @Return 	(bool)
	 * ------------------------------------------------------------------ */

	function get_metabox( $box_name, $post_id ) {
		global $wpdb;

		$return = false;

		$query 	= "SELECT meta_key,meta_value FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE '$box_name%'";
		$result = $wpdb->get_results($query);

		if ( !empty( $result ) ) {
			$return = true;
		}

		return $return;
	};
