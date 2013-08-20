<?php

	/* ------------------------------------------------------------------
	 * Function to return the value of the given fieldname from post_meta
	 *
	 * @Param 	field name (string), post ID (int)
	 * @Return 	field value (string)
	 * ------------------------------------------------------------------ */

	function get_field( $field_name, $post_id ) {
		$field_value = '';
		$field_value = get_post_meta( $post_id, $field_name, true );

		return $field_value;
	};
