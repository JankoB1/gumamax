<?php namespace App\Http\Controllers;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 16.3.2015
 * Time: 2:35
 */
use Crm\Models\Partner;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Delmax\Attachments\Attachment;

class AttachmentController extends DmxBaseController
{

    /**
     * @var Partner
     */
    private $partner;

    public function __construct(Partner $partner){
        parent::__construct();

        $this->partner = $partner;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {   $question = new stdClass();
        $question->question_id=52;
        return View::make('upload', compact('question'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $files = Input::file('attachments');

        foreach ($files as $file) {

            $rules = ['file' => 'required|mimes:png,gif,jpeg,jpg,txt,rtf,pdf,doc,docx,xls,xlsx'];

            $validator = Validator::make(['file' => $file], $rules);

            if ($validator->passes()) {

                $destinationPath = public_path() . '/uploads/';

                $fileName = $file->getClientOriginalName();

                $fileExt = $file->getClientOriginalExtension();

                $upload_success = $file->move($destinationPath, $fileName);

                if ($upload_success) {
                    $attachment = new Attachment();
                    $attachment->source_type_id = Input::get('source_type_id');
                    $attachment->source_id = Input::get('source_id');
                    $attachment->filename = $fileName;
                    $attachment->ext = $fileExt;
                    $attachment->save();
                }
            }
        }
    }

    public function download($id)
    {

        /** @var Attachment $attachment */
        $attachment = Attachment::findOrFail($id);

        return $attachment->download();

    }

    public static function upload($file, $rules, $destinationFolderName)
    {

        $validator = Validator::make(['file' => $file], $rules);

        if ($validator->passes()) {

            $destinationPath = str_replace( '//', '/', public_path() .'/'. $destinationFolderName );

            $fileName = $file->getClientOriginalName();

            return $file->move($destinationPath, $fileName);
        }
    }

    public function moveToAttachments(){

        $oldSrcDir = public_path().'/img/partners';

        $newSrcDir = public_path().'/uploads/partners';

        $partnerDirList = $this->nothidden($oldSrcDir);

        $oldFileList =[];

        foreach($partnerDirList as $partnerDir){

                $partnerPath = $oldSrcDir.'/'.$partnerDir;

                if (is_dir($partnerPath)){

                    $oldFileList[$partnerDir] = $this->nothidden($partnerPath, true);

                }
        }

        $newFileLists =[];

        foreach($oldFileList as $signUpId=>&$oldFiles){

            $this->partner  = Partner::where(['signup_id' => $signUpId])->first();
            if ($this->partner ){
                $newPartnerDir = $newSrcDir.'/'.$this->partner->partner_id;
                if (!file_exists($newPartnerDir)) {
                    mkdir($newPartnerDir, 0777, true);
                }
                $order_index = 0;
                foreach($oldFiles as &$oldfile){
                    $oldfile['new_file_name'] = $newPartnerDir.'/'.$oldfile['name'];

                    $fileCopied = File::copy($oldfile['full_name'], $oldfile['new_file_name']);

                    if ($fileCopied){

                        $attachment = new Attachment();

                        $attachment->attachable_type = 'Gumamax\Partners\Partner';

                        $attachment->attachable_id = $this->partner->partner_id;

                        $attachment->file_name = $oldfile['name'];

                        if (preg_match('/^cover./',$attachment->file_name)){
                            $attachment->file_order_index = 0;
                            $attachment->attachable_type = 'Gumamax\Partners\PartnerCover';
                        }else if (preg_match('/^logo./',$attachment->file_name)){
                            $attachment->file_order_index = 0;
                            $attachment->attachable_type = 'Gumamax\Partners\PartnerLogo';
                        }else{
                            $order_index ++;
                            $attachment->file_order_index = (int) $oldfile['name'];
                        }

                        $attachment->mime = File::mimeType($oldfile['new_file_name']);

                        $attachment->size = File::size($oldfile['new_file_name']);

                        $attachment->file_path = '/uploads/partners/'.$this->partner->partner_id.'/';

                        $attachment->save();
                    }
                }

                $newFileLists[$this->partner->partner_id]=$oldFiles;
            }

        }

        return $newFileLists;

    }

    function nothidden($path, $returnFullPath=false) {
        $nothidden=[];
        $files = scandir($path);
        foreach($files as $file) {

            if ($file[0] != '.') {
                if ($returnFullPath){
                    $nothidden[] = ['full_name'=> $path . '/'. $file, 'name'=>$file];
                }else
                    $nothidden[] = $file;
            }
        }

        return $nothidden;
    }

    public function partnerAttachments($partnerId){

        $this->partner = Partner::find($partnerId);
        $logo = $this->partner->logo->file;
        $cover = $this->partner->cover->file;
        $attachments = $this->partner->attachments;
        return compact('logo', 'cover', 'attachments');
    }
}
