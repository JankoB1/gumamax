<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 26.10.2016
 * Time: 6:18
 */

namespace Crm\Models;


use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $connection='CRM';

    protected $table='photos';

    protected $primaryKey='id';

    protected $fillable = ['imageable_type', 'imageable_id', 'file_name', 'full_name', 'thumb_name', 'size', 'mime'];


    public function imageable(){

        return $this->morphTo();
    }


}