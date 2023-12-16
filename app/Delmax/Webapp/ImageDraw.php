<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 18.9.2016
 * Time: 7:53
 */

namespace Delmax\Webapp;

use Delmax\Cart\Models\Order;

define('DPI_CORRECT', 3.125);

//Outline type
define('OT_IMAGE', 0);
define('OT_ATTR_VAL', 21);
define('OT_TEXT', 22); //ne prikazuje se (interna Temot upotreba)
define('OT_ATTR_KEY', 40);
define('OT_SOLID_LINE', 41);
define('OT_DASH_LINE', 42);

define('EMS_IMG_WIDTH', 2625);
define('EMS_IMG_HEIGHT', 1875);
define('EX_IMG_WIDTH', 2500);
define('EX_IMG_HEIGHT', 780);
define('SP_IMG_WIDTH', 2625);
define('SP_IMG_HEIGHT', 1875);

define('IMAGE_FLIP_HORIZONTAL', 1);
define('IMAGE_FLIP_VERTICAL', 2);
define('IMAGE_FLIP_BOTH', 3);

class ImageDraw {
    private function CalculateScale($aValue, $aZoom) {
        return round(($aValue * $aZoom)/100);
    }

    private function ImageSizeByModule($aModule) {
        if ($aModule == 'EMS_')  {
            $width = round(EMS_IMG_WIDTH / DPI_CORRECT);
            $height = round(EMS_IMG_HEIGHT / DPI_CORRECT);
        }
        elseif ($aModule == 'EX_') {
            $width = round(EX_IMG_WIDTH / DPI_CORRECT);
            $height = round(EX_IMG_HEIGHT / DPI_CORRECT);
        }
        else {
            $width = round(SP_IMG_WIDTH / DPI_CORRECT);
            $height = round(SP_IMG_HEIGHT / DPI_CORRECT);
        }

        return array('width'=>$width, 'height'=>$height);
    }


    private function ImageFlip($imgsrc, $mode)
    {
        $width = imagesx($imgsrc);
        $height = imagesy($imgsrc);

        $src_x = 0;
        $src_y = 0;
        $src_width = $width;
        $src_height = $height;

        switch ((int) $mode)
        {
            case  IMAGE_FLIP_VERTICAL:
                $src_y = $height-1;
                $src_height = -$height;
                break;

            case IMAGE_FLIP_HORIZONTAL:
                $src_x = $width-1;
                $src_width = -$width;
                break;

            case IMAGE_FLIP_BOTH:
                $src_x = $width-1;
                $src_y = $height-1;
                $src_width = -$width;
                $src_height = -$height;
                break;

            default:
                return $imgsrc;
        }

        $imgdest = imagecreatetruecolor($width, $height);

        if (imagecopyresampled($imgdest, $imgsrc, 0, 0, $src_x, $src_y, $width, $height, $src_width, $src_height))
        {
            return $imgdest;
        }

        return $imgsrc;
    }

    public function DrawImage($aModule, $aData, $aImagePath) {
        $image_size = $this->ImageSizeByModule($aModule);
        $m_width = $image_size['width'];
        $m_height = $image_size['height'];
        $font_path = Config::get('temot-data.img_font_path');

        $module_image = imagecreatetruecolor($m_width, $m_height);

        $img = @imagecreatefrompng($aImagePath);
        $img_size = getimagesize($aImagePath);
        imagecopyresampled($module_image, $img, 0, 0, 0, 0, $m_width, $m_height, $img_size[0], $img_size[1]);

        //$clr_blue = imagecolorallocate($module_image, 0, 0, 255);
        $clr_black = imagecolorallocate($module_image, 0, 0, 0);

        foreach ($aData as $data) {
            $caption = '';
            $X = $data->X + 2;
            $Y = $data->Y + 11;

            if ($data->OUTLINE_TYPE == OT_ATTR_KEY) {
                $caption = $data->ATTR_DESCRIPT;
            } elseif ($data->OUTLINE_TYPE == OT_ATTR_VAL || ($data->OUTLINE_TYPE == OT_IMAGE)) {
                $caption = $data->DESCRIPTION;
                if ($data->OUTLINE_TYPE == OT_IMAGE) {
                    $X += ($data->X_TEXT)/3.125;
                    $Y += ($data->Y_TEXT)/3.125;
                }
            }
            if (!empty($caption)) {
                imagettftext($module_image, 8, 0, $X, $Y, $clr_black, $font_path.'arial.ttf', $caption);
            }
        }
        /* OT_TEXT se ne prikazuje*/
        /*elseif ($data->OUTLINE_TYPE == OT_TEXT) {
          $c_red = imagecolorallocate($module_image, 255, 0, 0);

          imagettftext($module_image, 8, 0, $data->X+2, $data->Y+12, $c_red, $font_path.'arial.ttf', $data->DESCRIPTION);
        }*/

        header ("Content-type: image/gif");
        return imagegif($module_image);
    }

