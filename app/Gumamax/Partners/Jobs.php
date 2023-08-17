<?php namespace Gumamax\Partners;

use Illuminate\Support\Facades\DB;

class Jobs {


  public static function getJobsForPartnerId($id)
	{
        $sqlJobs = "SELECT
					pd_vehicle_category.value_text AS vehicle_category,
					p.product_id,
					d.description,
					p.additional_description,
					pd_diameter.value_text AS diameter,
					pd_vrsta_felne.value_text AS vrsta_felne,
					cpj.id,
					cpj.price_with_tax AS price
				FROM product p
				JOIN product_group pg ON pg.group_id=p.group_id
				JOIN description d ON d.description_id=p.description_id
				LEFT JOIN product_dimension pd_vehicle_category ON pd_vehicle_category.dimension_id=10 AND pd_vehicle_category.product_id=p.product_id
				LEFT JOIN product_dimension pd_diameter ON pd_diameter.dimension_id=13 AND pd_diameter.product_id=p.product_id
				LEFT JOIN product_dimension pd_vrsta_felne ON pd_vrsta_felne.dimension_id=24 AND pd_vrsta_felne.product_id=p.product_id
				LEFT JOIN partner_price_list cpj ON cpj.product_id=p.product_id AND cpj.partner_id=? AND cpj.deleted_at is NULL
				WHERE p.group_id=438
				ORDER BY 1 DESC, 3, 4, 5";

        return DB::connection('delmax_catalog')->select($sqlJobs, [$id]);
	}


	public static function getWheelAlignmentJobsForPartnerId($id)
	{
		$sql = "SELECT
					p.product_id,
					cpj.price_with_tax as job_price
				FROM partner_price_list cpj
				LEFT JOIN product p ON p.product_id=cpj.product_id
				WHERE
					cpj.deleted_at IS NULL AND
					cpj.partner_id = ? AND
					p.group_id=439";
		return DB::connection('delmax_catalog')->select($sql,[$id]);
	}
}