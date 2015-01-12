<?php
class APICalls {

	public static function apicallTest() {
		return array(
			'status' => 1,
			'message' => 'ok' 
			);
	}

	public static function apicallLogin() {
		$username = Input::get('username','');
		$password = Input::get('password','');
		$device_id = Input::get('device_id','');
		$status = 3;
		$message = 'Error';
		$sess_id = '';

		if(($username != '') && ($password != '')) {
			$session_id = Auth::login($username,$password,$device_id);
			if($session_id) {
				$status = 1;
				$message = "ok";
				$sess_id = $session_id;
			} else {
				$status = 2;
				$message = "Login or Password is incorrect";
			}
		}

		return array(
			'status' => $status,
			'message' => $message ,
			'session_id' => $sess_id
			);
	}


	public static function apicallStatus() {
		$username = Input::get('username','');
		$sess_id = Input::get('session_id','');
		$status = 3;
		$message = 'Error';
		if(($username != '') && ($sess_id != '')) {
			$status = Auth::status($username,$sess_id);
			if($status == 1) $message = 'Ok';
			if($status == 2) $message = 'Your session has expired. Please log in again';			
		}		
		return array(
			'status' => $status,
			'message' => $message 
			);
	}
}