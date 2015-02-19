<?php

class Vendor {

	public static function find($id) {
		$sql = "SELECT
					*
				FROM 
					vendor s
				WHERE
					`s`.`id` = ?
				";
		$res = DB::select($sql,array($id));
		if(count($res) > 0) {
			return $res[0];
		} else {
			return false;
		}
	}