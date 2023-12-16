<?php namespace Delmax\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class Product extends Model {

    use SoftDeletes;

    protected $connection = 'delmax_catalog';

    protected $table = 'product';

    protected $primaryKey = 'product_id';

    public static function getRange($from, $to, $descriptions=[]){

        $descriptionsSQL = implode(',', $descriptions);

        $client = new Client();

        $url  = config('services.delmaxapi.url').'/product/tyres/range';

        $user = config('services.delmaxapi.username');

        $pwd = config('services.delmaxapi.password');

        $response = $client->post($url,
                [
                    'auth' =>  [$user, $pwd],
                    'body' => [
                        'merchant_id'=>'8080',
                        'company_id'=>'8000',
                        'descriptions'=>$descriptionsSQL,
                        'from' => $from,
                        'to' => $to
                    ]
                ]);

        return $response->json();

    }

    public function addBetterPrice(BetterPrice $newBetterPrice){

        return $this->betterPrices()->save($newBetterPrice);

    }

    public function betterPrices(){

        return $this->hasMany(BetterPrice::class, 'product_id');

    }

}

/**
 * ProductException Exception Handler
 */
class ProductException extends Exception {

    /**
     * Constructor
     *
     * @param string $message Error message
     * @param int $code Error code
     * @param Exception $previous
     */
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class ProductNotFoundException extends ProductException {

    private $product_id;

    /**
     * Constructor
     *
     * @param string $product_id
     * @param int $code Error code
     * @param Exception $previous
     * @internal param string $message Error message
     */
    public function __construct($product_id, $code = 0, Exception $previous = null) {
        $this->product_id=$product_id;
        parent::__construct($this->format($product_id), $code, $previous);
    }

    function format($product_id) {
        return "Product Id: " . $product_id . " not found!!";
    }

    function getProduct_id() {
        return $this->product_id;
    }

}