<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 12.10.2016
 * Time: 11:16
 */

namespace Crm\Models;


use Illuminate\Database\Eloquent\Model;

class MemberPage extends Model{

    protected $connection = 'CRM';
    protected $table = 'member_page';
    protected $primaryKey = 'id';
    protected $fillable = ['member_id', 'name', 'headline', 'headline2', 'short_description', 'long_description', 'subdomain'];

    public function logo(){

        return $this->morphOne(Logo::class, 'logoable');

    }

    public function cover(){

        return $this->morphOne(Cover::class, 'coverable');

    }

    public function photos(){

        return $this->morphMany(Photo::class, 'imageable');

    }

    public function member(){

        return $this->belongsTo(Member::class, 'member_id');

    }
}