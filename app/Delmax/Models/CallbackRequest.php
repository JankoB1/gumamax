<?php

namespace Delmax\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class CallbackRequest extends Model {

    use SoftDeletes;

    protected $connection ='ApiDB';

    protected $table = 'callback_request';

    protected $fillable = [
        'app_id',
        'project_id',
        'name',
        'phone',
        'asap',
        'scheduled_date',
        'scheduled_time_span_id',
        'called_at',
        'agent_id',
        'answer',
        'closed'
    ];

    protected $appends = ['scheduled_time_span_txt'];

    protected $casts = [
        'called_at' => 'datetime:d.m.Y H:i:s',
    ];
   


    public static function make(array $data) {

        $cb_request = new static ($data);

        return $cb_request;
    }

    public static function getData(Request $request) {

        $data = $request->only(['name', 'phone']);

        $data['app_id'] = 2;
        $data['project_id'] = 2;

        if ($request->get('when_cb') == 'now') {
            $data['asap'] = 1;
        } else
        if ($request->get('when_cb') == 'scheduled') {
            $data['scheduled_date'] = Carbon::createFromFormat('d.m.Y',$request->get('when_date_cb'));
            $data['scheduled_time_span_id'] = $request->get('when_time_cb');
        }

        return $data;
    }

    public static function apiDatatables($status){

        switch ($status){
            case 'opened': {
                return CallbackRequest::whereNull('closed')->orderBy('asap', 'desc')->get();
                break;
            }
            case 'closed':{
                return CallbackRequest::whereNotNull('closed')->get();
                break;
            }
            default :{
                return null;
                break;
            }
        }
    }

    public static function apiCount($status){
        if ($status=='opened') {
            return CallbackRequest::whereNull('closed')->count();
        } else {
            return CallbackRequest::whereNotNull('closed')->count();
        }

    }  

    public function getScheduledTimeSpanTxtAttribute()
    {
        return substr($this->scheduled_time_span_id, 0, 2). '-'. substr($this->scheduled_time_span_id, -2);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone(Config::get('app.timezone'))->format('d.m.Y H:i:s');
    }
}