<?php
class Input {
	public static function get($param,$def = false) {
		if (isset($_POST[$param])) {
			return $_POST[$param];
		} elseif(isset($_GET[$param])) {
			return $_GET[$param];
		} else {
			return $def;
		}

	} 
}