    public static function drawUplatnica($aData, $dstFileName=null) {

        if (empty($aData))
            return false;

        $img_path = config('gumamax.img_path');
        $font_path = config('gumamax.img_font_path');
        $fontsize = 15;
        $path = $img_path . 'pay-order/';

        $im = @imagecreatefrompng($path.'empty_rs.png');
        if (!$im) {
            return false;
        } else {
            $font = $font_path . 'arial.ttf';
            $font_color = imagecolorallocate($im, 181, 55, 38);

            imagettftext($im, $fontsize, 0, 45, 74, $font_color, $font,  $aData->uplatilac);
            imagettftext($im, $fontsize, 0, 45, 92, $font_color, $font,  $aData->adresa);
            imagettftext($im, $fontsize, 0, 45, 110, $font_color, $font, $aData->mesto);
            imagettftext($im, $fontsize, 0, 45, 166, $font_color, $font, $aData->svrha);
            imagettftext($im, $fontsize, 0, 45, 250, $font_color, $font, $aData->primalac);
            imagettftext($im, $fontsize, 0, 655, 98, $font_color, $font, $aData->iznos);
            imagettftext($im, $fontsize, 0, 570, 150, $font_color, $font, $aData->racun);
            imagettftext($im, $fontsize, 0, 570, 202, $font_color, $font, $aData->poziv);

            if (is_null($dstFileName)){

                header("Content-type: image/png");

            }

            return imagepng($im, $dstFileName);
        }
    }

    public static function renderPayOrder(Order $order){

        if (!is_null($order)){
            $path = public_path().'/img/pay-order/';
            $filename = $order->id.'.png';
            $filepath = $path . $filename;
            if (file_exists($filepath)){

                return $filename;

            }

            $ban = str_replace('-','', config('gumamax.default_bank_account_number'));
            $banB = substr($ban,0,3);
            $banA = substr($ban,3,strlen($ban)-5);
            $banC = substr($ban,-2,2);

            $data['uplatilac'] = $order->user->first_name.' '.$order->user->last_name;

            /*if (!is_null($a)) {
                $data['adresa'] = $a->fulladdress;
                $data['mesto'] = $a->postal_code.' '.$a->city_name;
            }else {
                $data['adresa'] = '';
                $data['mesto'] = '';
            }
            */
            $data['adresa'] = '';
            $data['mesto'] = '';

            $data['svrha'] = "Online kupovina\nGumamax.com";
            $data['primalac'] = $order->merchant->name."\n".$order->merchant->address.",".$order->merchant->city->postal_code." ".$order->merchant->city->city_name;
            $total_amount = $order->amount_with_tax + $order->shipping_amount_with_tax;
            $data['iznos'] = "=".number_format($total_amount,2,',',' ');
            $data['racun'] = $banB."-".ltrim($banA,"0")."-".$banC;
            $data['poziv'] = config('gumamax.partner_id_internet_prodaja', ''). '-' .$order->number;

            if (self::drawUplatnica((object)$data, $filepath))
            {
              return $filename;
            }
        }
    }
}
