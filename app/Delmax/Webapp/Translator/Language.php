<?php namespace Delmax\Webapp\Translator;

use Delmax\Webapp\Traits\DmxDBTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 13.8.2015
 * Time: 16:00
 */


class Language extends Model
{
    use DmxDBTrait;

    protected $connection='ApiDB';

    protected $table='lng_language';

    protected $primaryKey='locale';

    protected $fillable = ['locale', 'name', 'encoding'];

    public function translations(){

        return $this->belongsToMany(Designation::class, 'lng_translation', 'locale', 'designation_id');

    }

    static $dtColumns= [
        [
            'displayName' => '',
            'name'=>'checkbox',
            'data'=>'checkbox',
            'visible' => true,
            'columnSearch'=> false,
            'search'=> false,
            'order'=> false,
            'group'=>null,
            'className'=>'text-align-center'
        ],
        [
            'displayName' => 'locale',
            'name'=>'lng_language.locale',
            'data' => 'locale',
            'visible' => true,
            'columnSearch'=> true,
            'search'=> true,
            'order'=> true,
            'group'=>null,

        ],
        [
            'displayName' => 'Name',
            'name'=>'lng_language.name',
            'data'=>'name',
            'visible' => true,
            'columnSearch'=> true,
            'search'=> true,
            'order'=> true,
            'group'=>null
        ],
        [
            'displayName' => 'Encoding',
            'name'=>'lng_language.encoding',
            'data'=>'encoding',
            'visible' => true,
            'columnSearch'=> true,
            'search'=> true,
            'order'=> true,
            'group'=>null
        ],
    ];

    /**
     *  Returns data for jQuery datatables
     *  Column  "checkbox" must be created as first in row and must have class row_check
     *  Column "actions" must be created as last in row and must have class row_actions
     * @return mixed
     */
    public static function datatablesData(){

        $sqlFields = static::getDtSqlFields();

        return Language::select($sqlFields);

    }


}