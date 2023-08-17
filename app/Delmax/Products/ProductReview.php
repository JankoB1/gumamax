<?php

namespace Delmax\Products;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model{

	protected $connection = 'delmax_catalog';

	protected $table = 'product_review';

	protected $primaryKey = 'product_review_id';

	protected $fillable = [
		'product_id',
		'user_id',
		'overall_rating',
		'dry_traction',
		'wet_traction',
		'steering_feel',
		'quietness',
		'purchase_again',
		'nickname',
		'email',
		'review_title',
		'review_product',
		'site_rating',
		'site_review',
		'approved_at',
		'rejected_at',
		'approved_by_user_id',
		'rejected_by_user_id',
	];

	public static function make(array $data) {

		$review = new static ($data);

		return $review;
	}


	public static function getRoadConditions(){
		return DB::table('road_condition')->get();
	}

	public static function storeProductReview($rev){
		DB::table('product_review')->insert(array(
			'product_id' => $rev['product_id'],
			'overall_rating' => $rev['overall_rating'],
			'dry_traction' => $rev['dry_traction'],
			'wet_traction' => $rev['wet_traction'],
			'steering_feel' => $rev['steering_feel'],
			'quietness' => $rev['quietness'],
			'purchase_again' => $rev['purchase_again'],
			'nickname' => $rev['nickname'],
			'review_title' => $rev['review_title'],
			'review_product' => $rev['review_product'],
			'site_rating' => $rev['site_rating'],
			'site_review' => $rev['site_review'],
			'created_at' => date('Y-m-d H:i:s')
		));
	}

	public static function storePartnerReview($rev){
		DB::table('crm_partner_review')
			->insert(array(
				"partner_id" => $rev['partner_id'],
				"overall_rating" => $rev['overall'],
				"cleanliness_of_facilities_rating"=>$rev['cleanliness'],
				"friendliness_of_staff_rating"=>$rev['friendliness'],
				"responsiveness_rating"=>$rev['responsiveness'],
				"using_again_rating"=>$rev['using_again'],
				"recommend_partner_rating"=>$rev['recommend'],
				"review_title"=>$rev['review_title'],
				"review"=>$rev['review'],
				"nickname"=>$rev['nickname'],
				"city_id"=>$rev['city_id'],
				"email"=>$rev['email'],
				"created_at"=> date('Y-m-d H:i:s')
		));
	}


