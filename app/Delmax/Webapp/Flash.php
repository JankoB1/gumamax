<?php namespace Delmax\Webapp;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 18.8.2015
 * Time: 21:40
 */


class Flash
{
    /**
     * @param $title
     * @param $message
     * @param $type
     * @param string $key
     * @return mixed
     */
    public function create($title, $message, $type, $key = 'flash_message')
    {
        return session()->flash($key, [
            'title'  => $title,
            'message'=> $message,
            'type'   => $type
        ]);
    }

    /**
     * @param $title
     * @param $message
     * @return mixed
     */
    public function info($title, $message)
    {
        return $this->create($title, $message, 'info');
    }

    /**
     * @param $title
     * @param $message
     * @return mixed
     */
    public function success($title, $message)
    {
        return $this->create($title, $message, 'success');
    }

    /**
     * @param $title
     * @param $message
     * @return mixed
     */
    public function error($title, $message)
    {
        return $this->create($title, $message, 'error');
    }

    /**
     * @param $title
     * @param $message
     * @param string $type
     * @return mixed
     */
    public function overlay($title, $message, $type='success')
    {
        return $this->create($title, $message, $type, 'flash_message_overlay');
    }
}