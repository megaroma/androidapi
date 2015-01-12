<?php
class API {
	public static function start($conf) {
		$api_key = Input::get('api_key','');
		$action = Input::get('api_action','');
		$actions = explode(",", $conf['actions']);
		if(($api_key != '') && ($api_key == $conf['api_key']) && ($action != '') && (in_array($action, $actions))) {
			$method = 'apicall'.ucfirst($action);
			$res = APICalls::$method();
			echo  json_encode ($res);
			exit;
		}
	}
}