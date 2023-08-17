<?php namespace App\Http\Controllers;

use Gumamax\Carousel\Carousel;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;


class CarouselController extends DmxBaseController
{

    /**
     * @var Carousel
     */
    private $carousel;

    public function __construct(Carousel $carousel){

        parent::__construct();

        $this->carousel = $carousel;
    }

    public function apiCarousel(){
        return Carousel::getCached();
    }

    public function index()
    {
        return view('admin.carousel.index');
    }

    public function apiDatatables($active=''){

        if ($active=='1'){
            $query = $this->carousel->onlyActive();
        } else {
            $query = $this->carousel->all();
        }

        $d = datatables()::of($query);

        return $d->make(true);

    }

    public function create()
    {
        $carousel = new Carousel();

        $carousel->datetime_start = date('Y-m-d H:i:s');

        $date = strtotime("+30 day");

        $carousel->datetime_end = date('Y-m-d H:i:s', $date);

        $pictures = $this->carousel->getPictures();

        $formMethod = 'POST';

        $formUrl = 'admin/carousel';

        return view('admin.carousel.edit', compact('carousel', 'pictures', 'formMethod', 'formUrl'));

    }

    public function store(Request $request)
    {
        $data = $request->all();

        Carousel::create($data);

        return redirect('/admin/carousel');

    }

    public function edit($id)
    {
        $carousel = $this->carousel->find($id);

        $pictures = $this->carousel->getPictures();

        $formMethod = 'PUT';

        $formUrl = 'admin/carousel/'.$id;

        return view('admin.carousel.edit', compact('carousel', 'pictures', 'formMethod', 'formUrl'));

    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $model = $this->carousel->find($id);

        if ($model){
            $model->datetime_start  = $data['datetime_start'];
            $model->datetime_end    = $data['datetime_end'];
            $model->carousel_type   = $data['carousel_type'];
            $model->link_type       = $data['link_type'];
            $model->link_to         = $data['link_to'];
            $model->html_id         = $data['html_id'];
            $model->image           = $data['image'];
            $model->text            = $data['text'];

            if ($data['active']==1) {
                DB::connection('delmax_gumamax')->table('carousel')->where(['active'=>1])->update(["active"=>null]);
                $model->active=1;
            }

            $model->save();
        }

        return redirect('/admin/carousel');

    }


    public function deletePicture($pic)
    {
        $pict = 'carousel/'.$pic;

        if(file_exists($pict) && is_file($pict)) {
            unlink($pict);
        }

        return redirect()->back();
    }

    public function indexPictures()
    {
        $pictures = $this->carousel->getPictures();

        return view('admin.carousel.pictures', compact('pictures'));
    }

    public function uploadPictures()
    {
        foreach($_FILES["file"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["file"]["tmp_name"][$key];
                $name = $_FILES["file"]["name"][$key];
                move_uploaded_file($tmp_name, "carousel/$name");
            }
        }

        return redirect()->back();
    }
}