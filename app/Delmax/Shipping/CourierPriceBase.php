<?php namespace Delmax\Shipping;
use Delmax\Products\Product;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 2/23/14
 * Time: 10:04 AM
 */

class CourierPriceBase {

    public function __construct(){

    }

    private function tyreBase($vehicle_type) {
       switch ($vehicle_type){
           /* Ako ide cena po komadu ovde treba da se stavi PACKAGE*/
           case 'Putničko':
               return 'PACKAGE';
           break;
           case '4x4':
               return 'PACKAGE';
           break;
           case 'Dostavno vozilo':
               return 'PACKAGE';
           break;
           case 'Kamioni i autobusi':
               return 'WEIGHT';
               break;
           case 'Motocikli i skuteri':
               return 'PACKAGE';
               break;
           case 'Poljoprivredno vozilo':
               return 'WEIGHT';
               break;
           default :
               return 'WEIGHT';
               break;
       }

    }

    private function defaultBase() {
        return 'WEIGHT';
    }

    public function getByProduct(Product $product) {
        if ($product->description_id==1679){
            $vehicle_type = Product::getProductVehicleType($product->product_id);
            return $this->tyreBase($vehicle_type);
        } else {
           return $this->defaultBase();
        }
    }

    public function getByProductId($product_id) {
        $product = Product::find($product_id);
        return $this->getByProduct($product);
    }

    public function getByDescriptionIdAndVehicleCategory($descriptionId=null, $vehicleCategory=null) {
        if ($descriptionId==1679){
            return $this->tyreBase($vehicleCategory);
        } else {
            return $this->defaultBase();
        }
    }
} 