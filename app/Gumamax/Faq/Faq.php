<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 26.9.2016
 * Time: 20:17
 */

namespace Gumamax\Faq;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $connection='delmax_gumamax';

    protected $table ='faq';

    protected $primaryKey ='faq_id';

}