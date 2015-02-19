<?php

class Site {

	public static function find($id) {
		$sql = "SELECT
					*
				FROM 
					site s
				WHERE
					`s`.`id` = ?
				ORDER BY site_number ASC";
		$res = DB::select($sql,array($id));
		if(count($res) > 0) {
			return $res[0];
		} else {
			return false;
		}
	}

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

	public static function vendors($site_id) {
		$site = self::find($site_id);
		if($site) {
			$vendors = array();
			$vendors['site'] = 'true';//--
			$names = array(
				'concrete_vendor_id' => 'Concrete Recycler',
				'scrap_vendor_id' => 'Scrap Recycler',
				'contract_trucking_vendor_id' => 'Contract Trucking',
				'fill_dirt_vendor_id' => 'Fill Dirt',
				'land_fill_vendor_id' => 'Land Fill',
				'quarry_vendor_id' => 'Quarry',
				'contract_hauling_vendor_id' => 'Contract Hualing',
				'dumpster_vendor_id' => 'Dumpster',
				'radio_recycle_vendor_id' => 'Radio Recycle'								
				);
			foreach ($names as $name => $type) {
				$vendors[$name]['interate'] = 'false';//-- 
				$vendors[$name]['data'] = $site->$name;//---
				if(($site->$name != '') && (ctype_digit(trim( $site->{$name} )))) {
					$vendors[$name]['interate'] = 'true';//-- 
					$vendor = Vendor::find($site->$name);
					$vendor->type = $type;
					$vendors[] = $vendor;
				}
			}
			return $vendors;
		} else {
			return false;
		}
	}
}