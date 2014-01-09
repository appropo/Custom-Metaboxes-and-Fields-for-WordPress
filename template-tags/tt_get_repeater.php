<?php

	/* ----------------------------------------------------------
	 * Function to return the repeater fields in grouped segments
	 *
	 * @Param 	box name (string), post ID (int)
	 * @Return 	repeater segments (array)
	 * ---------------------------------------------------------- */

	function recursive_array_search($needle,$haystack) {
	    foreach($haystack as $key=>$value) {
	        $current_key=$key;
	        if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
	            return $current_key;
	        };
	    };
	    return false;
	};

	function get_repeater( $box_name, $post_id ) {
		global $wpdb;

		$query 	 = "SELECT meta_key,meta_value FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE '$box_name%'";
		$results = $wpdb->get_results($query, ARRAY_A);

		$segments  = array();
		$result_ix = 0;

		// Write keys to keys and values to values
		foreach ($results as $item) {
			$id_field	= false;

			// Remove id token and add it after all cleaning
			if ( strrchr($item['meta_key'], '_') == "_id") {
				$id_field 		  = true;
				$id_token 		  = intval(strlen(strrchr($item['meta_key'], '_')));
				$stripped_key	  = substr($item['meta_key'], 0, strlen($item['meta_key']) - $id_token);

				/* ------------------------------------------------------------------
				 * Search for image value in results array!
				 * DON´T only validate by image_id, because image_id won't be removed
				 * in DB by removing an image from the custom fields
				 * ------------------------------------------------------------------ */
				$found = recursive_array_search( trim($stripped_key), $results );
				if ( empty( $found ) ) {

					// There is no image URL for this ID in DB -> Image was removed in CF
					continue;

				};

				$item['meta_key'] = $stripped_key;
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

			$result_ix++;
		};

		ksort($segments);

		return $segments;
	};
