<?php namespace App\Gumamax\BusinessRules;

use DB;

class Turnover {
	public static function getPartnersAutoComplete()
	{
        return DB::select("SELECT
	           p.partner_id,
	           p.partner_id as value,
	           CONCAT_WS(' ', p.name, coalesce(concat(' ', p.department), ''), p.address, c.city_name, c.postal_code, '( ID :',p.partner_id,')') as label
	        FROM partner p
	          join city c ON c.city_id=p.city_id
	        WHERE p.approved_at is not null and p.deleted_at is null and p.rejected_at is null");
	}

	public static function getMonthsAutocomplete()
	{
		return DB::select("SELECT
				concat(YEAR(c.ordered_at),'-',lpad(MONTH(c.ordered_at),2,'0')) AS label
			FROM cart c
			WHERE
			  c.cart_status_id!=4 AND
			  c.ordered_at IS NOT NULL
			GROUP BY 1
			ORDER BY 1 DESC ");
	}

    public static function getSummaryForPartnerId($partner_id)
    {
    	return DB::select("SELECT
		  concat(YEAR(c.ordered_at),'-',lpad(MONTH(c.ordered_at),2,'0')) AS mesec,
		  ps.description AS stat,
		  COUNT(DISTINCT(c.order_id)) AS brojac,
		  sum(c.amount_excl_tax) AS ukupno,
		  sum(c.amount_excl_tax)*(ss.value_dec/100) AS provizija
		FROM cart c
		  LEFT JOIN partner p ON c.shipping_to_partner_id=p.partner_id
		  LEFT JOIN payment_status ps ON ps.payment_status_id=c.payment_status_id
		  LEFT JOIN site_settings ss ON ss.description = 'merchant_commission'
		WHERE
		  c.cart_status_id!=4 AND
		  c.ordered_at IS NOT NULL AND
		  c.shipping_to_partner_id = {$partner_id}
		GROUP BY 1
		ORDER BY mesec DESC, c.shipping_to_partner_id");
    }

}