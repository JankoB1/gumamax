<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 2.10.2016
 * Time: 12:17
 */

namespace Crm\Models;


use Delmax\Attachments\Attachment;

class PartnerCover extends Partner
{

    public function file(){

        return $this->morphOne(Attachment::class, 'attachable');

    }

}