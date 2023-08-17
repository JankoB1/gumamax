<?php namespace Delmax\Webapp\Translator;

use Delmax\Webapp\Traits\DmxDBTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 13.8.2015
 * Time: 15:57
 */


class Designation extends Model
{
    use SoftDeletes, DmxDBTrait;

    protected $connection='ApiDB';

    protected $table='lng_designation';
    protected $fillable = ['text'];

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'lng_translation', 'designation_id', 'locale');
    }

    public function translations()
    {
        return $this->hasMany(Translation::class, 'designation_id', 'id');
    }

    public static function allCached($columns=['*']){

        return Cache::remember('designationAllCached', 30, function() use ($columns){

            return Designation::all($columns);

        });

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
            'displayName' => 'Text',
            'name'=>'lng_designation.text',
            'data' => 'text',
            'visible' => true,
            'columnSearch'=> true,
            'search'=> true,
            'order'=> true,
            'group'=>null,

        ]
    ];

    /**
     *  Returns data for jQuery datatables
     *  Column  "checkbox" must be created as first in row and must have class row_check
     *  Column "actions" must be created as last in row and must have class row_actions
     * @return mixed
     */
    public static function datatablesData(){

        $sqlFields = static::getDtSqlFields();

        return Designation::select($sqlFields);

    }
}