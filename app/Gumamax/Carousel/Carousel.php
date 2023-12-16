<?php namespace Gumamax\Carousel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Carousel extends Model
{
    use SoftDeletes;

    protected $connection = 'delmax_gumamax';

    protected $table = 'carousel';

    protected $primaryKey='carousel_id';


    public static function getCached(){

        return Carousel::whereRaw('now() BETWEEN `datetime_start` AND `datetime_end`')->get();

    }

   public function onlyActive(){

       return $this->whereRaw('now() BETWEEN `datetime_start` AND `datetime_end`')->get();

   }

    public function getPictures()
    {
        $directory = 'carousel';

        if(is_dir($directory)){
            $pics = array_diff(scandir($directory), ['..', '.', 'logo.png']);
        } else {
            $pics = [];
        }

        return $pics;
    }

}