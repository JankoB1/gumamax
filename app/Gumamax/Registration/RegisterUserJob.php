<?php namespace Gumamax\Registration;

use Delmax\User\UserCustomer;
use Gumamax\Vehicles\UserVehicle;
use App\Role;
use App\Models\User;
use Gumamax\Users\UserRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class RegisterUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $username;
    protected $email;
    protected $password;
    protected $first_name;
    protected $last_name;
    protected $phone_number;
    protected $activated_at;

    protected $customer_type_id;
    protected $company_name;
    protected $tax_identification_number;
    /**
     * @var
     */
    private $year_of_production;
    /**
     * @var
     */
    private $mfa_id;
    /**
     * @var
     */
    private $mod_id;
    /**
     * @var
     */
    private $typ_id;
    /**
     * @var
     */
    private $commercial_description;
    /**
     * @var
     */
    private $vin;
    /**
     * @var
     */
    private $engine_code;
    /**
     * @var
     */
    private $receive_newsletter;
    /**
     * @var
     */
    private $active;


    public function __construct(Array $data) {
       
        $this->username     = $data['username'];
        $this->email        = $data['email'];
        $this->password     = bcrypt($data['password']);
        $this->first_name   = $data['first_name'];
        $this->last_name    = $data['last_name'];
        $this->phone_number = $data['phone_number'];
        $this->activated_at = date('Y-m-d H:i:s');

        /* UserCustomer */
        $this->customer_type_id = $data['customer_type_id'];
        $this->company_name = $data['company_name'];
        $this->tax_identification_number = $data['tax_identification_number'];
        $this->receive_newsletter = $data['receive_newsletter'];

        /* UserVehicle */
        $this->year_of_production = $data['year_of_production'];
        $this->mfa_id = $data['mfa_id'];
        $this->mod_id = $data['mod_id'];
        $this->typ_id = $data['typ_id'];
        $this->commercial_description = $data['commercial_description'];
        $this->vin          = $data['vin'];
        $this->engine_code  = $data['engine_code'];

        $this->active = $data['active'];
    }    

    public function handle(UserRepository $repository)
    {
        $user = User::register(
            $this->username,
            $this->email,
            $this->password,
            $this->first_name,
            $this->last_name,
            $this->phone_number,
            $this->active,
            $this->activated_at);

        $repository->save($user);

        $user->attachRole(Role::gmxUser());

        $customer = UserCustomer::makeByValues($this->customer_type_id, $this->company_name, $this->tax_identification_number, $this->receive_newsletter);

        $user->addAsCustomer($customer);

        if ($this->typ_id>0) {

            $vehicle = UserVehicle::makeByValues($this->year_of_production, $this->mfa_id, $this->mod_id, $this->typ_id, $this->commercial_description, $this->vin, $this->engine_code);

            $user->addVehicle($vehicle);

        }

        event('user.created', $user);

        Auth::login($user);
    }

}