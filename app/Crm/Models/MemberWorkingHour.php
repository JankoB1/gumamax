<?php namespace Crm\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class MemberWorkingHour extends Model {
	use SoftDeletes;

	protected $connection = 'CRM';
	protected $table = 'member_working_hour';
	protected $primaryKey = 'id';
	protected $fillable=['member_id', 'day_of_week_id', 'start_time', 'end_time', 'info'];

	public function dayOfWeek(){

		return $this->hasOne(DayOfWeek::class, 'id', 'day_of_week_id');

	}

	public function monday(){

		return $this->where('day_of_week_id',1);

	}

	public function hours(){

		if (mb_strlen(($this->start_time))>0){
			return $this->start_time . ' - '.$this->end_time . ' '.$this->info;
		} else {
			return $this->info;
		}

	}

	public function getStartTimeAttribute($value){
		if ($value){
			return Carbon::parse($value)->format('H:i');
		} else
		return $value;
	}

	public function getEndTimeAttribute($value){
		if ($value){
			return Carbon::parse($value)->format('H:i');
		} else
			return $value;
	}



	public static function datatablesApi($memberId){

		$query = DB::connection('CRM')->table('fw_001.day_of_week')
			->leftJoin('member_working_hour', function($join) use ($memberId){
			  $join->on('member_working_hour.day_of_week_id', '=', 'day_of_week.id')
				  ->where('member_working_hour.member_id', '=', $memberId)
				  ->whereNull('member_working_hour.deleted_at');
			})
			->select("member_working_hour.id",
				"day_of_week.id as day_of_week_id",
				"day_of_week.description as day_of_week",
				DB::raw("coalesce(TIME_FORMAT(member_working_hour.start_time, '%H:%i'),'') as start_time"),
				DB::raw("coalesce(TIME_FORMAT(member_working_hour.end_time, '%H:%i'),'') as end_time"),
				DB::raw("coalesce(member_working_hour.info,'') as info"))
			->orderBy("day_of_week.order_index");

		$d = datatables()::of($query);

		return $d->make(true);
	}
}