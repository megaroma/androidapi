<?php

class Site {
	public static function all() {

		$sql = "SELECT
					`s`.`id` as `id`,
					`s`.`site_number` as `site_number`,
					`s`.`lat` AS `site_lat`,
					`s`.`long` AS `site_long`
				FROM 
					site s
				ORDER BY site_number ASC";

		$res = DB::select($sql);

		return $res;
	}

	public static function near($lat, $long) {
		$sql = "SELECT
					`s`.`id` as `id`,
					`s`.`site_number` as `site_number`,
					`s`.`lat` AS `site_lat`,
					`s`.`long` AS `site_long`,
					((ACOS(SIN( CAST( ? AS DECIMAL(10,6)) * PI() / 180) * 
					SIN(s.lat * PI() / 180) + 
					COS( CAST( ? AS DECIMAL(10,6)) * PI() / 180) *
					COS(s.lat * PI() / 180) *
					COS((  CAST( ? AS DECIMAL(10,6)) - s.long) * PI() / 180))
					* 180 / PI()) * 60 * 1.1515) AS distance
				FROM site s
				HAVING distance<= 50
				ORDER BY distance ASC";
		$data = array(trim($lat),trim($lat),trim($long));
		$res = DB::select($sql, $data);
		return $res;		
	}
}