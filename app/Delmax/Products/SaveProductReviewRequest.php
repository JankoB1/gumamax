<?php
/**
 * Created by PhpStorm.
 * User: Bane
 * Date: 19.10.2015
 * Time: 12:34
 */

namespace Delmax\Products;

use App\Http\Requests\Request;

class SaveProductReviewRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required',
            'overall_rating' => 'required|numeric',
            'dry_traction' => 'required|numeric',
            'wet_traction' => 'required|numeric',
            'steering_feel' => 'required|numeric',
            'quietness' => 'required|numeric',
            'purchase_again' => 'required|numeric',
            'nickname' => 'required|max:64',
            'review_title' => 'required|max:128',
            'review_product' => 'required',
            'site_rating' => 'required|numeric',
            'site_review' => 'required'
        ];
    }
}