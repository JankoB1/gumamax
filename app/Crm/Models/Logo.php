<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 26.10.2016
 * Time: 6:20
 */

namespace Crm\Models;


use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{

    protected $connection='CRM';
    protected $table='logos';
    protected $primaryKey='id';
    protected $fillable=['logoable_type', 'logoable_id', 'full_name', 'file_name', 'mime', 'size'];

    public function logoable(){

        return $this->morphTo();

    }

}