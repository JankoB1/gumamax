<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 2.10.2016
 * Time: 12:17
 */

namespace Gumamax\Partners;


use Delmax\Attachments\Attachment;

class PartnerLogo extends Partner
{

    public function file(){

        return $this->morphOne(Attachment::class, 'attachable');

    }

}