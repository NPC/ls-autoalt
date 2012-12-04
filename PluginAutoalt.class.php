<?php
/*-------------------------------------------------------
*
*   AutoAlt
*   Copyright  2012 Anton Maslo (http://amaslo.com)
*
---------------------------------------------------------
*/

/**
 * Forbid direct access to the file
 */
if (!class_exists('Plugin')) {
	die('Hacking attempt!');
}

class PluginAutoalt extends Plugin {
	/**
	 * Plugin activation
	 */
	public function Activate() {
		return true;
	}	

	/**
	 * Plugin init
	 */
	public function Init() {
	}
}
?>