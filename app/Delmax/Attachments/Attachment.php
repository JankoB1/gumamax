<?php
namespace Delmax\Attachments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Attachment extends Model {

    use SoftDeletes;

    protected $connection = 'CRM';

    protected $table = 'attachment';

    protected $primaryKey = 'id';

    protected $fillable=['attachable_type, attachable_id, file_order_index, file_name', 'file_path', 'mime', 'size'];

    /**
     * Get all of the owning atachable models.
     */
    public function attachable()
    {
        return $this->morphTo();
    }

    public function updateFileInfo(UploadedFile $file, $filePath)
    {
        $this->file_name = $file->getClientOriginalName();

        $this->mime = $file->getClientMimeType();

        $this->size = $file->getClientSize();

        $this->file_path = $filePath;

        return $this->save();

    }

    public function updateCopiedFileInfo($fileName, $filePath)
    {

        $this->file_name = File::name($fileName);

        $this->mime = File::mimeType($fileName);

        $this->size = File::size($fileName);

        $this->file_path = $filePath;

        return $this->save();

    }

    public function download()
    {

        $file = public_path(). $this->file_path . $this->file_name;

        $headers = ['Content-Type'=> $this->mime];

        return Response::download($file, $this->file_name, $headers);
    }
}
