<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 25.9.2016
 * Time: 1:05
 */

namespace Gumamax\Partners;


use App\Jobs\Job;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterPartnerJob implements ShouldQueue
{
    use DispatchesJobs;

    /**
     * @var
     */
    private $name;
    /**
     * @var
     */
    private $department;
    /**
     * @var
     */
    private $is_installer;
    /**
     * @var
     */
    private $tax_identification_number;
    /**
     * @var
     */
    private $city_id;
    /**
     * @var
     */
    private $country_id;
    /**
     * @var
     */
    private $address;
    /**
     * @var
     */
    private $latitude;
    /**
     * @var
     */
    private $longitude;
    /**
     * @var
     */
    private $first_name;
    /**
     * @var
     */
    private $last_name;
    /**
     * @var
     */
    private $phone;
    /**
     * @var
     */
    private $email;
    /**
     * @var
     */
    private $web_address;

    public function __construct($name, $department, $is_installer, $tax_identification_number,
                                $city_id, $country_id, $address, $latitude, $longitude,
                                $first_name, $last_name, $phone, $email, $web_address
                                )
    {

        $this->name = $name;
        $this->department = $department;
        $this->is_installer = $is_installer;
        $this->tax_identification_number = $tax_identification_number;
        $this->city_id = $city_id;
        $this->country_id = $country_id;
        $this->address = $address;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->phone = $phone;
        $this->email = $email;
        $this->web_address = $web_address;
    }

    public function handle(){

    }

}