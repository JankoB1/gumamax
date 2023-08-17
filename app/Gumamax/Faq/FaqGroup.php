<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 26.9.2016
 * Time: 20:19
 */

namespace Gumamax\Faq;


use Illuminate\Database\Eloquent\Model;

class FaqGroup extends Model
{
    protected $connection='delmax_gumamax';

    protected $table ='faq_group';

    protected $primaryKey ='faq_group_id';

    public function faqs(){

        return $this->hasMany(Faq::class, 'faq_group_id')->orderBy('order_index');

    }

}