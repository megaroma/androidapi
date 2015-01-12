<?php
class Access {
	public static function check($action , $user_id) {
		if($action == 'login') {
			return true;
		}

		return false;
	}
}