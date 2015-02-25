<?php
class API {
	public static function start($conf) {
		$api_key = Input::get('api_key','');
		$action = Input::get('api_action','');
		$actions = explode(",", $conf['actions']);
		if(($api_key != '') && ($api_key == $conf['api_key']) && ($action != '') && (in_array($action, $actions))) {
			$method = 'apicall'.ucfirst($action);
			$res = APICalls::$method();
			self::utf8ize($res->site);
			echo  json_encode ($res);
			echo "<br><br>". json_last_error() ;
			exit;
		}
	}


public static function utf8ize($mixed) {

        foreach ($mixed as $key => $value) {
        	echo $key."<br>";
            $mixed->$key = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($value));
        }

}

}