<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 11.10.2016
 * Time: 12:30
 */

namespace Gumamax\Partners;


use App\Http\Requests\Request;
use Delmax\Webapp\Traits\CyrToLatTrait;

class SavePartnerAboutRequest extends Request
{
    use CyrToLatTrait;

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
            'number_of_platforms'   => 'integer',
            'number_of_platforms_b' => 'integer',
            'number_of_platforms_d' => 'integer',
            'scheduling_period'     => 'integer',
            'google_local_service'  => 'in:0,1',
            'online_scheduling'     => 'required_with:scheduling_period|in:0,1',
            'place_of_delivery'     => 'in:0,1',
            'free_installation'     => 'in:0,1'
        ];
    }

    public function all($keys = null){

        $data = parent::all($keys);

        $data = $this->transliterateArray($data);

        return $data;
    }
}