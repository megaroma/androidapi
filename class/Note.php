<?php

class Note {

public static function saveNote($site_id, $note, $username ) {
	if(($site_id != '') && ($note != '') && ($username != '' )) {
		$sql = "CALL insertSiteNote_api (?, ?, ?)";
		$data = array($site_id, $note, $username);
		DB::statement($sql,$data);
		return true;
	} else {
		return false;
	}
}



}