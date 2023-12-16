<?php namespace App\Gumamax\BusinessRules;

use DB;
use Config;

class Order {
	private $sqlOrders = "SELECT
							c.cart_id,
							c.user_id,
							c.shipping_to_partner_id,
							c.shipping_to_address_id,
							c.payment_method_id,
							c.shipping_method_id,
							c.cart_status_id,
							c.order_status_id,
							c.order_id,
						    c.ordered_at,
						    DATE_FORMAT(c.ordered_at,'%d.%m.%Y') as ordered_date,
						    c.order_number,
							c.created_at,
							c.updated_at,
							c.deleted_at,
							c.from_ip,
							c.user_vehicle_id,
							c.billing_to_address_id,
						    c.amount_incl_tax,
						    c.amount_excl_tax,
						    c.tax_amount,
						    c.discount_amount,
							os.order_status_id,
							os.description AS order_status,
							u.firstname,
							u.lastname,
							sm.description as shipping_method,
							sc.free_shipping,
							sc.courier_shipping,
							pm.description as payment_method,
							ps.description as payment_status,
							cs.description as cart_status,
						    c.shipping_amount_incl_tax,
						    c.shipping_amount_excl_tax,
						    c.shipping_tax_amount,
						    c.total_amount_incl_tax,
						    c.total_amount_excl_tax,
						    c.total_tax_amount
						FROM cart c
						LEFT JOIN order_status os ON os.order_status_id=c.order_status_id
						LEFT JOIN cart_status cs ON cs.cart_status_id=c.cart_status_id
						LEFT JOIN payment_status ps ON ps.payment_status_id=c.payment_status_id
						LEFT JOIN users u ON u.user_id = c.user_id
						LEFT JOIN shipping_method sm on sm.shipping_method_id=c.shipping_method_id
						LEFT JOIN address sa on sa.address_id=c.shipping_to_address_id
						LEFT JOIN shipment_city sc on sc.city_id=sa.city_id
						LEFT JOIN payment_method pm on pm.payment_method_id=c.payment_method_id
						WHERE
							%%%where%%%
							AND c.deleted_at IS NULL
							AND c.order_id IS NOT NULL
						ORDER BY c.ordered_at DESC, c.order_number DESC";

	public function getOrdersForUser($id) {
		$sql = str_replace("#img_path#", Config::get('gumamax.productImagesUrl'), $this->sqlOrders);
		$sql = str_replace("%%%where%%%", " c.user_id = {$id} ", $sql);
    	$orders = DB::select($sql);

		foreach ($orders as $order) {
			$order->order_items = $this->getOrderedItems($order->order_id);
		}
    	return $orders;
	}

	public function getOrdersForPartner($partner_id)
	{
		$sql = str_replace("#img_path#", Config::get('gumamax.productImagesUrl'), $this->sqlOrders);
		$sql = str_replace("%%%where%%%", " c.shipping_to_partner_id = {$partner_id} ", $sql);
    	$orders = DB::select($sql);

		foreach ($orders as $order) {
			$order->order_items = $this->getOrderedItems($order->order_id);
		}
    	return $orders;
	}

	public function getOrdersForPartnerForPeriod($partner_id, $start_date, $end_date)
	{
		$out = new static();
		$sql = str_replace("%%%where%%%", " c.shipping_to_partner_id = {$partner_id} AND c.ordered_at BETWEEN '{$start_date}' and '{$end_date}' ", $this->sqlOrders);
		$out->orders = DB::select($sql);
		$out->total = DB::table('cart')
						->whereNull('deleted_at')
						->whereNotNull('order_id')
						->whereBetween('ordered_at', array($start_date, $end_date))
						->where('shipping_to_partner_id','=',$partner_id)
						->sum('total_amount_incl_tax');
		return $out;
	}

	public function getOrderedItems($id)
	{
		$sql = "SELECT
					ci.cart_item_id,
					ci.cart_id,
					ci.stavka_id,
					ci.merchant_id,
					ci.product_id,
					ci.product_name,
					ci.additional_description,
					ci.manufacturer_name,
					ci.cat_no,
					ci.country_of_origin,
					ci.qty,
					ci.old_qty,
					ci.list_price,
					ci.list_amount,
					ci.promo_code,
					ci.discount,
					ci.discount_amount,
					ci.price_incl_tax,
					ci.amount_incl_tax,
					ci.amount_excl_tax,
					ci.tax_id,
					ci.tax_rate,
					ci.tax_amount,
					ci.weight,
					ci.created_at,
					ci.deleted_at,
					ci.updated_at,
					p.avg_overall_rating,

					pw.value_num product_weight,
					vt.value_text vehicle_type,
					wi.value_text width,
					ra.value_text ratio,
					di.value_text diameter,
					se.value_text season,
					CONCAT(COALESCE(CONCAT('#img_path#', pi.image_id, pi.ext),concat('#img_path#','noimage.png')),
					'&transform=resize,60,60') AS image_name_60
				FROM cart c
				JOIN cart_item ci ON ci.cart_id=c.cart_id
				JOIN product p ON p.product_id=ci.product_id
				LEFT JOIN product_image pi ON pi.product_id = ci.product_id AND pi.is_default = 1 AND COALESCE(pi.is_deleted,0) = 0
				LEFT JOIN product_dimension pw ON pw.product_id = p.product_id AND pw.dimension_id = 1
				LEFT JOIN product_dimension vt ON vt.product_id = p.product_id AND vt.dimension_id = 10
				LEFT JOIN product_dimension wi ON wi.product_id = p.product_id AND wi.dimension_id = 11
				LEFT JOIN product_dimension ra ON ra.product_id = p.product_id AND ra.dimension_id = 12
				LEFT JOIN product_dimension di ON di.product_id = p.product_id AND di.dimension_id = 13
				LEFT JOIN product_dimension se ON se.product_id = p.product_id AND se.dimension_id = 14
				WHERE
					c.order_id={$id}
					AND ci.deleted_at IS NULL
					/*AND COALESCE(ci.qty,0)>0*/";
		$sql = str_replace("#img_path#", Config::get('gumamax.productImagesUrl'), $sql);
		return DB::select($sql);
	}

}


