<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 27.10.2016
 * Time: 8:09
 */

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\DmxBaseController;
use Crm\Models\Cover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CoverController extends DmxBaseController
{

    public function upload(Request $request){

        $file = $request->file('cover');

        $pageId = $request->get('page_id');

        $coverId = $request->get('id');

        if ($coverId) {
            $cover = Cover::find($coverId);
        } else {
            $cover = new Cover(['coverable_type'=>'Crm\\Models\\MemberPage', 'coverable_id'=>$pageId]);
        }

        $destinationPath = '/uploads/pages/'.$pageId.'/cover/';

        $absolutePath = public_path() . $destinationPath;

        $fileExt = $file->getClientOriginalExtension();

        $fileName = 'cover.'.$fileExt;

        $mime = $file->getMimeType();

        $size = $file->getSize();

        $upload_success = $file->move($absolutePath, $fileName);

        if ($upload_success) {
            $cover->full_name    = $destinationPath.$fileName;
            $cover->file_name    = $fileName;
            $cover->mime         = $mime;
            $cover->size         = $size;
            $cover->save();

            if ($request->ajax()){

                return $this->respond($cover);

            } else {

                return redirect()->back();
            }
        }
    }

    public function delete(Request $request, $id){
        
        $cover = Cover::find($id);

        if ($cover){

            $isDeleted = File::delete(public_path(). $cover->full_name);

            if ($isDeleted){

                $cover->delete();

                if ($request->ajax()){

                    return $this->respond('OK');

                } else {

                    return redirect()->back();
                }
            }
        }
        abort(404);
    }

}