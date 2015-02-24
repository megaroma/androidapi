<?php
class API {
	public static function start($conf) {
		$api_key = Input::get('api_key','');
		$action = Input::get('api_action','');
		$actions = explode(",", $conf['actions']);
		if(($api_key != '') && ($api_key == $conf['api_key']) && ($action != '') && (in_array($action, $actions))) {
			$method = 'apicall'.ucfirst($action);
			$res = APICalls::$method();
			//echo  json_encode ($res, JSON_HEX_QUOT);
			echo self::toJSON($res);
			exit;
		}
	}


	public static function toJSON($o) {
	switch (gettype($o)) {
		case 'NULL':
			return 'null';
		case 'integer':
		case 'double':
			return strval($o);
		case 'string':
			return '"' . addslashes($o) . '"';
		case 'boolean':
			return $o ? 'true' : 'false';
		case 'object':
			$o = (array) $o;
		case 'array':
			$foundKeys = false;

			foreach ($o as $k => $v) {
				if (!is_numeric($k)) {
					$foundKeys = true;
					break;
				}
			}

			$result = array();

			if ($foundKeys) {
				foreach ($o as $k => $v) {
					$result []= toJSON($k) . ':' . toJSON($v);
				}

				return '{' . implode(',', $result) . '}';
			} else {
				foreach ($o as $k => $v) {
					$result []= toJSON($v);
				}

				return '[' . implode(',', $result) . ']';
			}
	}
	}
}