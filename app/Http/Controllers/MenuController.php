<?php namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 26.9.2016
 * Time: 23:04
 */

use Aws\CloudFront\Exception\Exception;
use Delmax\Webapp\Layout\DmxMenuBuilder;
use Gumamax\Layout\DmxMenuBuilderAce3;
use Gumamax\Layout\Menu;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Response;

class MenuController extends DmxBaseController
{

    public function index(){

        return view('admin.menu.index-tree');

    }

    public function create(){

        $model = new Menu();

        /**
         * Postavljanje default vrednosti za polje
        */

        $model->parent_id=20000000;

        $formMethod = 'POST';

        $formUrl = route('admin.menu.store');

        return view('admin.menu.edit', compact('model', 'formMethod', 'formUrl'));

    }

    public function store(Request $request){

        $data = $request->all();

        Menu::create($data);

        return redirect()->route('admin.menu.index');

    }

    public function edit(Request $request, $id){

        $model = Menu::find($id);

        if ($model){

            $formMethod = 'PUT';

            $formUrl = route('admin.menu.update', [$id]);

            return view('admin.menu.edit', compact('model', 'formMethod', 'formUrl'));

        }

        abort(404);

    }

    public function update(Request $request, $id){

        $data = $request->get('data');

        foreach ($data as $d) {

            $model = Menu::find($d['id']);

            $model->update($d);

            $model->save();

        }

        $this->flushCache('menu');

        return $this->respond($data);

    }

    public function apiDatatables(){

        $query = Menu::all();

        $d = datatables()::of($query);

        return $d->make(true);

    }

    public function apiItems() {

        $items = Menu::getItems();

        return compact('items');

    }

}