<?php

class Auth {

	public static function login($name,$pass,$device) {
		$res = DB::select("select * from users where `loginname` = ? ",array($name));
		$user = (count($res) > 0) ? $res[0] : false;
		if(($user) && ($user->loginpasswd == $pass) && (Access::check('login',$user->id))) {
			$res = DB::select("select * from `user_logins` where `user_id` = ? and `session_expire` > now()",array($user->id));
			if(count($res) > 0) {
				$sess = $res[0];
				DB::statement("update `user_logins` set `session_expire` = now() where `id` = ? ",array($sess->id));
			}
			$sess_key = self::get_session_id();
			DB::statement("insert into `user_logins` (`user_id`,`mobile_iemi`,`session_id`,`session_expire`,`created_at`) 
							values (? , ? , ? , now() + INTERVAL 20 MINUTE , now())",array($user->id,$device,$sess_key));
			return $sess_key;
		} else {
			return false;
		}
	}

	public static function status($name,$sess_id) {
		$res = DB::select("select * from users where `loginname` = ? ",array($name));
		$user = (count($res) > 0) ? $res[0] : false;
		if($user) {
			$res = DB::select("select * from `user_logins` where `user_id` = ? and `session_expire` > now()",array($user->id));
			$sess = (count($res) > 0) ? $res[0] : false;
			if($sess) {
				if($sess->session_id == $sess_id) {
					DB::statement("update `user_logins` set `session_expire` = now() + INTERVAL 20 MINUTE where `id` = ? ",array($sess->id));
					return 1;// ok 
				} else {
					return 3;//error token is not correct
				}
			} else {
				return 2; // session expired
			}

		}		
		return 3; //error
	}

	public static function get_session_id() {
		$rand = openssl_random_pseudo_bytes(32);
		return substr(bin2hex($rand ), 0, 32);
	}
}