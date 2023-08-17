<?php
namespace Delmax\User;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 16.9.2015
 * Time: 1:49
 */

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;


class ChangePasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    private $user_id;
    private $password;


    /**
     * Create a new job instance.
     * @param $user_id
     * @param $password
     */
    public function __construct(Array $data)
    {
        $this->user_id    = $data['user_id'];
        $this->password   = $data['password'];
    }

    public function handle()
    {
        $user = User::findOrFail($this->user_id);

        $user->password = bcrypt($this->password);

        $user->save();

        return true;
    }

}