<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nikola
 * Date: 9/28/13
 * Time: 10:20 AM
 */

function getIpAddress() {
    return (empty($_SERVER['HTTP_CLIENT_IP'])?(empty($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR']):$_SERVER['HTTP_CLIENT_IP']);
}

function objectToArray($d) {
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    }
    else {
        // Return array
        return $d;
    }
}

function arrayToObject($array){
    $object = new stdClass();
    foreach ($array as $key => $value)
    {
        $object->$key = $value;
    }
    return $object;
}



function getTokensArray($s, $delimiter='|'){
    if ($s!==''){
        $tokens = explode($delimiter, $s);
        foreach($tokens as $token){
            $token = '"'.$token.'"';
        }
        return $tokens;
    } else {
        return $s;
    }
}

function isEmpty($field){
    return ($field === '' || $field === null);
}

	/**
     * returns the rounded value of $value to specified precision, according to your configuration;
     *
     * @note : PHP 5.3.0 introduce a 3rd parameter mode in round function
     *
     * @param float $value
     * @param int $precision
     * @return float
     */
function gmRound($value, $precision = 0)
{
    $method = Config::get('gumamax.price_round_method');
    if ($method == 'PRICE_ROUND_UP')
        return Tools::ceilf($value, $precision);
    elseif ($method == 'PRICE_ROUND_DOWN')
        return Tools::floorf($value, $precision);
    return round($value, $precision);
}

/**
 * returns the rounded value down of $value to specified precision
 *
 * @param float $value
 * @param int $precision
 * @return float
 */
function ceilf($value, $precision = 0)
{
    $precision_factor = $precision == 0 ? 1 : pow(10, $precision);
    $tmp = $value * $precision_factor;
    $tmp2 = (string)$tmp;
    // If the current value has already the desired precision
    if (strpos($tmp2, '.') === false)
        return ($value);
    if ($tmp2[strlen($tmp2) - 1] == 0)
        return $value;
    return ceil($tmp) / $precision_factor;
}

	/**
     * returns the rounded value up of $value to specified precision
     *
     * @param float $value
     * @param int $precision
     * @return float
     */
function floorf($value, $precision = 0)
{
    $precision_factor = $precision == 0 ? 1 : pow(10, $precision);
    $tmp = $value * $precision_factor;
    $tmp2 = (string)$tmp;
    // If the current value has already the desired precision
    if (strpos($tmp2, '.') === false)
        return ($value);
    if ($tmp2[strlen($tmp2) - 1] == 0)
        return $value;
    return floor($tmp) / $precision_factor;
}

function buildCartQtyOptions($qty, $available_qty){
    $result='';
    $maxQty = max($qty, $available_qty);
    if ($qty==0) {
        $result .= '<option value="0" selected>0</option>';
    } else
    for ($i = 1; $i <= $maxQty; $i++) {
        $selected = ($i==$qty) ? "selected" : "";
        $result .= '<option '.$selected.' value="'.$i.'">'.$i.'</option>';
    }

    return $result;
}

function buildCartQtyLabel($old_qty){
    if (is_null($old_qty)||($old_qty==0))
        return "Količina";
        else
        return "Naručeno: ".number_format($old_qty,2,'.',',')."<br>Rezervisano";
}

function checkCookie($name){

    return isset($_COOKIE[$name]) &&  ($_COOKIE[$name]!='') && ($_COOKIE[$name]!='""' && ($_COOKIE[$name]!='null'));

}

function flash($title=null, $message=null){

    $flash=app(Delmax\Webapp\Flash::class);

    if (func_num_args()==0){

        return $flash;
    }

    return $flash->info($title, $message);
}

function subdomain($key=null){

    $subdomain = app(Delmax\Webapp\Subdomain::class);

    if (is_array($key)) {
        $subdomain->create($key['member_id'], $key['title'], $key['name'], $key['erp_company_id'], $key['erp_partner_id']);
    }

    if ($key=='member_id'){
        return $subdomain->getMemberId();
    }

    if ($key=='title'){
        return $subdomain->getTitle();
    }

    if ($key=='name'){
        return $subdomain->getName();
    }

    if ($key=='erp_partner_id'){
        return $subdomain->getErpPartnerId();
    }

    if ($key=='erp_company_id'){
        return $subdomain->getErpCompanyId();
    }

    return $subdomain;
}


function buildTreeArray(array &$elements, $parentId = 0, $idField='id', $parentIdField='parent_id', $childrenArrName='children') {

    $branch = array();

    foreach ($elements as &$element) {

        if ($element[$parentIdField] == $parentId) {
            $children = buildTreeArray($elements, $element[$idField], $idField, $parentIdField, $childrenArrName);

            if ($children) {
                usort($children, function($a, $b) {
                    return $a['order_index'] - $b['order_index'];
                });
                $element[$childrenArrName] = $children;
            }
            $branch[$element[$idField]] = $element;
            unset($element);
        }
    }
    return $branch;
}

function appgmx(){

    $appgmx = app(Gumamax\GumamaxApp::class);

    if (func_num_args()==0){

        return $appgmx;
    }

}

function smartTruncate($str,  $n, $useWordBoundary=true){

    $isTooLong =  strlen($str) > $n;

    $s_ = $isTooLong ? substr($str, 0, $n-1) : $str;

    $s_ = ($useWordBoundary && $isTooLong) ?substr( $s_, 0, strrpos( substr( $s_, 0, 35), ' ' ) ) : $s_;

    return  $isTooLong ? $s_ . '&hellip;' : $s_;

};
