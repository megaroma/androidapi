<?php
class GoogleAPI {

public static function get_key() {
	return API::conf['google_api_keys'][0];
}

public static function send_file($tmp_name, $name) {
	$url = API::conf['google_api_url']."?output=json&lang=en-us&key=".self::get_key();
	$cfile = self::getCurlValue($tmp_name,'audio/wav',$name);
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
	$result = str_replace('{"result":[]}','', $result);
	return json_decode($result);
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


?>