<?php namespace Delmax\Addresses;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 17.9.2015
 * Time: 12:14
 */

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class SaveAddressJob extends Job implements SelfHandling
{
    private $data;
    /**
     * Create a new job instance.
     * @param array $data
     */
    public function __construct(array $data){

        $this->data = $data;

    }

    /**
     * Execute the job.
     *
     * @return static
     */
    public function handle()
    {
        //$address = Address::register($this->data);

        //$repository->save($address);

        return $address;
    }
}