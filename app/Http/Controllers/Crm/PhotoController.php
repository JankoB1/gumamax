<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 27.10.2016
 * Time: 10:29
 */

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\DmxBaseController;
use Crm\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class PhotoController extends DmxBaseController
{

    public function upload(Request $request){

        $file = $request->file('photo');

        $pageId = $request->get('page_id');

        $photoId = $request->get('id');

        if ($photoId) {
            $photo = Photo::find($photoId);
        } else {
            $photo = new Photo(['imageable_type'=>'Crm\\Models\\MemberPage', 'imageable_id'=>$pageId]);
        }

        $fileName =  $file->getClientOriginalName();

        $mime = $file->getMimeType();

        $size = $file->getSize();

        $destinationPath = 'uploads/pages/'.$pageId.'/photo/';

        $thumbsDestinationPath = $destinationPath . 'thumbs/';

        $img = Image::make($file->getRealPath());

        $thumbName = $this->createThumbnail($img, $fileName, $thumbsDestinationPath, 180, 100);

        $absolutePath = public_path($destinationPath);

        $upload_success = $file->move($absolutePath, $fileName);

        if ($upload_success) {

            $photo->full_name    = $destinationPath.$fileName;
            $photo->file_name    = $fileName;
            $photo->mime         = $mime;
            $photo->size         = $size;
            $photo->thumb_name   = $thumbName;
            $photo->save();

            if ($request->ajax()){

                return $this->respond($photo);

            } else {

                return redirect()->back();
            }
        }

    }

    public function delete(Request $request, $id){

        $photo = Photo::find($id);

        if ($photo){

            $isDeleted = File::delete(public_path($photo->full_name));
            $isDeleted = ($isDeleted && File::delete(public_path($photo->thumb_name)));
            if ($isDeleted){

                $photo->delete();

                if ($request->ajax()){

                    return $this->respond('OK');

                } else {

                    return redirect()->back();
                }
            }
        }
        abort(404);

    }

    private function createThumbnail($img, $fileName, $thumbsDestinationPath, $width, $height){

        $thumbsDir = public_path($thumbsDestinationPath);



        if (!(File::exists($thumbsDir))){
            File::makeDirectory($thumbsDir,  0755, true, true);
        }

        $thumbName = $thumbsDir.$fileName;

        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->save($thumbName);

        return $thumbsDestinationPath . $fileName;

    }

    public function migratePagesPhoto($fileName, $thumbsDestinationPath, $srcFileName, $dstFileName){

        $srcFileName = public_path(mb_substr($srcFileName,1));
        File::copy($srcFileName, public_path($dstFileName));

        $img = Image::make($srcFileName);

        $this->createThumbnail($img, $fileName, $thumbsDestinationPath, 180, 100);

    }

    public function migratePhotos(){
        $sql = "
        select
            mp.member_id,
            mp.id as page_id,
            ph.id as photo_id,
            ph.file_name as file_name,
            ph.full_name as src_file,
            concat('uploads/pages/', mp.id, '/photos/') as  photo_path,
            concat('uploads/pages/', mp.id, '/photos/',ph.file_name) as dst_file,
            concat('uploads/pages/', mp.id, '/photos/thumbs/') as  thumbs_path,
            concat('uploads/pages/', mp.id, '/photos/thumbs/',ph.file_name) as dst_thumb_file
        from photos ph
          join member_page mp on mp.id = ph.imageable_id
          join member m on m.id=mp.member_id
        ";
        $photos = DB::connection('CRM')->select($sql);

        foreach ($photos as $photo){

            if (!(File::exists($photo->photo_path))){
                File::makeDirectory($photo->photo_path,  0755, true, true);
            }

            $this->migratePagesPhoto($photo->file_name, $photo->thumbs_path, $photo->src_file, $photo->dst_file);
            $photoModel = Photo::find($photo->photo_id);
            if ($photoModel){
                $photoModel->full_name = $photo->dst_file;
                $photoModel->thumb_name = $photo->dst_thumb_file;
                $photoModel->save();
            }
        }
    }

}