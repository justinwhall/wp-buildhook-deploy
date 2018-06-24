<?php
/**
 * LittleBot Netlifly
 *
 * A class for all plugin metaboxs.
 *
 * @version   0.9
 * @category  Class
 * @author    Justin W Hall
 */
class LBN_Call_Netlify {

	/**
	 * Kick it off.
	 */
	function __construct() {
		$this->hooks();
	}

	/**
	 * Attach hooks.
	 *
	 * @return void
	 */
	public function hooks() {
	}


	public function call_netlify() {

		// $response = Requests::post( 'https://api.netlify.com/build_hooks/5b27103fb13fb13aa20b3d5e' );


		// further processing ....
		// if ($server_output == "OK") { ... } else { ... }
	}
}
