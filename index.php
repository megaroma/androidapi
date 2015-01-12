<?php
function __autoload($class_name) {
	$class_name =  str_replace('\\', '/', $class_name);
    include 'class/'.$class_name.'.php';
}
$api_conf = include 'conf/AndroidAPI.php';
DB::init();

API::start($api_conf);

//$res = DB::select("select ? as `test` , ? as `test4` from dual",array("boo","boo2"));

//print_r($res);

//$rand = openssl_random_pseudo_bytes(32);
//$rand = substr(bin2hex($rand ), 0, 32);


echo "Android API V1.00";
//print_r($_REQUEST);
$data = file_get_contents('php://input');
print "DATA: <pre>";
var_dump($data);
var_dump($_POST);
print "</pre>";

?>