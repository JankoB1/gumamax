<?php namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 17.3.2015
 * Time: 19:43
 */

use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Response;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 8/6/14
 * Time: 11:30 PM
 */

class ApiController extends Controller{

    public $data;

    protected $total;

    protected $perPage;

    protected $currentPage;

    protected $order;

    protected $paginator;

    protected $query;

    /**
     * @var
     */
    protected $statusCode=IlluminateResponse::HTTP_OK;


    /**
     * @param mixed $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function respondNotFound($message = 'Not found!')
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)->respondWithError($message);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function respondUnprocessable($message = 'Unprocessable entity!')
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function respondNoContent($message = 'No content!')
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NO_CONTENT)->respondWithError($message);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function respondInternalError($message = 'Internal error!')
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    /**
     * @param $message
     * @param int $errorCode
     * @return mixed
     */
    public function respondWithError($message, $errorCode=0)
    {
        $error = [
            'error'=>[
                'message'=> $message,
                'status_code'=>$this->getStatusCode(),
                'error_code' => $errorCode
            ]
        ];

        return $this->respond($error);
    }

    public function respondWithInfo($message)
    {
        $error = [
            'info'=>[
                'message'=> $message,
                'status_code'=>$this->getStatusCode(),
            ]
        ];

        return $this->respond($error);
    }

    /**
     * @param $message
     * @return mixed
     */
    protected function respondCreated($message)
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_CREATED)->respondWithMessage($message);
    }

    protected function respondWithMessage($message)
    {
        return $this->respond([
            'message' => $message
        ]);
    }

    /**
     * @param $data
     * @param array $headers
     * @param array $jsonParams
     * @return mixed
     */
    public function respondWithData($data, $headers=[], $jsonParams=null)
    {
        return Response::json(compact('data'), $this->getStatusCode(), $headers, $jsonParams);
    }

    /**
     * @param $data
     * @param array $headers
     * @param array $jsonParams
     * @return mixed
     */
    public function respond($data, $headers=[], $jsonParams=null)
    {
        return Response::json($data, $this->getStatusCode(), $headers, $jsonParams);
    }

    public function respondWithCallback($data, $callback, $headers=[])
    {
        return Response::json($data, $this->getStatusCode(), $headers)->setCallback($callback);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function setPaginationRequest(Request $request)
    {

        $this->perPage      = (int) $request->get('per_page', 15);

        $this->currentPage  = (int) $request->get('page', 1);

        $this->order        = $request->get('order','');

    }

    /**
     * @param $data
     * @param $total
     * @return array
     */
    public function getPagination($data, $total){

        $this->paginator =  new Paginator($data, $total,  $this->perPage, $this->currentPage);

        $totalPages = (int) ceil($total/$this->perPage);

        $next = (($totalPages>0)&&($this->currentPage < $totalPages)) ? $this->currentPage + 1 : 0;

        $prev = (($totalPages>0)&&($this->currentPage > 1)) ? $this->currentPage - 1 : 0;

        return [
            'pagination'=> [

                'total'=> $total,

                'current_page'=>  $this->currentPage,

                'last_page' => $totalPages,

                'per_page' => $this->perPage,

                'next_page' => $next,

                'prev_page' => $prev

            ]
        ];
    }

    public function attachPaginator(){

        $p = $this->getPagination($this->data, $this->total);

        $this->data = array_merge($p, $this->data);

    }

    public function respondWithPagination($headers = [])
    {
        $this->attachPaginator();

        return $this->respond($this->data, $headers);

    }
}