<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 26.10.2016
 * Time: 11:41
 */

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\DmxBaseController;
use Crm\Models\Logo;
use Crm\Requests\SaveLogoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class LogoController extends DmxBaseController
{

    public function upload(SaveLogoRequest $request){

        $file = $request->file('logo');

        $pageId = $request->get('page_id');

        $logoId = $request->get('id');

        if ($logoId) {
            $logo = Logo::find($logoId);
        } else {
            $logo = new Logo(['logoable_type'=>'Crm\\Models\\MemberPage', 'logoable_id'=>$pageId]);
        }

        $destinationPath = '/uploads/pages/'.$pageId.'/logo/';

        $absolutePath = public_path() . $destinationPath;

        $fileExt = $file->getClientOriginalExtension();

        $fileName = 'logo.'.$fileExt;

        $mime = $file->getMimeType();

        $size = $file->getSize();

        $upload_success = $file->move($absolutePath, $fileName);

        if ($upload_success) {
            $logo->full_name    = $destinationPath.$fileName;
            $logo->file_name    = $fileName;
            $logo->mime         = $mime;
            $logo->size         = $size;
            $logo->save();

            if ($request->ajax()){

                return $this->respond($logo);

            } else {

                return redirect()->back();
            }
        }

    }

    public function delete(Request $request, $id){

        $logo = Logo::find($id);

        if ($logo){

            $isDeleted = File::delete(public_path(). $logo->full_name);

            if ($isDeleted){

                $logo->delete();

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