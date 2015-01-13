<?php
class DB {

	public static $mysqli;

	public static function init() {
		/*
		$host = "localhost";
		$user = "root";
		$pass = "";
		$db = "job_manager";
		*/
		$host = "f5vpn.impactfax.com";
		$user = "acctmgmt";
		$pass = "whatdoyouwant";
		$db = "job_manager";

		self::$mysqli = new mysqli($host,$user,$pass,$db);

	}

	public static function select($sql,$data = array()) {
		$res = array();
		if(count($data) > 0) {
			$stmt = self::$mysqli->prepare($sql);

			$types =  str_repeat ( 's' , count($data));
			array_unshift($data,$types);
            $refs = array();
            foreach($data as $key => $value)
                $refs[$key] = &$data[$key];			
			call_user_func_array(array($stmt,'bind_param'),$refs);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
		} else {
			$result = $mysqli->query($sql);
		}
			if($result) {
    			while ($obj = $result->fetch_object()) {
        			$res[] = $obj;
    			}		
    			$result->close();
    		}
    	
		return $res;
	}

	public static function statement($sql,$data = array()) {
		self::select($sql,$data);
	}

}


?>