	public static function getLatestReview($id,$reviewType){

        switch ($reviewType) {
			case 'product':
				$review = DB::select("SELECT
						pr.product_review_id,
						pr.product_id,
						pr.overall_rating,
						pr.dry_traction,
						pr.wet_traction,
						pr.steering_feel,
						pr.quietness,
						pr.purchase_again,
						pr.city_id,
						pr.nickname,
						pr.review_title,
						pr.review_product,
						pr.site_rating,
						pr.site_review,
						pr.created_at,
						pr.updated_at,
						pr.deleted_at,
						pr.approved_at,
						c.city_name
					from product_review pr
					join city c on pr.city_id=c.city_id
					where (pr.product_id = {$id}) and (pr.approved_at is not null) and (pr.deleted_at is null)
					order by pr.created_at desc LIMIT 0,1");
			break;

			case 'partner':
				$review = DB::select("SELECT
						pr.partner_review_id,
						pr.partner_id,
						pr.overall_rating,
						pr.cleanliness_of_facilities_rating,
						pr.friendliness_of_staff_rating,
						pr.responsiveness_rating,
						pr.using_again_rating,
						pr.recommend_partner_rating,
						pr.review_title,
						pr.nickname,
						pr.city_id,
						pr.email,
						pr.review,
						pr.created_at,
						pr.deleted_at,
						pr.updated_at,
						pr.approved_at,
						c.city_name
					from crm_partner_review pr
					join city c on pr.city_id=c.city_id
					where (pr.partner_id = {$id}) and (pr.approved_at is not null) and (pr.deleted_at is null)
					order by pr.created_at desc LIMIT 0,1");
			break;
		}
		if (count($review)>0) {
            return $review[0];
        }
	}

	public static function getAllReviews($id,$reviewType){
		switch ($reviewType) {
			case 'product':
				$reviews = DB::select("SELECT
					pr.product_review_id,
					pr.product_id,
					pr.overall_rating,
					pr.dry_traction,
					pr.wet_traction,
					pr.steering_feel,
					pr.quietness,
					pr.purchase_again,
					pr.city_id,
					pr.nickname,
					pr.review_title,
					pr.review_product,
					pr.site_rating,
					pr.site_review,
					pr.created_at,
					pr.updated_at,
					pr.deleted_at,
					pr.approved_at,
					c.city_name
				from product_review pr
				join city c on pr.city_id=c.city_id
				where (pr.product_id = {$id}) and (pr.approved_at is not null) and (pr.deleted_at is null)");
			break;

			case 'partner':
				$reviews = DB::select("SELECT
					pr.partner_review_id,
					pr.partner_id,
					pr.overall_rating,
					pr.cleanliness_of_facilities_rating,
					pr.friendliness_of_staff_rating,
					pr.responsiveness_rating,
					pr.using_again_rating,
					pr.recommend_partner_rating,
					pr.review_title,
					pr.nickname,
					pr.city_id,
					pr.email,
					pr.review,
					pr.created_at,
					pr.deleted_at,
					pr.updated_at,
					pr.approved_at,
					c.city_name
				from crm_partner_review pr
				join city c on pr.city_id=c.city_id
				where (pr.partner_id = {$id}) and (pr.approved_at is not null) and (pr.deleted_at is null)");
			break;
		}

		return $reviews;
	}

	public static function updateAvgRatingAll($type) {
		switch ($type) {
			case 'partner':
				$sql = "UPDATE partner p INNER JOIN (SELECT DISTINCT partner_id, AVG(overall_rating) AS avg_rating FROM crm_partner_review GROUP BY partner_id) r ON r.partner_id=p.partner_id SET p.avg_overall_rating=r.avg_rating";
				DB::update($sql);
			break;

			case 'product':
				$sql = "UPDATE product p INNER JOIN (SELECT DISTINCT product_id, AVG(overall_rating) AS avg_rating FROM product_review GROUP BY product_id) r ON r.product_id=p.product_id SET p.avg_overall_rating=r.avg_rating";
				DB::update($sql);
			break;
		}
	}

	public static function approvePartnerReview($id) {
		DB::table('crm_partner_review')->where('partner_review_id','=',$id)->update(array('approved_at'=>date('Y-m-d H:i:s')));
		$partner_id = DB::select("SELECT partner_id FROM crm_partner_review WHERE partner_review_id = $id")[0];
		return static::updateAvgRating($partner_id->partner_id,'partner');
	}

	public static function updateAvgRating($id, $type) {
		switch ($type) {
			case 'partner':
				$avgArr = DB::select("SELECT AVG(overall_rating) as avgOR
									  FROM crm_partner_review
									  WHERE partner_id={$id} AND
									    	rejected_at IS NULL AND
											deleted_at IS NULL AND
											approved_at IS NOT NULL"
				);
				if(isset($avgArr) && count($avgArr)==1){
					$avg = $avgArr[0]->avgOR;
				} else {
					$avg = 0;
				}

				DB::table('partner')->where('partner_id','=',$id)->update(array("avg_overall_rating"=>$avg));
			break;

			case 'product':
				$avgArr = DB::select( "SELECT AVG(overall_rating) AS avgOR
									FROM product_review
									WHERE product_id={$id} AND
										  rejected_at IS NULL AND
										  deleted_at IS NULL AND
										  approved_at IS NOT NULL"
				);
				if(isset($avgArr) && count($avgArr)==1){
					$avg = $avgArr[0]->avgOR;
				} else {
					$avg = 0;
				}
				DB::table('product')->where('product_id','=',$id)->update(array("avg_overall_rating"=>$avg));
			break;
		}
	}

	public static function approveProductReview($id) {
		DB::table('product_review')->where('product_review_id','=',$id)->update(array('approved_at'=>date('Y-m-d H:i:s')));
		$product_id = DB::select("SELECT product_id FROM product_review WHERE product_review_id = $id")[0];
		return static::updateAvgRating($product_id->product_id,'product');
	}


	public static function rejectPartnerReview($id) {
		DB::table('crm_partner_review')->where('partner_review_id','=',$id)->update(array('rejected_at'=>date('Y-m-d H:i:s')));
	}

	public static function rejectProductReview($id) {
		DB::table('product_review')->where('product_review_id','=',$id)->update(array('rejected_at'=>date('Y-m-d H:i:s')));
	}


	public static function getPartnerReviewsForApproval() {
		$rev = DB::select("SELECT
			cpr.partner_review_id,
			cpr.partner_id,
			cpr.overall_rating,
			cpr.cleanliness_of_facilities_rating,
			cpr.friendliness_of_staff_rating,
			cpr.responsiveness_rating,
			cpr.using_again_rating,
			cpr.recommend_partner_rating,
			cpr.review_title,
			cpr.nickname,
			cpr.city_id,
			cpr.email,
			cpr.review,
			cpr.created_at,
			cpr.deleted_at,
			cpr.updated_at,
			cpr.approved_at,
			cpr.rejected_at,
			c.city_name,
			c.postal_code,
			p.name,
			p.department,
			p.avg_overall_rating
			FROM crm_partner_review cpr
			LEFT JOIN city c ON cpr.city_id=c.city_id
			LEFT JOIN partner p ON cpr.partner_id=p.partner_id
			WHERE cpr.deleted_at is NULL AND cpr.rejected_at is NULL AND cpr.approved_at is NULL");
		return $rev;
	}

	public static function getProductReviewsForApproval() {
		$rev = DB::select("SELECT
			pr.product_review_id,
			pr.product_id,
			pr.user_id,
			pr.overall_rating,
			pr.road_condition_id,
			pr.dry_traction,
			pr.wet_traction,
			pr.steering_feel,
			pr.quietness,
			pr.purchase_again,
			pr.city_id,
			pr.nickname,
			pr.review_title,
			pr.review_product,
			pr.site_rating,
			pr.site_review,
			pr.created_at,
			pr.updated_at,
			pr.deleted_at,
			pr.approved_at,
			pr.rejected_at,

			p.product_id,
			p.description_id,
			p.manufacturer_id,
			p.cat_no,
			p.country_of_origin,
			p.additional_description,
			p.avg_overall_rating,

			c.city_name,
			c.postal_code,

			m.description as manufacturer_name

			FROM product_review pr
			LEFT JOIN product p ON pr.product_id=p.product_id
			LEFT JOIN city c ON pr.city_id=c.city_id
			LEFT JOIN manufacturer m ON m.manufacturer_id=p.manufacturer_id
			WHERE pr.rejected_at is NULL AND pr.deleted_at is NULL AND pr.approved_at is NULL");
		return $rev;
	}

}
