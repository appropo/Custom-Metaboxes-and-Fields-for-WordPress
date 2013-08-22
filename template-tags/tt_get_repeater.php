<?php

	/* ----------------------------------------------------------
	 * Function to return the repeater fields in grouped segments
	 *
	 * @Param 	box name (string), post ID (int)
	 * @Return 	repeater segments (array)
	 * ---------------------------------------------------------- */

	function get_repeater( $box_name, $post_id ) {
		global $wpdb;

		$query 	 = "SELECT meta_key,meta_value FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE '$box_name%'";
		$results = $wpdb->get_results($query, ARRAY_A);

		$segments = array();

		// Write keys to keys and values to values
		foreach ($results as $item) {
			$id_field	= false;

			// Remove id token and add it after all cleaning
			if ( strrchr($item['meta_key'], '_') == "_id") {
				$id_field 		  = true;
				$id_token 		  = intval(strlen(strrchr($item['meta_key'], '_')));
				$item['meta_key'] = substr($item['meta_key'], 0, strlen($item['meta_key']) - $id_token);
			};

			$segment_ix = substr(strrchr($item['meta_key'], '_'),1);

			$rep_token 		= intval(strlen(strrchr($item['meta_key'], '_')));

			// Strip repeater count at the end
			$formated_key 	= substr($item['meta_key'], 0, strlen($item['meta_key']) - $rep_token);

			// Strip box name
			$formated_key	= substr($formated_key, intval(strlen($box_name)) + 1 );

			if ( $id_field == true ) {
				$formated_key .= "_id";
				$id_field 	  = false;
			};

			$segments[$segment_ix][$formated_key] = $item['meta_value'];
		};

		ksort($segments);

		return $segments;
	};
