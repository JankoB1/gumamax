<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Crm\Models\Member;
use Crm\Models\Partner;
use Crm\Models\PartnerUser;
use Delmax\Addresses\Address;
use Delmax\Cart\Models\Order;
use Delmax\User\UserCustomer;
use Gumamax\Vehicles\UserVehicle;
use Illuminate\Support\Facades\DB;
use App\Notifications\ResetPasswordNotification;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $connection = 'ApiDB';
    protected $table = 'user';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    const IS_PERSON = 1;
    const IS_COMPANY = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password',
        'active',
        'activated_at'
    ];

    protected $guarded = ['user_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Register a new user
     *
     * @param $username
     * @param $email
     * @param $password
     * @param $first_name
     * @param $last_name
     * @param $phone_number
     * @param $active
     * @param $activated_at
     * @return static
     */
    public static function register($username, $email, $password,  $first_name, $last_name, $phone_number, $active,  $activated_at)
    {
        $user = new static(compact('username', 'email', 'password', 'first_name', 'last_name', 'phone_number', 'active', 'activated_at'));

        return $user;
    }

    /**
     * @return string
     */
    public function label(){
        return sprintf('%s %s.', $this->first_name, mb_substr($this->last_name,0,1,'UTF-8'));
    }

    public function fullName(){
        return $this->first_name .' '.$this->last_name;
    }

    public function activities(){

        return $this->hasMany('Delmax\User\Activity\UserActivity', 'user_id');

    }

    public function customer(){

        return $this->hasOne(UserCustomer::class, 'user_id');

    }

    public function addAsCustomer(UserCustomer $customer){

        return $this->customer()->save($customer);

    }

    public function partners(){

        return $this->belongsToMany(Partner::class, 'delmax_crm.partner_user', 'user_id', 'partner_id', 'id');

    }

    public function addAsPartner(PartnerUser $partner){

        return $this->partners()->save($partner);

    }

    public function disableAccount(){

        $this->activated_at=null;
        $this->active = 0;
        $this->save();

    }

    public function addresses(){

        return $this->morphMany(Address::class, 'addressable');

    }

    public function addAddress(Address $newAddress){
        Address::unsetDefaultAddress($this->addresses, $newAddress);
        return $this->addresses()->save($newAddress);
    }

    public function vehicles(){

        return $this->hasMany(UserVehicle::class, 'user_id', 'user_id');

    }

    public function addVehicle(UserVehicle $vehicle){

        return $this->vehicles()->save($vehicle);

    }

    public function members(){

        return $this->belongsToMany(Member::class, 'delmax_crm.member_user_role', 'user_id', 'member_id');

    }

    public function projects(){

        $sql = "select
					distinct p.id as project_id,
					p.name as project_name,
					p.note,
					p.start_date,
					p.end_date,
					p.icon,
					mu.role_id
				from member_user_role mu
				  join member m on m.id=mu.member_id and m.deleted_at is null
				  join project p on p.id=m.project_id and p.deleted_at is null
				  join fw_001.rbac_user_role ur on mu.role_id=ur.role_id
				where mu.user_id= ? and mu.deleted_at is null";
        return DB::connection('CRM')->select($sql, [$this->user_id]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carts()
    {
        return $this->hasMany('Delmax\Cart\Models\Cart', 'user_id');
    }

    public function orders(){

        return $this->hasMany(Order::class, 'user_id')->select(['number','date','amount_with_tax','tour','dispatch_date',
            'dispatch_time','shipping_recipient','shipping_address','shipping_postal_code','shipping_city','shipping_phone']);
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {

        $this->notify(new ResetPasswordNotification($token));
    }
}
