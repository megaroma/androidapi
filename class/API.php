<?php
class API {
	public static function start($conf) {
		$api_key = Input::get('api_key','');
		$action = Input::get('api_action','');
		$actions = explode(",", $conf['actions']);
		if(($api_key != '') && ($api_key == $conf['api_key']) && ($action != '') && (in_array($action, $actions))) {
			$method = 'apicall'.ucfirst($action);
			$res = (array) APICalls::$method();
			$res = self::utf8ize($res);
			echo  json_encode ($res);
			echo "<br><br>". json_last_error() ;
			exit;
		}
	}


public static function utf8ize($mixed) {
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = self::utf8ize($value);
        }
    } else if (is_string ($mixed)) {
        return iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($mixed));
    }
    return $mixed;
}

}