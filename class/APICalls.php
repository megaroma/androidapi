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

	public static function apicallGet_all_sites() {
		$check = self::apicallStatus();
		if($check['status'] == 1) {
			$check['sites'] =  Site::all();
			$check['total'] = count($check['sites']);
			return $check;
		} else {
			return $check;
		}
		
	}

	public static function apicallGet_near_sites() {
		$lat = Input::get('lat','');
		$long = Input::get('long','');
		if((trim($lat) == '') || (trim($long) == '')) {
			return array(
				'status' => 3,
				'message' => "Error" 
			);	
		}

		$check = self::apicallStatus();
		if($check['status'] == 1) {
			$check['sites'] =  Site::near($lat, $long);
			$check['total'] = count($check['sites']);
			$check['debug'] = "Lat $lat, Long $long";
			return $check;
		} else {
			return $check;
		}
		
	}

	public static function apicallGet_site() {
		$id = Input::get('site_id','');
		if(trim($id) == '') {
			return array(
				'status' => 3,
				'message' => "Error" 
			);	
		}

		$check = self::apicallStatus();
		if($check['status'] == 1) {
			$check['site'] =  Site::find($id);
			//foreach($check['site'] as $k => $v) {
			//	$check['site'][$k] = str_replace(array("'", '"'), array("\'", '\"'), $v);
			//}
			API::utf8ize($check['site']);
			return $check;
		} else {
			return $check;
		}
		
	}

	public static function apicallGet_vendors() {
		$id = Input::get('site_id','');
		if(trim($id) == '') {
			return array(
				'status' => 3,
				'message' => "Error" 
			);	
		}

		$check = self::apicallStatus();
		if($check['status'] == 1) {
			$site = Site::find($id);
			$check['site'] = $site;
			API::utf8ize($check['site']);
			$check['vendors'] =  Site::vendors($site);
			$check['total'] = count($check['vendors']);
			return $check;
		} else {
			return $check;
		}
	}

	public static function apicallSave_note() {
		$id = Input::get('site_id','');
		$username = Input::get('username','');
		$note = Input::get('site_note','');

		if((trim($id) == '') || (trim($note) == '')) {
			return array(
				'status' => 3,
				'message' => "Error" 
			);	
		}

		$check = self::apicallStatus();
		if($check['status'] == 1) {
				Note::saveNote($id, $note, $username );
			return $check;
		} else {
			return $check;
		}
	}


	public static function apicallSend_message() {
		$username = Input::get('username','');
		$message = Input::get('message','');

		if((trim($username) == '') || (trim($message) == '')) {
			return array(
				'status' => 3,
				'message' => "Error" 
			);	
		}

		$check = self::apicallStatus();
		if($check['status'] == 1) {
				$to = "genmarieb@gmail.com";//"darkromanovich@gmail.com";
				$subject = "message from ".$username;
				Mail::send($to, $subject, $message);
			return $check;
		} else {
			return $check;
		}
	}


	public static function apicallSend_google_api() {
		
			ob_start();


$key = 'AIzaSyAqk7vE0vQDR3JItUPgFp6bcPqgJz8h8tI';
$url = 'https://www.google.com/speech-api/v2/recognize?output=json&lang=en-us&key='.$key;

$cfile = self::getCurlValue($_FILES['file']['tmp_name'],'audio/wav','hello.wav');

$data = array('file' => $cfile);
 
$ch = curl_init();
$options = array(CURLOPT_URL => $url,
             CURLOPT_RETURNTRANSFER => true,
             CURLINFO_HEADER_OUT => true, //Request header
             CURLOPT_HEADER => false, //Return header
             CURLOPT_SSL_VERIFYPEER => false, //Don't veryify server certificate
             CURLOPT_POST => true,
             CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/22.0.1216.0 Safari/537.2',
             CURLOPT_HTTPHEADER => array('Content-Type: audio/l16; rate=16000'),
             CURLOPT_POSTFIELDS => $data
            );
 
curl_setopt_array($ch, $options);
$result = curl_exec($ch);
echo $result;

if (file_exists($_FILES['file']['tmp_name'])) {
	echo "file exists \n";
	//echo file_get_contents($_FILES['file']['tmp_name']);

}


			$text = ob_get_contents();
			ob_end_clean();
			file_put_contents('test.txt', $text);
			move_uploaded_file($_FILES['file']['tmp_name'],"/var/www/html/beltonepublic/androidapi/".$_FILES['file']['name'] );
	


			return array(
				'status' => 1,
				'message' => "Ok" 
			);	
	}



public static function getCurlValue($filename, $contentType, $postname) {
    if (function_exists('curl_file_create')) {
        return curl_file_create($filename, $contentType, $postname);
    }
    $value = "@{$this->filename};filename=" . $postname;
    if ($contentType) {
        $value .= ';type=' . $contentType;
    }
    return $value;
}


}