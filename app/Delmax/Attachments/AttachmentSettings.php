<?php namespace Delmax\Attachments;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 14.3.2015
 * Time: 18:22
 */


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttachmentSettings extends Model {

   use SoftDeletes;

    protected $connections = 'ApiDB';

    protected $table = 'attachment_settings';

    public function decodeFilePath($id)
    {
        return str_replace('{{id}}', $id, $this->folder_name);
    }
}
