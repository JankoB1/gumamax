<?php namespace App\Gumamax\Users;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 15.9.2015
 * Time: 22:19
 */


use Delmax\Webapp\Traits\CyrToLatTrait;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class SaveAccountBasicInfoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CyrToLatTrait;

    private $user_id;
    private $first_name;
    private $last_name;
    private $phone_number;

    private $customer_type_id;
    private $company_name;
    private $tax_identification_number;
    private $receive_newsletter;


    /**
     * Create a new job instance.
     * @param $user_id
     * @param $first_name
     * @param $last_name
     * @param $phone_number
     * @param $customer_type_id
     * @param $company_name
     * @param $tax_identification_number
     * @param $receive_newsletter
     */
    public function __construct(Array $data)
    {
        $this->user_id      = $data['user_id'];

        $this->first_name   = $data['first_name'];
        $this->last_name    = $data['last_name'];
        $this->phone_number = $data['phone_number'];

        /* UserCustomer */
        $this->customer_type_id = $data['customer_type_id'];
        $this->company_name = $data['company_name'];
        $this->tax_identification_number = $data['tax_identification_number'];
        $this->receive_newsletter = $data['receive_newsletter'];
    }

    public function handle()
    {
        $user = User::findOrFail($this->user_id);

        $user->first_name = $this->transliterateStr($this->first_name);
        $user->last_name = $this->transliterateStr($this->last_name);
        $user->phone_number = $this->phone_number;
        $user->save();

        $user->customer->customer_type_id=$this->customer_type_id;
        $user->customer->company_name=$this->transliterateStr($this->company_name);
        $user->customer->tax_identification_number=$this->tax_identification_number;
        $user->customer->receive_newsletter=$this->receive_newsletter;
        $user->customer->save();

        return $user;
    }

}