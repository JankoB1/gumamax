<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 26.10.2016
 * Time: 6:20
 */

namespace Crm\Models;


use Illuminate\Database\Eloquent\Model;

class Cover extends Model
{

    protected $connection='CRM';

    protected $table='covers';

    protected $primaryKey='id';

    protected $fillable =['coverable_type', 'coverable_id', 'full_name', 'file_name', 'mime', 'size'];


    public function coverable(){

        return $this->morphTo();

    }

}