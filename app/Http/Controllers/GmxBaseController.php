<?php namespace App\Http\Controllers;


class GmxBaseController extends Controller {

	protected $cyr  = array('а','б','в','г','д','ђ','е','ж','з','и','ј','к','л','љ','м',
                'н','њ','о','п','р','с','т','ћ','у','ф','х','ц','ч','џ','ш',
                'А','Б','В','Г','Д','Ђ','Е','Ж','З','И','Ј','К','Л','Љ','М',
                'Н','Њ','О','П','Р','С','Т','Ћ','У','Ф','Х','Ц','Ч','Џ','Ш');

	protected $lat  = array('a','b','v','g','d','đ','e','ž','z','i','j','k','l','lj','m',
                'n','nj','o','p','r','s','t','ć','u','f','h','c','č','dž','š',
                'A','B','V','G','D','Đ','E','Ž','Z','I','J','K','L','Lj','M',
                'N','Nj','O','P','R','S','T','Ć','U','F','H','C','Č','Dž','Š');


    public function __construct(){

    }
    /**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = view($this->layout);
		}
	}

}